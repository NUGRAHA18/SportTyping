<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Create trigger to update total_experience when new experience is added
        DB::unprepared('
            CREATE TRIGGER update_total_experience AFTER INSERT ON user_experience
            FOR EACH ROW
            BEGIN
                UPDATE user_profiles 
                SET total_experience = total_experience + NEW.amount
                WHERE user_id = NEW.user_id;
            END
        ');

        // Create trigger to check and award experience-based badges
        DB::unprepared('
            CREATE TRIGGER check_experience_badges AFTER UPDATE ON user_profiles
            FOR EACH ROW
            BEGIN
                DECLARE badge_id BIGINT;
                DECLARE done INT DEFAULT FALSE;
                DECLARE badge_cursor CURSOR FOR 
                    SELECT id FROM badges 
                    WHERE requirement_type = "experience" AND requirement_value <= NEW.total_experience;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
                
                OPEN badge_cursor;
                
                badge_loop: LOOP
                    FETCH badge_cursor INTO badge_id;
                    IF done THEN
                        LEAVE badge_loop;
                    END IF;
                    
                    -- Check if user already has this badge
                    IF NOT EXISTS (SELECT 1 FROM user_badges WHERE user_id = NEW.user_id AND badge_id = badge_id) THEN
                        -- Award the badge
                        INSERT INTO user_badges (user_id, badge_id) VALUES (NEW.user_id, badge_id);
                    END IF;
                END LOOP;
                
                CLOSE badge_cursor;
            END
        ');

        // Create trigger to update user's league based on experience
        DB::unprepared('
            CREATE TRIGGER update_user_league AFTER UPDATE ON user_profiles
            FOR EACH ROW
            BEGIN
                DECLARE league_id_var BIGINT;
                
                -- Find the appropriate league based on experience
                SELECT id INTO league_id_var
                FROM leagues
                WHERE NEW.total_experience >= min_experience 
                AND (max_experience IS NULL OR NEW.total_experience <= max_experience)
                LIMIT 1;
                
                -- If league has changed, update it
                IF NEW.current_league_id <> league_id_var OR NEW.current_league_id IS NULL THEN
                    UPDATE user_profiles
                    SET current_league_id = league_id_var
                    WHERE id = NEW.id;
                END IF;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_total_experience');
        DB::unprepared('DROP TRIGGER IF EXISTS check_experience_badges');
        DB::unprepared('DROP TRIGGER IF EXISTS update_user_league');
    }
};