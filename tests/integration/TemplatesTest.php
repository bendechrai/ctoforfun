<?php

namespace Gilbitron;

use League\Plates\Engine;

class TemplatesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_renders_the_home_page()
    {
        global $templates;

        (new App())->bootstrap();

        $html = $templates->render('home');

        $this->assertContains('Meetup.com Random Attendee Selector', $html);
    }
    
    /**
     * @test
     */
    public function it_renders_select_attendee_page()
    {
        global $templates;

        (new App())->bootstrap();

        $html = $templates->render('meetup-random-attendee');

        $this->assertContains('Enter the full URL to the main page of your Meetup.com event', $html);
    }
}