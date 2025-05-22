-- Create the SportTyping database
CREATE DATABASE IF NOT EXISTS sport_typing_db;
USE sport_typing_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100),
    email_verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Text Categories table
CREATE TABLE IF NOT EXISTS text_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Leagues table
CREATE TABLE IF NOT EXISTS leagues (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    min_experience INT UNSIGNED NOT NULL DEFAULT 0,
    max_experience INT UNSIGNED NULL,
    icon VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Badges table
CREATE TABLE IF NOT EXISTS badges (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    icon VARCHAR(255),
    requirement_type ENUM('experience', 'accuracy', 'speed', 'competitions', 'wins', 'lessons') NOT NULL,
    requirement_value INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Typing Texts table
CREATE TABLE IF NOT EXISTS typing_texts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    word_count INT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    difficulty_level ENUM('beginner', 'intermediate', 'advanced', 'expert') NOT NULL DEFAULT 'beginner',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES text_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Profiles table
CREATE TABLE IF NOT EXISTS user_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    avatar VARCHAR(255),
    bio TEXT,
    typing_speed_avg DECIMAL(6,2) DEFAULT 0.00,
    typing_accuracy_avg DECIMAL(5,2) DEFAULT 0.00,
    total_competitions INT UNSIGNED DEFAULT 0,
    total_wins INT UNSIGNED DEFAULT 0,
    current_league_id BIGINT UNSIGNED,
    total_experience INT UNSIGNED DEFAULT 0,
    device_preference ENUM('mobile', 'pc', 'both') DEFAULT 'both',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (current_league_id) REFERENCES leagues(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Devices table
CREATE TABLE IF NOT EXISTS devices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    type ENUM('mobile', 'pc') NOT NULL,
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Competitions table
CREATE TABLE IF NOT EXISTS competitions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    start_time TIMESTAMP NOT NULL,
    end_time TIMESTAMP,
    status ENUM('upcoming', 'active', 'completed') NOT NULL DEFAULT 'upcoming',
    device_type ENUM('mobile', 'pc', 'both') NOT NULL DEFAULT 'both',
    text_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (text_id) REFERENCES typing_texts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Competition Participants table
CREATE TABLE IF NOT EXISTS competition_participants (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    competition_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    device_id BIGINT UNSIGNED,
    is_bot BOOLEAN DEFAULT FALSE,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (competition_id) REFERENCES competitions(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (device_id) REFERENCES devices(id) ON DELETE SET NULL,
    UNIQUE KEY competition_user (competition_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Competition Results table
CREATE TABLE IF NOT EXISTS competition_results (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    competition_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    typing_speed DECIMAL(6,2) NOT NULL COMMENT 'Words per minute',
    typing_accuracy DECIMAL(5,2) NOT NULL COMMENT 'Percentage',
    completion_time INT UNSIGNED COMMENT 'Time in seconds',
    position INT UNSIGNED COMMENT 'Ranking in competition',
    experience_earned INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (competition_id) REFERENCES competitions(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY competition_user_result (competition_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Typing Lessons table
CREATE TABLE IF NOT EXISTS typing_lessons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    difficulty_level ENUM('beginner', 'intermediate', 'advanced', 'expert') NOT NULL DEFAULT 'beginner',
    order_number INT UNSIGNED NOT NULL,
    content LONGTEXT NOT NULL,
    estimated_completion_time INT UNSIGNED COMMENT 'Time in minutes',
    experience_reward INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Lesson Progress table
CREATE TABLE IF NOT EXISTS lesson_progress (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    lesson_id BIGINT UNSIGNED NOT NULL,
    completion_status ENUM('not_started', 'in_progress', 'completed') NOT NULL DEFAULT 'not_started',
    highest_speed DECIMAL(6,2) DEFAULT 0.00,
    highest_accuracy DECIMAL(5,2) DEFAULT 0.00,
    experience_earned INT UNSIGNED NOT NULL DEFAULT 0,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES typing_lessons(id) ON DELETE CASCADE,
    UNIQUE KEY user_lesson (user_id, lesson_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Badges table
CREATE TABLE IF NOT EXISTS user_badges (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    badge_id BIGINT UNSIGNED NOT NULL,
    earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE,
    UNIQUE KEY user_badge (user_id, badge_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Experience table
CREATE TABLE IF NOT EXISTS user_experience (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    amount INT UNSIGNED NOT NULL,
    source_type ENUM('competition', 'lesson', 'practice', 'achievement') NOT NULL,
    source_id BIGINT UNSIGNED NOT NULL COMMENT 'Polymorphic ID referencing the source',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Leaderboards table
CREATE TABLE IF NOT EXISTS leaderboards (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('global', 'league', 'country', 'device_type') NOT NULL DEFAULT 'global',
    device_type ENUM('mobile', 'pc', 'both') NOT NULL DEFAULT 'both',
    category_id BIGINT UNSIGNED NULL,
    league_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES text_categories(id) ON DELETE SET NULL,
    FOREIGN KEY (league_id) REFERENCES leagues(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Leaderboard Entries table
CREATE TABLE IF NOT EXISTS leaderboard_entries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    leaderboard_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    rank INT UNSIGNED NOT NULL,
    score DECIMAL(8,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (leaderboard_id) REFERENCES leaderboards(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY leaderboard_user_rank (leaderboard_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Practices table
CREATE TABLE IF NOT EXISTS user_practices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    text_id BIGINT UNSIGNED NOT NULL,
    typing_speed DECIMAL(6,2) NOT NULL COMMENT 'Words per minute',
    typing_accuracy DECIMAL(5,2) NOT NULL COMMENT 'Percentage',
    completion_time INT UNSIGNED COMMENT 'Time in seconds',
    experience_earned INT UNSIGNED NOT NULL DEFAULT 0,
    device_id BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (text_id) REFERENCES typing_texts(id) ON DELETE CASCADE,
    FOREIGN KEY (device_id) REFERENCES devices(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menambahkan kolom guest_id untuk pengunjung yang tidak login
CREATE TABLE IF NOT EXISTS guest_sessions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255) NOT NULL UNIQUE,
    device_type ENUM('mobile', 'pc') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default leagues (only if not exist)
INSERT IGNORE INTO leagues (name, description, min_experience, max_experience, icon) VALUES
('Novice', 'Beginning your typing journey', 0, 499, 'novice.png'),
('Apprentice', 'Developing your typing skills', 500, 1999, 'apprentice.png'),
('Journeyman', 'Consistent typing skill', 2000, 4999, 'journeyman.png'),
('Expert', 'Advanced typing skill', 5000, 9999, 'expert.png'),
('Master', 'Superior typing skill', 10000, 19999, 'master.png'),
('Grandmaster', 'Exceptional typing skill', 20000, 49999, 'grandmaster.png'),
('Legend', 'Legendary typing skill', 50000, NULL, 'legend.png');

-- Insert default badges (only if not exist)
INSERT IGNORE INTO badges (name, description, icon, requirement_type, requirement_value) VALUES
-- Experience-based badges
('Rookie', 'Earned 500 experience points', 'rookie.png', 'experience', 500),
('Explorer', 'Earned 2,000 experience points', 'explorer.png', 'experience', 2000),
('Veteran', 'Earned 10,000 experience points', 'veteran.png', 'experience', 10000),
('Champion', 'Earned 25,000 experience points', 'champion.png', 'experience', 25000),
('Legend', 'Earned 50,000 experience points', 'legend.png', 'experience', 50000),

-- Speed-based badges
('Swift Fingers', 'Achieved 40 WPM typing speed', 'swift_fingers.png', 'speed', 40),
('Speed Demon', 'Achieved 70 WPM typing speed', 'speed_demon.png', 'speed', 70),
('Lightning Hands', 'Achieved 100 WPM typing speed', 'lightning_hands.png', 'speed', 100),
('Sonic Typist', 'Achieved 130 WPM typing speed', 'sonic_typist.png', 'speed', 130),
('Typing God', 'Achieved 150+ WPM typing speed', 'typing_god.png', 'speed', 150),

-- Accuracy-based badges
('Precise', 'Achieved 90% typing accuracy', 'precise.png', 'accuracy', 90),
('Perfectionist', 'Achieved 95% typing accuracy', 'perfectionist.png', 'accuracy', 95),
('Flawless', 'Achieved 98% typing accuracy', 'flawless.png', 'accuracy', 98),
('Impeccable', 'Achieved 100% typing accuracy', 'impeccable.png', 'accuracy', 100),

-- Competition-based badges
('Competitor', 'Participated in 10 competitions', 'competitor.png', 'competitions', 10),
('Regular', 'Participated in 50 competitions', 'regular.png', 'competitions', 50),
('Dedicated', 'Participated in 100 competitions', 'dedicated.png', 'competitions', 100),
('Iron Will', 'Participated in 500 competitions', 'iron_will.png', 'competitions', 500),

-- Wins-based badges
('Winner', 'Won 5 competitions', 'winner.png', 'wins', 5),
('Champion', 'Won 25 competitions', 'champion_wins.png', 'wins', 25),
('Dominant', 'Won 50 competitions', 'dominant.png', 'wins', 50),
('Unstoppable', 'Won 100 competitions', 'unstoppable.png', 'wins', 100),

-- Lesson-based badges
('Student', 'Completed 5 typing lessons', 'student.png', 'lessons', 5),
('Scholar', 'Completed 15 typing lessons', 'scholar.png', 'lessons', 15),
('Master Student', 'Completed 30 typing lessons', 'master_student.png', 'lessons', 30),
('Professor', 'Completed all typing lessons', 'professor.png', 'lessons', 50);

-- Insert default text categories (only if not exist)
INSERT IGNORE INTO text_categories (name, description) VALUES
('Programming', 'Typing practice with code snippets and programming concepts'),
('Literature', 'Excerpts from famous books and stories'),
('Science', 'Scientific texts and explanations'),
('Technology', 'Texts about technology and digital world'),
('Business', 'Business-related content and professional terminology'),
('Random', 'Miscellaneous texts for varied practice');

-- Create or modify indexes
CREATE INDEX IF NOT EXISTS idx_user_profiles_experience ON user_profiles(total_experience);
CREATE INDEX IF NOT EXISTS idx_typing_texts_category ON typing_texts(category_id);
CREATE INDEX IF NOT EXISTS idx_typing_texts_difficulty ON typing_texts(difficulty_level);
CREATE INDEX IF NOT EXISTS idx_competitions_status ON competitions(status);
CREATE INDEX IF NOT EXISTS idx_competitions_device ON competitions(device_type);
CREATE INDEX IF NOT EXISTS idx_user_experience_source ON user_experience(source_type, source_id);
CREATE INDEX IF NOT EXISTS idx_lesson_progress_status ON lesson_progress(completion_status);
CREATE INDEX IF NOT EXISTS idx_user_practices_user ON user_practices(user_id, created_at);
CREATE INDEX IF NOT EXISTS idx_badges_requirement ON badges(requirement_type, requirement_value);

-- Drop triggers if they exist and recreate
DROP TRIGGER IF EXISTS check_experience_badges;
DROP TRIGGER IF EXISTS update_total_experience;
DROP TRIGGER IF EXISTS update_user_league;

DELIMITER //

-- Trigger to check and award experience-based badges
CREATE TRIGGER check_experience_badges AFTER UPDATE ON user_profiles
FOR EACH ROW
BEGIN
    DECLARE badge_id BIGINT;
    DECLARE done INT DEFAULT FALSE;
    DECLARE badge_cursor CURSOR FOR 
        SELECT id FROM badges 
        WHERE requirement_type = 'experience' AND requirement_value <= NEW.total_experience;
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
END//

-- Trigger to update total_experience in user_profiles when new experience is added
CREATE TRIGGER update_total_experience AFTER INSERT ON user_experience
FOR EACH ROW
BEGIN
    UPDATE user_profiles 
    SET total_experience = total_experience + NEW.amount
    WHERE user_id = NEW.user_id;
END//

-- Trigger to check and update user's league based on experience
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
END//

DELIMITER ;