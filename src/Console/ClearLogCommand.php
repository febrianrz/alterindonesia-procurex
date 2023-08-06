<?php
namespace Alterindonesia\Procurex\Console;

use Alterindonesia\Procurex\Facades\GlobalHelper;
use Illuminate\Console\Command;

class ClearLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all logs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        GlobalHelper::clearUserLogs();
        return Command::SUCCESS;
    }
}
