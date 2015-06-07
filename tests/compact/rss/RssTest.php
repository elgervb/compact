<?php
namespace compact\rss;

class RssTest extends \PHPUnit_Framework_TestCase
{
    public function testRssChannelXML(){
        $rss = new Rss();
        $channel = $rss->getChannel();
        
        $channel->set(RssChannel::DESCRIPTION, "Rss Description");
        $channel->set(RssChannel::LINK, 'http://google.com');
        $channel->Set(RssChannel::TITLE, "Rss Title");

        $xml = $rss->toXml();
        $this->assertEquals( file_get_contents(__DIR__.'/RssTest-testRssChannelXML.xml'), $xml );
    }
    
    public function testRssChannelWithItemsXML(){
        $rss = new Rss();
        $channel = $rss->getChannel();
    
        $channel->set(RssChannel::DESCRIPTION, "Rss Description");
        $channel->set(RssChannel::LINK, 'http://google.com');
        $channel->Set(RssChannel::TITLE, "Rss Title");
        
        $item = $channel->newItem();
        $item->set(RssItem::AUTHOR, 'Elger van Boxtel');
        $item->set(RssItem::DESCRIPTION, "Homepage Elger van Boxtel");
        $item->set(RssItem::LINK, 'http://www.elgervanboxtel.nl');
        
        $item = $channel->newItem();
        $item->set(RssItem::AUTHOR, 'Elger van Boxtel');
        $item->set(RssItem::DESCRIPTION, "Startpage Elger van Boxtel");
        $item->set(RssItem::LINK, 'http://www.elgervanboxtel.nl/labs/start');
            
        $xml = $rss->toXml();
        $this->assertEquals( file_get_contents(__DIR__.'/RssTest-testRssChannelWithItemsXML.xml'), $xml );
    }
}