<?php

use Gilbitron\Util\SimpleCache;
use PHPieces\Framework\App;
use PHPieces\Framework\Util\File;
use PHPieces\Framework\Util\Http;

class AppTest extends \PHPieces\Framework\TestCase
{
    public function setUp()
    {
        parent::setUp();

        $config = [
            'template_dir' => __DIR__.'/../../src/templates'
        ];

        $app = new App($config);

        $app->container->share(SimpleCache::class, SimpleCache::class)
            ->withArgument(__DIR__ . '/../cache/')
            ->withArgument(File::class)
            ->withArgument(Http::class);

        $app->get('/', 'Gilbitron\Controllers\RandomAttendeeController::home');

        $app->get('/meetup-random-attendee', 'Gilbitron\Controllers\RandomAttendeeController::show');

        $app->post('/meetup-random-attendee', 'Gilbitron\Controllers\RandomAttendeeController::spin');

        $this->app = $app;
    }

    /**
     * @test
     */
    public function it_gets_home_page()
    {
        $client = $this->getClient($this->app);

        $crawler = $client->request('GET', '/');

        $this->assertEquals('Meetup.com Random Attendee Selector', $crawler->filter('a[href="/meetup-random-attendee"]')->text());
    }

    /**
     * @test
     */
    public function it_gets_select_page()
    {
        $client = $this->getClient($this->app);

        $crawler = $client->request('GET', '/meetup-random-attendee');

        $this->assertEquals('Go Spin That Wheel', $crawler->filter('button[id="go"]')->text());
    }
}