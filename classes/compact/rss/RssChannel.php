<?php
namespace compact\rss;

class RssChannel
{
	/**
	 * The name of the channel. It's how people refer to your service. If you have an HTML website that contains the same information as your RSS file, the title of your channel should be the same as the title of your website.
	 *
	 * @var string
	 */
	const TITLE = "title";
	/**
	 * The URL to the HTML website corresponding to the channel.
	 *
	 * @var string
	 */
	const LINK = "link";
	/**
	 * Phrase or sentence describing the channel.
	 *
	 * @var string
	 */
	const DESCRIPTION = "description";
	
	/**
	 * The language the channel is written in. This allows aggregators to group all Italian language sites, for example, on a single page. A list of allowable values for this element, as provided by Netscape, is here. You may also use values defined by the W3C.
	 *
	 * @var string
	 *
	 * @see http://www.w3.org/TR/REC-html40/struct/dirlang.html#langcodes
	 */
	const LANGUAGE = "language";
	/**
	 * Copyright notice for content in the channel.
	 *
	 * @var string
	 */
	const COPYRIGHT = "copyright";
	/**
	 * Email address for person responsible for editorial content.
	 *
	 * @var string
	 */
	const MANAGING_EDITOR = "managingEditor";
	/**
	 * Email address for person responsible for technical issues relating to channel.
	 *
	 * @var string
	 */
	const WEBMASTER = "webMaster";
	/**
	 * The publication date for the content in the channel. For example, the New York Times publishes on a daily basis, the publication date flips once every 24 hours. That's when the pubDate of the channel changes. All date-times in RSS conform to the Date and Time Specification of RFC 822, with the exception that the year may be expressed with two characters or four characters (four preferred).
	 *
	 * @var string
	 *
	 * @see http://asg.web.cmu.edu/rfc/rfc822.html
	 */
	const PUBLICATION_DATE = "pubDate";
	/**
	 * The last time the content of the channel changed. (in RFC 822)
	 *
	 * @var string
	 *
	 * @see http://asg.web.cmu.edu/rfc/rfc822.html
	 */
	const LAST_BUILD_DATE = "lastBuildDate";
	/**
	 * Specify one or more categories that the channel belongs to. Follows the same rules as the <item>-level category element.
	 *
	 * @var string
	 */
	const CATEGORY = "category";
	/**
	 * A string indicating the program used to generate the channel.
	 *
	 * @var string
	 */
	const GENERATOR = "generator";
	/**
	 * A URL that points to the documentation for the format used in the RSS file. It's probably a pointer to this page. It's for people who might stumble across an RSS file on a Web server 25 years from now and wonder what it is.
	 *
	 * @var string
	 */
	const DOCS = "docs";
	/**
	 * Allows processes to register with a cloud to be notified of updates to the channel, implementing a lightweight publish-subscribe protocol for RSS feeds.
	 *
	 * @var string
	 *
	 * @see http://cyber.law.harvard.edu/rss/rss.html#ltcloudgtSubelementOfLtchannelgt
	 */
	const CLOUD = "cloud";
	/**
	 * ttl stands for time to live. It's a number of minutes that indicates how long a channel can be cached before refreshing from the source.
	 *
	 * @var string
	 *
	 * @see http://cyber.law.harvard.edu/rss/rss.html#ltttlgtSubelementOfLtchannelgt
	 */
	const TTL = "ttl";
	/**
	 * Specifies a GIF, JPEG or PNG image that can be displayed with the channel.
	 *
	 * @var string
	 *
	 * @see http://cyber.law.harvard.edu/rss/rss.html#ltttlgtSubelementOfLtchannelgt
	 */
	const IMAGE = "image";
	/**
	 * The PICS rating for the channel.
	 *
	 * @var string
	 *
	 * @see http://www.w3.org/PICS/
	 */
	const RATING = "rating";
	/**
	 * Specifies a text input box that can be displayed with the channel.
	 *
	 * @var string
	 *
	 * @see http://cyber.law.harvard.edu/rss/rss.html#lttextinputgtSubelementOfLtchannelgt
	 */
	const TEXT_INPUT = "textInput";
	/**
	 * A hint for aggregators telling them which hours they can skip.
	 *
	 * @var string
	 *
	 * @see http://cyber.law.harvard.edu/rss/skipHoursDays.html#skiphours
	 */
	const SKIP_HOURS = "skipHours";
	/**
	 * A hint for aggregators telling them which days they can skip.
	 *
	 * @var string
	 *
	 * @see http://cyber.law.harvard.edu/rss/skipHoursDays.html#skipdays
	 */
	const SKIP_DAYS = "skipDays";
	
	/**
	 * @var ArrayObject with RssItems
	 */
	private $items;

	/**
	 * Creates a new TRssChannelModel
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->items = new \ArrayObject();
	}

	/**
	 * Returns an iterator with item objects
	 *
	 * @return Iterator of TRssItem
	 */
	public function getItems()
	{
		return $this->items->getIterator();
	}
	
	public function get($aField){
		if (isset($this->$aField ))
			return $this->$aField;
		return null;
	}
	
	public function set($aField, $aValue){
		$this->$aField = $aValue;
	}

	/**
	 * Creates and adds a new Rss Item object
	 *
	 * @return TRssItem The newly created rss item object
	 */
	public function newItem()
	{
		$item = new RssItem();
		$this->items->append( $item );
		
		return $item;
	}
	
	public function getFieldNames()
	{
		$ref = new \ReflectionClass($this);
		return $ref->getConstants();
	}
}