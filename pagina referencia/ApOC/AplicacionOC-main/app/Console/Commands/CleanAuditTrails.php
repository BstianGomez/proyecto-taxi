<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanAuditTrails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:clean {--days=90 : Days to keep records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old audit trail records based on retention policy';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = $this->option('days');

        // Registros no críticos: mantener 90 días
        // Registros críticos: mantener 365 días

        $deletedNormal = DB::table('audit_trails')
            ->where('severity', '!=', 'critical')
            ->where('timestamp', '<', now()->subDays($days))
            ->delete();

        $deletedCritical = DB::table('audit_trails')
            ->where('severity', '=', 'critical')
            ->where('timestamp', '<', now()->subDays(365))
            ->delete();

        $this->info("Deleted $deletedNormal normal audit records older than $days days");
        $this->info("Deleted $deletedCritical critical audit records older than 365 days");

        return self::SUCCESS;
    }
}
