<?php


namespace BannerOn;


abstract class Logger
{
    
    // Just for testing
    public static function write_log($log) {
        
        if (true !== WP_DEBUG) return;
        
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
        
    }


}