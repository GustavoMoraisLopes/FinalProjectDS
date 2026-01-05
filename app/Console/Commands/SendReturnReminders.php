<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Notifications\ReturnReminderNotification;
use Carbon\Carbon;

class SendReturnReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:return';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send return reminders for reservations nearing their end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        
        // Find approved reservations ending today or tomorrow
        $reservations = Reservation::where('status', 'approved')
            ->whereDate('end_date', '<=', $tomorrow)
            ->whereDate('end_date', '>=', $today)
            ->with('user', 'equipment')
            ->get();
        
        $count = 0;
        
        foreach ($reservations as $reservation) {
            $endDate = Carbon::parse($reservation->end_date);
            $daysLeft = $today->diffInDays($endDate, false);
            
            // Only send if we haven't already sent a notification for this reservation today
            $alreadyNotified = $reservation->user->notifications()
                ->where('type', 'App\Notifications\ReturnReminderNotification')
                ->where('data->reservation_id', $reservation->id)
                ->whereDate('created_at', $today)
                ->exists();
            
            if (!$alreadyNotified) {
                $reservation->user->notify(new ReturnReminderNotification($reservation, max(0, $daysLeft)));
                $count++;
            }
        }
        
        $this->info("Sent {$count} return reminder(s).");
        
        return Command::SUCCESS;
    }
}
