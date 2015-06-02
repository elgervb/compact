<?php
namespace compact\rss;

class RssItem
{
	/**
	 * The title of the item
	 *
	 * @var string
	 */
	const TITLE = "title";
	/**
	 * The URL of the item
	 *
	 * @var string
	 */
	const LINK = "link";
	/**
	 * The item synopsis
	 *
	 * @var string
	 */
	const DESCRIPTION = "description";
	
	/**
	 * Email address of the author of the item.
	 *
	 * @var string
	 */
	const AUTHOR = "author";
	/**
	 * Includes the item in one or more categories.
	 *
	 * @var string
	 */
	const CATEGORY = "category";
	/**
	 * URL of a page for comments relating to the item.
	 *
	 * @var string
	 */
	const COMMENTS = "comments";
	/**
	 * Describes a media object that is attached to the item.
	 *
	 * @var string
	 *
	 * @see http://cyber.law.harvard.edu/rss/rss.html#ltenclosuregtSubelementOfLtitemgt
	 */
	const ENCLOSURE = "enclosure";
	/**
	 * A string that uniquely identifies the item. You could use the (unique) url for this.
	 *
	 * @var string
	 */
	const GUID = "guid";
	/**
	 * Indicates when the item was published (RFC 822)
	 *
	 * @var string
	 */
	const PUBLICATION_DATE = "pubDate";
	/**
	 * The RSS channel that the item came from
	 *
	 * @var string
	 */
	const SOURCE = "source";

	public function get($aField){
		if (isset($this->$aField ))
			return $this->$aField;
		return null;
	}
	
	public function getFieldNames()
	{
		$ref = new \ReflectionClass($this);
		return $ref->getConstants();
	}
	
	public function set($aField, $aValue){
		$this->$aField = $aValue;
	}
	
	/**
	 * Set a field with CData
	 *
	 * @param string $aField
	 * @param string $aValue
	 */
	public function setWithCData( $aField, $aValue )
	{
		$this->set( $aField, "<![CDATA[" . $aValue . "]]>" );
	}
}