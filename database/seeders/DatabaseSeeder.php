<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\TypingText;
use App\Models\TypingLesson;
use App\Models\Competition;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user
        $testUser = User::factory()->create([
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        // Create user profile for test user
        UserProfile::create([
            'user_id' => $testUser->id,
            'current_league_id' => 1, // Novice league
            'typing_speed_avg' => 45.50,
            'typing_accuracy_avg' => 92.75,
            'total_experience' => 750,
        ]);

        // Create additional test users
        User::factory(10)->create()->each(function ($user) {
            UserProfile::create([
                'user_id' => $user->id,
                'current_league_id' => rand(1, 3), // Random league between 1-3
                'typing_speed_avg' => rand(20, 80),
                'typing_accuracy_avg' => rand(70, 99),
                'total_experience' => rand(0, 5000),
            ]);
        });

        // Seed sample typing texts
        $this->seedTypingTexts();
        
        // Seed sample lessons
        $this->seedTypingLessons();
        
        // Seed sample competitions
        $this->seedCompetitions();
    }
    
    private function seedTypingTexts(): void
    {
        $texts = [
            [
                'title' => 'JavaScript Basics',
                'content' => 'JavaScript is a programming language that adds interactivity to your website. It is one of the core technologies of web development and can be used on both the front-end and the back-end.',
                'word_count' => 31,
                'category_id' => 1, // Programming
                'difficulty_level' => 'beginner'
            ],
            [
                'title' => 'Python Functions',
                'content' => 'In Python, a function is a group of related statements that performs a specific task. Functions help break our program into smaller and modular chunks. As our program grows larger and larger, functions make it more organized and manageable.',
                'word_count' => 38,
                'category_id' => 1, // Programming
                'difficulty_level' => 'intermediate'
            ],
            [
                'title' => 'Classic Literature',
                'content' => 'It was the best of times, it was the worst of times, it was the age of wisdom, it was the age of foolishness, it was the epoch of belief, it was the epoch of incredulity.',
                'word_count' => 35,
                'category_id' => 2, // Literature
                'difficulty_level' => 'intermediate'
            ],
            [
                'title' => 'Science Exploration',
                'content' => 'The scientific method is a systematic approach to understanding the natural world through observation, hypothesis formation, experimentation, and analysis. It forms the foundation of all scientific inquiry.',
                'word_count' => 29,
                'category_id' => 3, // Science
                'difficulty_level' => 'advanced'
            ],
            [
                'title' => 'Technology Trends',
                'content' => 'Artificial intelligence and machine learning are transforming industries worldwide. From healthcare to finance, these technologies are creating new opportunities and solving complex problems.',
                'word_count' => 26,
                'category_id' => 4, // Technology
                'difficulty_level' => 'advanced'
            ],
        ];

        foreach ($texts as $text) {
            TypingText::create($text);
        }
    }
    
    private function seedTypingLessons(): void
    {
        $lessons = [
            [
                'title' => 'Home Row Position',
                'description' => 'Learn the basic home row position for touch typing',
                'difficulty_level' => 'beginner',
                'order_number' => 1,
                'content' => 'The home row is the middle row on your keyboard. For the standard QWERTY keyboard, it consists of the keys: ASDF for your left hand, and JKL; for your right hand. Place your fingers on these keys as your default resting position.',
                'estimated_completion_time' => 10,
                'experience_reward' => 50,
            ],
            [
                'title' => 'The F and J Keys',
                'description' => 'Understanding the guide keys F and J',
                'difficulty_level' => 'beginner',
                'order_number' => 2,
                'content' => 'The F and J keys usually have small bumps or ridges on them. These are tactile indicators to help you find the home row without looking at the keyboard. Your left index finger should rest on F, and your right index finger should rest on J.',
                'estimated_completion_time' => 15,
                'experience_reward' => 75,
            ],
            [
                'title' => 'Top Row Keys',
                'description' => 'Learning to type the top row efficiently',
                'difficulty_level' => 'intermediate',
                'order_number' => 3,
                'content' => 'The top row contains the keys QWERTY UIOP. Practice reaching up from the home row to these keys while maintaining proper finger positioning. Each finger is responsible for specific keys.',
                'estimated_completion_time' => 20,
                'experience_reward' => 100,
            ],
        ];

        foreach ($lessons as $lesson) {
            TypingLesson::create($lesson);
        }
    }
    
    private function seedCompetitions(): void
    {
        $competitions = [
            [
                'title' => 'Daily Speed Challenge',
                'description' => 'Test your typing speed in this daily challenge',
                'start_time' => now()->addHours(1),
                'end_time' => now()->addHours(2),
                'status' => 'upcoming',
                'device_type' => 'both',
                'text_id' => 1,
            ],
            [
                'title' => 'Mobile Typing Contest',
                'description' => 'Compete with other mobile users',
                'start_time' => now()->subHours(1),
                'end_time' => now()->addHours(1),
                'status' => 'active',
                'device_type' => 'mobile',
                'text_id' => 2,
            ],
            [
                'title' => 'Programming Challenge',
                'description' => 'Type programming code as fast as you can',
                'start_time' => now()->addDays(1),
                'end_time' => now()->addDays(1)->addHours(1),
                'status' => 'upcoming',
                'device_type' => 'pc',
                'text_id' => 3,
            ],
        ];

        foreach ($competitions as $competition) {
            Competition::create($competition);
        }
    }
}