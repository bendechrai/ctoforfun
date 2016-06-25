<?php

use PHPieces\Framework\App;
use PHPieces\Framework\Util\File;
use PHPieces\Framework\Util\Http;
use Gilbitron\Util\SimpleCache;

require __DIR__ .'/../vendor/autoload.php';

$config = [
    'template_dir' => __DIR__.'/../src/templates'
];

$app = new App($config);

$app->container->share(SimpleCache::class, SimpleCache::class)
            ->withArgument(__DIR__ . '/../cache/')
            ->withArgument(File::class)
            ->withArgument(Http::class);

$app->get('/', 'Gilbitron\Controllers\RandomAttendeeController::home');

$app->get('/meetup-random-attendee', 'Gilbitron\Controllers\RandomAttendeeController::show');

$app->post('/meetup-random-attendee', 'Gilbitron\Controllers\RandomAttendeeController::spin');

$app->run();
