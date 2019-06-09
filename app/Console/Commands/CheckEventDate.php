<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Repositories\EventRepository;
class CheckEventDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */ 
    protected $signature = 'check:eventDate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
    public function handle(EventRepository $eventRepository)
    {
        $now = time();
        $events = $eventRepository->getsWith([],['status.in'=>[0, 1]]);
        foreach ($events as $key => $event) {
            if($event->status==0 && strtotime($event->started_at) <= $now && strtotime($event->ended_at.'+1 day') > $now){
                $event->update(['status'=>1]);
            }
            if($event->status==1 && strtotime($event->ended_at.'+1 day') <= $now){
                $event->update(['status'=>2]);
            }
        }
    }
}
