<?php

namespace Gilbitron;

use Gilbitron\Util\SimpleCache;
use Gilbitron\Controllers\RandomAttendeeController;
use Gilbitron\AttendeePicker;
use \League\Plates\Engine;

class App
{

    public function bootstrap()
    {
        global $templates, $cache;
        
        $templates = new Engine(__DIR__.'/templates');
        
        $cache = new SimpleCache(__DIR__.'/../cache/'); 
        
    }
    
    public function run()
    {
        global $templates;
        
        if(strpos($_SERVER['REQUEST_URI'], 'meetup-random-attendee')) {
            $picker = new AttendeePicker();
            $controller = new RandomAttendeeController();
            $controller->index($picker);
            return;
        }
        
       echo $templates->render('home', [ 'hostsOK' => true ]);
    }
}