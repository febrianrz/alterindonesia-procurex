<?php
namespace Alterindonesia\Procurex\Console;

use Illuminate\Console\Command;

class CreateTaskNotifikasiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create task dan notifikasi via rabbitmq';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Command::SUCCESS;
    }
}
