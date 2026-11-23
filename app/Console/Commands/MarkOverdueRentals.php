<?php

namespace App\Console\Commands;

use App\Models\Rental;
use Illuminate\Console\Command;

class MarkOverdueRentals extends Command
{
    protected $signature = 'rentals:mark-overdue {--dry-run : Preview without updating}';

    protected $description = 'Mark active rentals past due date as terlambat. Only affects rentals in "dipinjam" status.';

    public function handle(): int
    {
        $query = Rental::query()
            ->whereNull('returned_at')
            ->where('due_date', '<', now()->toDateString())
            ->where('status', 'dipinjam');

        $count = $query->count();

        if ($this->option('dry-run')) {
            $this->info("Would mark {$count} rental(s) overdue.");
            return self::SUCCESS;
        }

        $updated = $query->update(['status' => 'terlambat']);

        $this->info("Marked {$updated} rental(s) as terlambat.");

        return self::SUCCESS;
    }
}