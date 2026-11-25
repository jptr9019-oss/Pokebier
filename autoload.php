<?php
spl_autoload_register(function ($class_name) {
    $arquivo = __DIR__ . '/src/Models/' . $class_name . '.php';

    if (file_exists($arquivo)) {
        require_once $arquivo;
    }
});
?>