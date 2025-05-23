<?php
namespace App\Http\Controllers\Api\V1;

use App\Services\WPMCalculationService;
use Illuminate\Http\Request;

class PracticeApiController extends BaseApiController
{
    public function __construct(private WPMCalculationService $wmpService) {}

    public function calculateRealTimeStats(Request $request)
    {
        try {
            $validated = $request->validate([
                'original_text' => 'required|string|max:10000',
                'typed_text' => 'required|string|max:10000',
                'elapsed_seconds' => 'required|integer|min:0|max:3600'
            ]);

            $stats = $this->wmpService->calculateRealTimeWMP(
                $validated['original_text'],
                $validated['typed_text'],
                $validated['elapsed_seconds']
            );

            return $this->successResponse($stats, 'Stats calculated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to calculate stats', 500);
        }
    }

    public function calculateGuestWMP(Request $request)
    {
        try {
            $validated = $request->validate([
                'original_text' => 'required|string|max:5000',
                'typed_text' => 'required|string|max:5000',
                'elapsed_seconds' => 'required|integer|min:1|max:1800'
            ]);

            $stats = $this->wmpService->calculateRealTimeWMP(
                $validated['original_text'],
                $validated['typed_text'],
                $validated['elapsed_seconds']
            );

            return $this->successResponse($stats, 'Guest stats calculated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to calculate guest stats', 500);
        }
    }

    public function validateTyping(Request $request)
    {
        try {
            $validated = $request->validate([
                'original_text' => 'required|string',
                'typed_text' => 'required|string',
                'position' => 'required|integer|min:0'
            ]);

            $originalText = $validated['original_text'];
            $typedText = $validated['typed_text'];
            $position = $validated['position'];

            $isCorrect = $position < strlen($originalText) && 
                        $position < strlen($typedText) &&
                        $originalText[$position] === $typedText[$position];

            $result = [
                'is_correct' => $isCorrect,
                'expected_char' => $position < strlen($originalText) ? $originalText[$position] : null,
                'typed_char' => $position < strlen($typedText) ? $typedText[$position] : null,
                'position' => $position
            ];

            return $this->successResponse($result, 'Typing validated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to validate typing', 500);
        }
    }
}