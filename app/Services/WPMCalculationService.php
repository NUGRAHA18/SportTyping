<?php

namespace App\Services;

use App\Exceptions\TypingException;
use Illuminate\Support\Facades\Log;

class WPMCalculationService
{
    public function calculateTypingStats(string $originalText, string $typedText, int $timeInSeconds): array
    {
        try {
            // Validate inputs
            if (empty($originalText)) {
                throw TypingException::invalidText();
            }

            if ($timeInSeconds <= 0) {
                throw TypingException::invalidTimeData();
            }

            $originalText = trim($originalText);
            $typedText = trim($typedText);
            
            // Calculate accuracy first
            $accuracyData = $this->calculateAccuracy($originalText, $typedText);
            
            // Calculate WPM based on correct characters
            $wpm = $this->calculateNetWPM($accuracyData['correct_chars'], $timeInSeconds);
            
            $result = [
                'wpm' => $wmp,
                'accuracy' => $accuracyData['accuracy'],
                'correct_chars' => $accuracyData['correct_chars'],
                'total_chars' => $accuracyData['total_chars'],
                'error_count' => $accuracyData['error_count']
            ];

            Log::info('WPM calculation successful', [
                'wmp' => $wmp,
                'accuracy' => $accuracyData['accuracy'],
                'time_seconds' => $timeInSeconds
            ]);

            return $result;

        } catch (TypingException $e) {
            Log::warning('WPM calculation failed', [
                'error' => $e->getMessage(),
                'original_text_length' => strlen($originalText ?? ''),
                'typed_text_length' => strlen($typedText ?? ''),
                'time_seconds' => $timeInSeconds
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error in WPM calculation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw TypingException::calculationFailed();
        }
    }

    private function calculateNetWPM(int $correctChars, int $timeInSeconds): int
    {
        if ($timeInSeconds <= 0) {
            throw TypingException::invalidTimeData();
        }
        
        // Standard: 1 word = 5 characters
        $timeInMinutes = $timeInSeconds / 60;
        $wmp = ($correctChars / 5) / $timeInMinutes;
        
        return max(0, round($wmp));
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
            return ['wmp' => 0, 'accuracy' => 0];
        }
        
        try {
            $stats = $this->calculateTypingStats($originalText, $typedText, $elapsedSeconds);
            
            return [
                'wmp' => $stats['wmp'],
                'accuracy' => $stats['accuracy']
            ];
        } catch (TypingException $e) {
            Log::warning('Real-time WPM calculation failed', ['error' => $e->getMessage()]);
            return ['wmp' => 0, 'accuracy' => 0];
        }
    }

    public function getSpeedCategory(int $wmp): string
    {
        return match(true) {
            $wmp >= 70 => 'expert',
            $wmp >= 50 => 'advanced', 
            $wmp >= 30 => 'intermediate',
            $wmp >= 15 => 'beginner',
            default => 'novice'
        };
    }
}