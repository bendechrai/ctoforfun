<?php

namespace Gilbitron\Controllers;

use Gilbitron\AttendeePicker;
use Gilbitron\Util\File;

class RandomAttendeeController {
    
    public function index(AttendeePicker $picker) {
        global $templates, $cache;
        
        $hostsOK = $picker->hostsOK(File::get('/etc/hosts'));
        
        if(!array_key_exists('url', $_GET)){
            echo $templates->render('meetup-random-attendee', [ 'hostsOK' => $hostsOK ]);
            return;
        }
        
        $url = $picker->validateUrl($_GET['url']);

        $html = $cache->findOrFetch($url, $url);

        $name = $picker->getRandomName($html);
        
        echo $templates->render('meetup-random-attendee', [ 'hostsOK' => $hostsOK, 'name' => $name, 'url' => $url ]);
        
    }
    

}
