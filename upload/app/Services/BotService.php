<?php
namespace App\Services;

class BotService
{
    const BOT_NAMES = [
        'SpeedTyper', 'KeyMaster', 'WordWizard', 'TypeFury', 
        'KeyboardNinja', 'LetterDash', 'TextRunner', 'WordRacer'
    ];
    
    const BOT_AVATARS = [
        'bot_blue.png', 'bot_red.png', 'bot_green.png', 
        'bot_yellow.png', 'bot_purple.png'
    ];
    
    public function generateBots($count, $difficultyLevel = 'intermediate')
    {
        $bots = [];
        
        // Get random names
        $names = collect(self::BOT_NAMES)->shuffle()->take($count);
        
        for ($i = 0; $i < $count; $i++) {
            $bot = [
                'id' => 'bot_' . ($i + 1),
                'name' => $names[$i],
                'avatar' => self::BOT_AVATARS[array_rand(self::BOT_AVATARS)],
                'is_bot' => true,
                'typing_speed' => $this->getTypingSpeedForDifficulty($difficultyLevel),
                'accuracy' => $this->getAccuracyForDifficulty($difficultyLevel),
            ];
            
            $bots[] = $bot;
        }
        
        return $bots;
    }
    
    private function getTypingSpeedForDifficulty($difficulty)
    {
        return match($difficulty) {
            'beginner' => rand(20, 35),
            'intermediate' => rand(40, 60),
            'advanced' => rand(65, 85),
            'expert' => rand(90, 120),
            default => rand(40, 60),
        };
    }
    
    private function getAccuracyForDifficulty($difficulty)
    {
        return match($difficulty) {
            'beginner' => rand(80, 90),
            'intermediate' => rand(85, 95),
            'advanced' => rand(90, 98),
            'expert' => rand(95, 99),
            default => rand(85, 95),
        };
    }
}