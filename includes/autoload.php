<?php

use RobertWP\WPFormsEntriesPro\Utils\RWLogger;

spl_autoload_register(function ($class) {
    $prefix = 'RobertWP\\WPFormsEntriesPro\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative_class = substr($class, strlen($prefix));

    $parts = explode('\\', $relative_class);

    $class_name = array_pop($parts);
    $sub_path = count($parts) ? implode('/', array_map('strtolower', $parts)) . '/' : '';

    $file = __DIR__ . '/' . $sub_path . $class_name . '.php';

    if (file_exists($file)) {
        require $file;
    } else {
         RWLogger::log("Autoload error: File not found for class $class => $file");
    }
});


