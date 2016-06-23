<?php

namespace Gilbitron;

use Gilbitron\Util\File;
use Gilbitron\Util\Http;
use Gilbitron\Util\SimpleCache;
use Gilbitron\Controllers\RandomAttendeeController;
use Gilbitron\AttendeePicker;
use \League\Plates\Engine;

class App
{
    public $container;

    public function __construct()
    {
        $this->container = new \League\Container\Container;

        $this->container->delegate(
            new \League\Container\ReflectionContainer
        );
    }

    public function bootstrap()
    {
        global $templates, $cache;

        $this->container->share(SimpleCache::class, SimpleCache::class)
            ->withArgument(__DIR__.'/../cache/')
            ->withArgument(File::class)
            ->withArgument(Http::class);

        $templates = new Engine(__DIR__.'/templates');
    }
    
    public function run()
    {
        global $templates;
        
        if(strpos($_SERVER['REQUEST_URI'], 'meetup-random-attendee')) {
            $picker = new AttendeePicker();
            $controller = $this->container->get(RandomAttendeeController::class);
            $controller->index($picker);
            return;
        }
        
       echo $templates->render('home');
    }
}