<?php

namespace Gilbitron;

class AttendeePickerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *@test
     */
    public function it_returns_false_if_hosts_file_contains_meetup_url()
    {
        $picker = new AttendeePicker();
        
        $hosts = "192.168.10.10  joomla.local
                  192.168.10.10  meetup.com
                  192.168.10.10  bitcoin.local";
        
        $result = $picker->hostsOK($hosts);
        
        $this->assertEquals(false, $result);
        
    }
    
    /**
     * @test
     */
    public function it_returns_true_if_hosts_file_does_not_contain_meetup_url()
    {
        $picker = new AttendeePicker();
        
        $hosts = "192.168.10.10  joomla.local
                  192.168.10.10  owncloud
                  192.168.10.10  bitcoin.local";
        
        $result = $picker->hostsOK($hosts);
        
        $this->assertEquals(true, $result);
    }
    
    /**
     * @test
     */
    public function it_throws_error_if_url_is_not_valid()
    {
        $picker = new AttendeePicker();
        
        $this->setExpectedException(\Exception::class);
        
        $picker->validateUrl(null);
    }
    
    /**
     * @test
     */
    public function it_adds_trailing_slash_to_url()
    {
        $picker = new AttendeePicker();
        
        $urlWithSlash = 'http://www.meetup.com/Melbourne-PHP-Users-Group/events/224111131/';
                
        $urlWithoutSlash = 'http://www.meetup.com/Melbourne-PHP-Users-Group/events/224111131';
        
        $result = $picker->validateUrl($urlWithoutSlash);
        
        $this->assertEquals($urlWithSlash, $result);
    }
    
    /**
     * @test
     */
    public function it_throws_exception_if_url_is_not_for_meetup_event()
    {
        $picker = new AttendeePicker();
        
        $this->setExpectedException(\Exception::class);
        
        $picker->validateUrl('http://randomsite.com');
    }
    
    /**
     * @test
     */
    public function it_gets_random_name_from_list_of_attendees()
    {
        $names = ["Ben D.", "Adam P.", "Dave", "Lusy", "Andrew M.", "Stafford A.", "Amud", "Charlie C.", "Adeel Ahmad C.", "Alan H.", "Chitto", "Gustavo Sgarbi C.", "Ali H.", "Alex G.", "Tim C.", "Cristian Camilo G.", "tim", "Jasio", "Tom C.", "Altynbek U.", "Damien", "Richard", "Patrick O.", "Dirgh B.", "David H.", "Greg J.", "Marco", "Daniel L.", "David K.", "Ghulam M.", "Owen", "Anuruddha K.", "Varun B.", "luis miguel daravina h.", "Kevin B.", "Dipali", "AJ", "OBEE", "Sir", "Andrew E.", "Tim M.", "Joseph L.", "Hammy G.", "Andrew H.", "Ralph W.", "Lu Y.", "Csongor H."];
    
        $html = file_get_contents(__DIR__."/../data/response.html");
        
        $picker = new AttendeePicker();
        
        $name = $picker->getRandomName($html);
        
        $this->assertTrue(in_array($name, $names));
        
        
    }
}
