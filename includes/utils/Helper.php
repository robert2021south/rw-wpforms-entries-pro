<?php
namespace RobertWP\WPFormsEntriesPro\Utils;

class Helper {

    public static function terminate(): void {
        if (defined('WP_ENV') && WP_ENV === 'testing') {
            throw new \Exception('terminate called');
        }
        exit;
    }

}


