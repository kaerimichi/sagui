<?php
require '../vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

use Infrastructure\App\Sagui;

try {
    $app = new Sagui();
    $app->bootstrap();
    $app->run();
} catch (\Exception $e) {
    var_dump($e->getMessage());
    var_dump($e->getTrace());
}
