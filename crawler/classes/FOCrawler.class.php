<?php

namespace FinanceOne;

use Goutte\Client;

class FOCrawler{
    
    public      $titleSelector;
    public      $contentSelector;
    public      $url;
    public      $content;
    protected   $client;
    protected   $crawler;

    public function __construct(){
        $this->client = new Client();
    }

    public function titleSelector($titleSelector){
        $this->titleSelector = $titleSelector;
        return $this;
    }

    public function contentSelector($contentSelector){
        $this->contentSelector = $contentSelector;
        return $this;
    }

    public function url($url){
        $this->url = $url;
        return $this;
    }

    public function getContent(){
        $this->crawler = $this->client->request('GET', $this->url);
        return $this;
    }

    public function scanDocument(){
        
        $this->crawler->filter($this->titleSelector)->each(function ($node) {
            
            $this->content['title'] = $node->text();

        });


        $this->crawler->filter($this->contentSelector)->each(function ($node) {

            if(!$this->jsExists($node->html())){
                $this->content['content'][] = $node->html();
            }          

        });

        return $this;

    }

    protected function jsExists($html){
        return preg_match("/<script>/", $html);
    }
}