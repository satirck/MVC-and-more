<?php

spl_autoload_register(static function ($class_name) {
    $global_path = sprintf('%s.php', str_replace('\\', DIRECTORY_SEPARATOR, $class_name));
    $global_path = sprintf('%s', str_replace('App', 'src', $global_path));

    if (file_exists($global_path)) {
        require_once $global_path;
    }
});