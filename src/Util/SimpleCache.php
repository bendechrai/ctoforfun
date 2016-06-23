<?php

namespace Gilbitron\Util;

/*
 * SimpleCache v1.4.1
 *
 * By Gilbert Pellegrom
 * http://dev7studios.com
 *
 * Free to use and abuse under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */

class SimpleCache {

    // Path to cache folder (with trailing /)
    public $cache_path;
    
    // Length of time to cache a file (in seconds)
    public $cache_time = 3600;
    // Cache file extension
    public $cache_extension = '.cache';
    
    public function __construct($cache_path, File $file, Http $http) {
        $this->cache_path = $cache_path;
        $this->file = $file;
        $this->http = $http;
    }

    public function set($label, $data) {
        $this->file->put($this->cache_path . $this->safe_filename($label) . $this->cache_extension, $data);
    }

    public function get($label) {
        if ($this->is_cached($label)) {
            $filename = $this->cache_path . $this->safe_filename($label) . $this->cache_extension;
            return $this->file->get($filename);
        }

        return false;
    }
    
    public function findOrFetch($url) {
        if ($data = $this->get($url)) {
            return $data;
        }
            $data = $this->http->get($url);
            $this->set($url, $data);
            return $data;
    }

    public function is_cached($label) {
        $filename = $this->cache_path . $this->safe_filename($label) . $this->cache_extension;

        if ($this->file->exists($filename) && ($this->file->time($filename) + $this->cache_time >= time()))
            return true;

        return false;
    }

    //Helper function to validate filenames
    private function safe_filename($filename) {
        return preg_replace('/[^0-9a-z\.\_\-]/i', '', strtolower($filename));
    }

}
