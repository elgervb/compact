<?php

namespace compact\rss;

class XmlAttributeParser
{
	/**
	 * Parses the XML attributes
	 * @param ArrayObject $aAttributes
	 * 
	 * @return String
	 */
	public function parse( \ArrayObject $aAttributes )
	{
		$result = "";
		
		foreach ($aAttributes as $key => $value)
		{
			$result .= $key . "=\"" . $value . "\" ";
		}
		
		return trim( $result );
	}
}