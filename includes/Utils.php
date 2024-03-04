<?php
class Utils{
    public static function extractVideoId($str){
        
    }

    public static function arrayGet($array, $key, $default = null){
        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                $array = $default;
                break;
            }
        }
        return $array;
    }
}