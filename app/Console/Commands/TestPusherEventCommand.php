<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestPusherEventCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pusher:test {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'pusher test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        event(new \App\Events\TestPusherEvent($this->argument('message')));
    }
}
