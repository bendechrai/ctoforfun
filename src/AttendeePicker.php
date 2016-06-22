<?php

namespace Gilbitron;

use Jclyons52\PHPQuery\Document;

class AttendeePicker
{
    // Check the server this is running on isn't intercepting or diverting calls to Meetup.com
    public function hostsOK($hosts){
         if (strpos($hosts, 'meetup.com') !== FALSE) {
            return false;
        }
        return true;
    }
    
    public function validateUrl($url) {
        if (!isset($url)) {
           throw new \Exception('url is not valid');
        }
        // Ensure URL has a trailing slash
        $url = trim($url, '/') . '/';
        
        $this->groupName = $this->getGroupName($url);
        
        return $url;
    }
    
    public function getRandomName($html)
    {
        $names = $this->getNames($html);

        $selected = array_rand($names);

        // Extract the value -- as this was ripped from HTML, it is already HTML encoded :)
        $name = $names[$selected];
        
        return $name;
    }
    
    private function getGroupName($url)
    {
        // Validate URL format, so this tool isn't used to hit random sites, and capture the Meetup group name while we're at it
        preg_match('#^https?://www.meetup.com/([^\/]+)/events/[0-9]+/$#', $url, $results);
        
        if(!$results) {
            throw new \Exception('could not get group name');
        }
                
        return $results[1];       
    }
    
    private function getNames($html)
    {
        $dom = new Document($html);
        
        $names = $dom->querySelectorAll('.unlink')->text();
            
        // Remove any instance of "Members" from the names array
        foreach (array_keys($names, 'Members') as $key) {
            unset($names[$key]);
        }

        // Remove duplicates (i.e. attending convenors)
        return array_unique($names);
    }
}
