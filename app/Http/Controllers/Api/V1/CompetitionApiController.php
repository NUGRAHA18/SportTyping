<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\Competition;
use App\Services\CompetitionService;
use App\Services\WPMCalculationService;
use Illuminate\Http\Request;

class CompetitionApiController extends BaseApiController
{
    public function __construct(
        private CompetitionService $competitionService,
        private WPMCalculationService $wpmService
    ) {}

    public function getParticipants(Competition $competition)
    {
        try {
            $participants = $this->competitionService->getCompetitionParticipants($competition);
            
            return $this->successResponse($participants, 'Participants retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve participants', 500);
        }
    }

    public function updateProgress(Competition $competition, Request $request)
    {
        try {
            $validated = $request->validate([
                'progress' => 'required|integer|min:0|max:100',
                'wpm' => 'required|numeric|min:0',
                'accuracy' => 'required|numeric|min:0|max:100',
                'typed_text' => 'nullable|string',
            ]);

            $result = $this->competitionService->updateCompetitionProgress(
                $competition, 
                auth()->user(), 
                $validated
            );

            return $this->successResponse($result, 'Progress updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update progress', 500);
        }
    }

    public function getRealTimeStats(Competition $competition, Request $request)
    {
        try {
            $validated = $request->validate([
                'typed_text' => 'required|string',
                'elapsed_seconds' => 'required|integer|min:0'
            ]);

            $stats = $this->wpmService->calculateRealTimeWPM(
                $competition->text->content,
                $validated['typed_text'],
                $validated['elapsed_seconds']
            );

            return $this->successResponse($stats, 'Stats calculated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to calculate stats', 500);
        }
    }
}