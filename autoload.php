<?php
spl_autoload_register(function ($class_name) {
    require_once 'src/models/'.  $class_name . '.php';
});

?>