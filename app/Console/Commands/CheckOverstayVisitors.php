<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GateLog;
use Illuminate\Support\Facades\Log;

class CheckOverstayVisitors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gate:check-overstay {--hours=4 : Threshold in hours before flagging overstay}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flag and alert security guards for visitors remaining inside society premises beyond threshold hours.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $thresholdHours = (int) $this->option('hours');
        $cutoffTime = now()->subHours($thresholdHours);

        $overstayLogs = GateLog::with(['visitor', 'flat'])
            ->whereNull('exit_time')
            ->where('entry_time', '<=', $cutoffTime)
            ->get();

        $count = $overstayLogs->count();

        if ($count > 0) {
            $this->warn("Found {$count} visitor(s) overstaying inside premises (>= {$thresholdHours} hours).");
            foreach ($overstayLogs as $log) {
                $visitorName = $log->visitor->visitor_name ?? 'Guest';
                $flatStr = $log->flat ? "Flat {$log->flat->block_name}-{$log->flat->flat_number}" : 'Unknown Flat';
                $this->line(" - Log #{$log->id}: {$visitorName} at {$flatStr} (Entered: {$log->entry_time})");
            }
        } else {
            $this->info("No overstaying visitors detected.");
        }

        return Command::SUCCESS;
    }
}
