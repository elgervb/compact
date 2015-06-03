<?php
namespace compact\rss;

use compact\rss\XmlNode;
/**
 * Rss class
 *
 * @see http://cyber.law.harvard.edu/rss/rss.html
 *
 * @author elger
 */
class Rss
{
	private $xmlVersion;
	private $rssVersion;
	
	/**
	 * @var RssChannel
	 */
	private $channel;

	/**
	 * Creates a new TRss feed
	 *
	 * @param string $aRssVersion
	 * @param string $aXmlVersion
	 */
	public function __construct( $aRssVersion = "2.0", $aXmlVersion = "1.0" )
	{
		$this->rssVersion = $aRssVersion;
		$this->xmlVersion = $aXmlVersion;
		
		$this->channel = new RssChannel();
	}

	/**
	 * Returns the RssChannel
	 *
	 * @return RssChannel
	 */
	public function getChannel()
	{
		return $this->channel;
	}

	/**
	 * Returns the xml represention of the RSS feed in xml
	 *
	 * @throws ValidationException when the channel of one of the items are not correct
	 */
	public function toXml()
	{
		$rssNode = new XmlNode( "rss", true );
		$rssNode->setXmlVersion( $this->xmlVersion );
		$rssNode->attr( "version", $this->rssVersion );
		
		$channel = $this->getChannel();
		
		$rssNode->add( $this->channelToXml( $channel ) );
		
		return $rssNode->toString();
	}

	/**
	 * Converts the channel (and all it's items) to XML
	 *
	 * @param RssChannel $aChannel
	 *
	 * @return string the channel xml
	 */
	private function channelToXml( RssChannel $aChannel )
	{
		$channelXml = new XmlNode( "channel", true );
		foreach ($this->channel->getFieldNames() as $fieldName)
		{
			$value = $aChannel->get( $fieldName );
			
			if ($value !== null)
			{
				$child = new XmlNode( $fieldName, true );
				$child->setText( $value );
				$channelXml->add( $child );
			}
		}
		
		foreach ($aChannel->getItems() as $item)
		{
			$channelXml->add( $this->itemToXml( $item ) );
		}
		
		return $channelXml;
	}

	/**
	 * Converts the item to XML
	 *
	 * @param RssItem $aItem
	 *
	 * @return string the item xml
	 */
	private function itemToXml( RssItem $aItem )
	{
		$itemXml = new XmlNode( "item", true );
		foreach ($aItem->getFieldNames() as $fieldname)
		{
			$value = $aItem->get( $fieldname );
			
			if ($value !== null)
			{
				$child = new XmlNode( $fieldname, true );
				$child->setText( $value );
				$itemXml->add( $child );
			}
		}
		
		return $itemXml;
	}
}