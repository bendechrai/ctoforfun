<?php

namespace Gilbitron\Controllers;

use Gilbitron\AttendeePicker;
use Gilbitron\Util\SimpleCache;
use League\Plates\Engine;
use PHPieces\Framework\Util\File;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RandomAttendeeController {

    public function __construct(File $file, SimpleCache $cache, Engine $templates, AttendeePicker $picker)
    {
        $this->file = $file;
        $this->cache = $cache;
        $this->templates = $templates;
        $this->picker = $picker;
    }

    public function home(ServerRequestInterface $request, ResponseInterface $response){
        
        $response->getBody()->write($this->templates->render('home'));
        
        return $response;
    }

    public function show(ServerRequestInterface $request, ResponseInterface $response) {

        $hostsOK = $this->picker->hostsOK($this->file->get('/etc/hosts'));

        $response->getBody()->write($this->templates->render('meetup-random-attendee', [ 'hostsOK' => $hostsOK ]));
        
        return $response;
    }

    public function spin(ServerRequestInterface $request, ResponseInterface $response) {

        $hostsOK = $this->picker->hostsOK($this->file->get('/etc/hosts'));

        $url = $this->picker->validateUrl($request->getParsedBody()['url']);

        $html = $this->cache->findOrFetch($url, $url);

        $name = $this->picker->getRandomName($html);
        
        $response->getBody()->write($this->templates->render('meetup-random-attendee', [ 'hostsOK' => $hostsOK, 'name' => $name, 'url' => $url ]));
                
        return $response;
    }

}
