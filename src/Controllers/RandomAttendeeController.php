<?php

namespace Gilbitron\Controllers;

use Gilbitron\AttendeePicker;
use Gilbitron\Util\File;
use Gilbitron\Util\SimpleCache;

class RandomAttendeeController {

    public function __construct(File $file, SimpleCache $cache)
    {
        $this->file = $file;
        $this->cache = $cache;
    }

    public function index(AttendeePicker $picker)
    {
        global $templates;

        $hostsOK = $picker->hostsOK($this->file->get('/etc/hosts'));

        if(!array_key_exists('url', $_GET)){

            echo $templates->render('meetup-random-attendee', [ 'hostsOK' => $hostsOK ]);
            return;
        }
        
        $url = $picker->validateUrl($_GET['url']);

        $html = $this->cache->findOrFetch($url, $url);

        $name = $picker->getRandomName($html);
        
        echo $templates->render('meetup-random-attendee', [ 'hostsOK' => $hostsOK, 'name' => $name, 'url' => $url ]);
        
    }
    

}
