<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteExpiredRegistrations extends Command
{
    protected $signature = 'registrations:delete-expired';

    protected $description = 'Delete expired course registrations from users table.';

    public function handle(): int
    {
        $deleted = User::where('payment_status', 'expired')->delete();

        $this->info("Deleted {$deleted} expired registrations.");

        return self::SUCCESS;
    }
}
