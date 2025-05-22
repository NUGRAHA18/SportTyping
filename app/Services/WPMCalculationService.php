<?php

namespace App\Services;

class WPMCalculationService
{
    public function calculateTypingStats(string $originalText, string $typedText, int $timeInSeconds): array
    {
        $originalText = trim($originalText);
        $typedText = trim($typedText);
        
        // Calculate accuracy first
        $accuracyData = $this->calculateAccuracy($originalText, $typedText);
        
        // Calculate WPM based on correct characters
        $wpm = $this->calculateNetWPM($accuracyData['correct_chars'], $timeInSeconds);
        
        return [
            'wpm' => $wpm,
            'accuracy' => $accuracyData['accuracy'],
            'correct_chars' => $accuracyData['correct_chars'],
            'total_chars' => $accuracyData['total_chars'],
            'error_count' => $accuracyData['error_count']
        ];
    }

    private function calculateNetWPM(int $correctChars, int $timeInSeconds): int
    {
        if ($timeInSeconds <= 0) return 0;
        
        // Standard: 1 word = 5 characters
        $timeInMinutes = $timeInSeconds / 60;
        $wpm = ($correctChars / 5) / $timeInMinutes;
        
        return max(0, round($wpm));
    }
    
    private function calculateAccuracy(string $originalText, string $typedText): array
    {
        $originalLength = strlen($originalText);
        $typedLength = strlen($typedText);
        
        if ($typedLength === 0) {
            return [
                'accuracy' => 0.0,
                'correct_chars' => 0,
                'total_chars' => $originalLength,
                'error_count' => 0
            ];
        }
        
        $correctChars = 0;
        $errorCount = 0;
        $maxLength = max($originalLength, $typedLength);
        
        // Compare character by character
        for ($i = 0; $i < $maxLength; $i++) {
            $originalChar = $i < $originalLength ? $originalText[$i] : '';
            $typedChar = $i < $typedLength ? $typedText[$i] : '';
            
            if ($originalChar === $typedChar && $originalChar !== '') {
                $correctChars++;
            } elseif ($typedChar !== '') {
                $errorCount++;
            }
        }
        
        // Calculate accuracy as percentage of correct characters vs original text length
        $accuracy = $originalLength > 0 ? ($correctChars / $originalLength) * 100 : 0;
        
        return [
            'accuracy' => round($accuracy, 2),
            'correct_chars' => $correctChars,
            'total_chars' => $originalLength,
            'error_count' => $errorCount
        ];
    }
    
    public function calculateRealTimeWPM(string $originalText, string $typedText, int $elapsedSeconds): array
    {
        if ($elapsedSeconds < 1) {
            return ['wpm' => 0, 'accuracy' => 0];
        }
        
        $stats = $this->calculateTypingStats($originalText, $typedText, $elapsedSeconds);
        
        return [
            'wpm' => $stats['wpm'],
            'accuracy' => $stats['accuracy']
        ];
    }

    public function getSpeedCategory(int $wpm): string
    {
        return match(true) {
            $wpm >= 70 => 'expert',
            $wpm >= 50 => 'advanced', 
            $wpm >= 30 => 'intermediate',
            $wpm >= 15 => 'beginner',
            default => 'novice'
        };
    }
}