<?php
namespace App\Console\Commands;

use App\Models\Competition;
use App\Models\TypingText;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateCompetition extends Command
{
    protected $signature = 'competition:create 
                            {--title= : Competition title}
                            {--description= : Competition description}
                            {--start=now : Start time (format: Y-m-d H:i:s)}
                            {--duration=10 : Duration in minutes}
                            {--device=both : Device type (mobile, pc, or both)}
                            {--text= : ID of the typing text to use (randomly selected if not provided)}
                            {--difficulty= : Difficulty level for random text}';
                            
    protected $description = 'Create a new typing competition';

    public function handle()
    {
        $title = $this->option('title') ?? 'Typing Competition #' . random_int(1000, 9999);
        $description = $this->option('description') ?? 'Show off your typing skills in this competition!';
        
        // Parse start time
        $startTime = $this->option('start') === 'now' 
            ? Carbon::now() 
            : Carbon::parse($this->option('start'));
            
        if (!$startTime) {
            $this->error('Invalid start time format. Use Y-m-d H:i:s');
            return Command::FAILURE;
        }
        
        // Calculate end time
        $duration = (int) $this->option('duration');
        $endTime = (clone $startTime)->addMinutes($duration);
        
        // Validate device type
        $deviceType = $this->option('device');
        if (!in_array($deviceType, ['mobile', 'pc', 'both'])) {
            $this->error('Invalid device type. Use mobile, pc, or both');
            return Command::FAILURE;
        }
        
        // Get typing text
        $textId = $this->option('text');
        if (!$textId) {
            $query = TypingText::query();
            
            // Filter by difficulty if provided
            if ($difficulty = $this->option('difficulty')) {
                $query->where('difficulty_level', $difficulty);
            }
            
            $text = $query->inRandomOrder()->first();
            
            if (!$text) {
                $this->error('No suitable typing texts found');
                return Command::FAILURE;
            }
            
            $textId = $text->id;
        } else {
            // Verify text exists
            if (!TypingText::find($textId)) {
                $this->error('Typing text not found');
                return Command::FAILURE;
            }
        }
        
        // Create competition
        $competition = Competition::create([
            'title' => $title,
            'description' => $description,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => $startTime->isFuture() ? 'upcoming' : 'active',
            'device_type' => $deviceType,
            'text_id' => $textId,
        ]);
        
        $this->info("Competition created successfully!");
        $this->table(
            ['ID', 'Title', 'Start Time', 'End Time', 'Status', 'Device Type'],
            [[
                $competition->id,
                $competition->title,
                $competition->start_time->format('Y-m-d H:i:s'),
                $competition->end_time->format('Y-m-d H:i:s'),
                $competition->status,
                $competition->device_type,
            ]]
        );
        
        return Command::SUCCESS;
    }
}