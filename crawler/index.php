<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('functions.php');

use FinanceOne\FOCrawler;
use Models\Posts;
use Models\Crawlers;
use Jenssegers\Date\Date;
use Cocur\Slugify\Slugify;

$crawlers = Crawlers::where('active',1)->get();
$timezone = new DateTimeZone('America/Sao_Paulo');

foreach ($crawlers as $key => $crawler) {
    Feed::$cacheDir =  dirname(__FILE__).'/tmp';
    $rss = Feed::loadRss($crawler->url);

    $now = Date::now();

    $rssInformation = array();

    foreach ($rss->item as $item) {
        $date =  (int) $item->timestamp;
        $date =  (string) new Date($date,$timezone);
        $rssInformation[] = [ 'url' => toMobile($item->link,$crawler->mobileConverterRegex,$crawler->mobileConverterReplacement), 'date'=> $date ];
    }

    $wordPressContent = prepareWordPress($crawler,$rssInformation);

    foreach ($wordPressContent as $key => $wp) {
        $post = Posts::create($wp); 
    }
    
    print 'Finalizada com sucesso!! ;)';   
}

function prepareWordPress($crawler,$rssInformation) {
    $wordpressContent = [];

    foreach ($rssInformation as $key => $info) {
        $page = scanPage($crawler,$info)->content;
        
        if (isset($page['content'])) {
            $slug = (new Slugify())->slugify($page['title']);

            if (!postExists($slug)) {
                $wordpressContent[] =  [
                    'post_author'             => 1,
                    'post_date'               => $info['date'],
                    'post_date_gmt'           => $info['date'],
                    'post_content'            => renderPostContent($page['content'],$info['url'],$crawler->name),
                    'post_title'              => $page['title'],
                    'post_excerpt'            => '',
                    'post_status'             => 'publish',
                    'comment_status'          => '',
                    'ping_status'             => '',
                    'post_password'           => '',
                    'post_name'               => $slug,
                    'to_ping'                 => '',
                    'pinged'                  => '',
                    'post_modified'           => $info['date'],
                    'post_modified_gmt'       => $info['date'],
                    'post_content_filtered'   => '',
                    'post_type'               => 'post',
                ];
            }
        }
    }

    return $wordpressContent;
}

function postExists($slug) {
    $post = Posts::where('post_name',$slug)->first();
    
    if ($post) return true;
    
    return false;
}

function scanPage($crawler,$rssLink) {
     return (new FOCrawler())->url($rssLink['url'])
        ->titleSelector($crawler->titleSelector)
        ->contentSelector($crawler->contentSelector)
        ->getContent()
        ->scanDocument()
    ;
}

function renderPostContent($pageContent,$url,$siteName) {
    $renderedContent = '';

    foreach ($pageContent as $paragraph) {
        $renderedContent .= '<p>'.$paragraph.'</p>';
    }

    $renderedContent .= '<p><b>Fonte:</b> <a href="'.$url.'" rel="nofollow">'.$siteName.'</a></p>';
   
    return $renderedContent;
}

?>

