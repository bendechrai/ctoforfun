<?php

namespace Gilbitron\Util;

class File {

    public static function exists($filename) {
        return file_exists($filename);
    }

    public static function get($path) {
        return file_get_contents($path);
    }

    public static function put($path, $contents)
    {
       $result = file_put_contents($path, $contents);
       
       return $result;
    }
}
