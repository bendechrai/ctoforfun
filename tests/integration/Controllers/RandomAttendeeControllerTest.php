<?php

namespace Gilbitron;

use Gilbitron\Util\File;
use Gilbitron\Util\SimpleCache;
use Mockery;

class RandomAttendeeControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_integrates_with_attendee_picker()
    {
        $_GET['url'] = 'http://www.meetup.com/Melbourne-PHP-Users-Group/events/224111131/';
        
        $names = ["Ben D.", "Adam P.", "Dave", "Lusy", "Andrew M.", "Stafford A.", "Amud", "Charlie C.", "Adeel Ahmad C.", "Alan H.", "Chitto", "Gustavo Sgarbi C.", "Ali H.", "Alex G.", "Tim C.", "Cristian Camilo G.", "tim", "Jasio", "Tom C.", "Altynbek U.", "Damien", "Richard", "Patrick O.", "Dirgh B.", "David H.", "Greg J.", "Marco", "Daniel L.", "David K.", "Ghulam M.", "Owen", "Anuruddha K.", "Varun B.", "luis miguel daravina h.", "Kevin B.", "Dipali", "AJ", "OBEE", "Sir", "Andrew E.", "Tim M.", "Joseph L.", "Hammy G.", "Andrew H.", "Ralph W.", "Lu Y.", "Csongor H."];

        $file = Mockery::mock(File::class);

        $file->shouldReceive('get')->once()->with('/etc/hosts')->andReturn('192.168.10.10  joomla.local');
       
        $cache = Mockery::mock(SimpleCache::class);
        
        $cache->shouldReceive('findOrFetch')->with($_GET['url'], $_GET['url'])->andReturn(file_get_contents(__DIR__.'/../../data/response.html'));
        
        

    }
}