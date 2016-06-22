<?php

use Gilbitron\App;

require __DIR__ .'/../vendor/autoload.php';

$app = new App();

$app->bootstrap();

$app->run();
