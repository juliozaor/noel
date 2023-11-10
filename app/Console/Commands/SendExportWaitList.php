<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\ExportController;
use Illuminate\Console\Command;

class SendExportWaitList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-export-wait-list';

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
        $exportController->sendEmailWaitList();
        $this->info('Data export process completed.');
    }
}
