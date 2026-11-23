<?php

session_start();

$_SESSION = [];

session_destroy();

header("Location: ../../views/inicio_pokebier.php");
exit;