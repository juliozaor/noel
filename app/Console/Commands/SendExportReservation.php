<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\ExportController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendExportReservation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-export-reservation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $exportController = new ExportController();
        $exportController->export();
        $this->info('Data export process completed.');

    }
}
