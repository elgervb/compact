<?php
namespace compact\rss;

use compact\rss\XmlAttributeParser;

/**
 * @author elgervb
 */
class XmlNode
{
	const OPEN_NODE = "<";
	const CLOSE_NODE = ">";
	const SPACE = " ";
	const DASH = "/";
	
	/**
	 * @var string
	 */
	private $tag;
	/**
	 * @var ArrayObject
	 */
	private $attributes;
	
	/**
	 * @var String
	 */
	private $text;
	/**
	 * The xml version info, leave empty when not needed to print
	 * @var string
	 */
	private $xmlVersion;
	
	private $disableShortNotation;
	
	/**
	 * @var ArrayObject
	 */
	private $childs;
	
	/**
	 * Creates a XmlNode with a specific tag
	 * 
	 * @param string $aTag The tag name
	 * @param boolean $aDisableShortNotation Disable rendering as short notation, tag will always have a end tag
	 */
	public function __construct( $aTag, $aDisableShortNotation = false )
	{
		assert( '! empty( $aTag )' );
		
		$this->tag = $aTag;
		$this->setDisableShortNotation( $aDisableShortNotation );
		$this->attributes = new \ArrayObject();
		$this->childs = new \ArrayObject();
	}
	
	/**
	 * Automagical method to clone a TModel
	 */
	public function __clone()
	{
		foreach ($this as $key => $val)
		{
			if (is_object( $val ) || (is_array( $val )))
			{
				$this->{$key} = unserialize( serialize( $val ) );
			}
		}
	}
	
	/**
	 * Automagical method to render this node
	 */
	public function __toString()
	{
		return $this->toString();
	}
	
	/**
	 * Adds a child to the current node
	 * 
	 * @param XmlNode $aElement
	 * 
	 * @return XmlNode this node
	 */
	public function add( XmlNode $aElement )
	{
		return $this->addChild( $aElement );
	}
	
	/**
	 * Adds a child to the current node
	 * 
	 * @param XmlNode $aElement
	 * 
	 * @return XmlNode this node
	 * 
	 * @deprecated use XmlNode::add()
	 */
	public function addChild( XmlNode $aElement )
	{
		assert( '$this !== $aElement' );
		$this->childs->append( $aElement );
		
		return $this;
	}
	/**
	 * Adds an attribute
	 * 
	 * @param string $aName
	 * @param string $aValue = null optional
	 * 
	 * @return XmlNode This XML node for method chaining
	 */
	public function attr( $aName, $aValue = null )
	{
		if ($aValue !== null)
		{
			$this->setAttribute( $aName, $aValue );
		}
		
		return $this;
	}
	
	/**
	 * Returns a new node based on this one
	 * 
	 * @return XmlNode
	 */
	public function cloneNode()
	{
		return clone $this;
	}
	
	/**
	 * Creates and returns new child node
	 * 
	 * @param string $aTag
	 * 
	 * @return XmlNode
	 */
	public function create( $aTag )
	{
		$node = new XmlNode( $aTag );
		$this->add( $node );
		
		return $node;
	}
	
	/**
	 * Override in child classes to do something just before rendering the node
	 */
	protected function doBeforeRender()
	{
		//
	}
	
	/**
	 * Returns the close node
	 * 
	 * @return String
	 */
	private function getCloseNode()
	{
		$result = "";
		
		if ($this->useShortNotation())
		{
			$result = self::SPACE . self::DASH . self::CLOSE_NODE;
		}
		else
		{
			$result = self::CLOSE_NODE . $this->text;
			
			if ($this->hasChilds())
			{
				$result .= $this->renderChilds();
			}
			
			$result .= self::OPEN_NODE . self::DASH . $this->tag . self::CLOSE_NODE;
		}
		
		return $result;
	}
	
	/**
	 * Returns the open node
	 * 
	 * @returns String the open node
	 */
	private function getOpenNode()
	{
		return self::OPEN_NODE . $this->tag;
	}
	
	public function getAttribute( $aAttr )
	{
		return $this->attributes->offsetGet( $aAttr );
	}
	
	/**
	 * Gets all childs by tagname
	 * @param string $aTagName
	 * 
	 * @return Iterator
	 */
	public function getChildsByTagname( $aTagName )
	{
		$result = array();
		
		/* @var $child XmlNode */
		foreach ($this->childs as $child)
		{
			if ($child->getTag() === $aTagName)
			{
				$result[] = $child;
			}
		}
		
		return new \ArrayIterator( $result );
	}
	
	/**
	 * Returns the tagname of the node
	 * 
	 * @return string
	 */
	public function getTag()
	{
		return $this->tag;
	}
	
	/**
	 * Checks if this node had an attribute
	 * 
	 * @param string $aName
	 * 
	 * @return boolean
	 */
	public function hasAttribute( $aName )
	{
		return $this->attributes->offsetExists( $aName );
	}
	
	/**
	 * Checks whether or not this node has childs
	 * 
	 * @return boolean
	 */
	public function hasChilds()
	{
		return $this->childs->count() > 0;
	}
	
	/**
	 * Checks if this node has text
	 * 
	 * @return boolean
	 */
	public function hasText()
	{
		return ! empty( $this->text );
	}
	
	/**
	 * Returns the string representation of this node
	 * 
	 * @return String
	 */
	public function render()
	{
		$result = "";
		$this->doBeforeRender();
		
		// print version info
		if ($this->xmlVersion !== null)
		{
			$result .= '<?xml version="' . $this->xmlVersion . '"?>'.PHP_EOL;
		}
		
		$result .= self::OPEN_NODE . $this->tag;
		
		// parse attributes
		if ($this->attributes->count() > 0)
		{
			$parser = new XmlAttributeParser();
			$result .= self::SPACE . $parser->parse( $this->attributes );
		}
		
		$result .= $this->getCloseNode();
		
		return $result;
	}
	
	/**
	 * Renders all child nodes
	 * 
	 * @return string
	 */
	private function renderChilds()
	{
		$result = "";
		if ($this->hasChilds())
		{
			/* @var $child XmlNode */
			foreach ($this->childs as $child)
			{
				$result .= $child->toString();
			}
		}
		
		return $result;
	}
	
	/**
	 * Adds an attribute
	 * 
	 * @param string $aName
	 * @param string $aValue
	 * 
	 * @return XmlNode This XML node for method chaining
	 * 
	 * @deprecated use XmlNode::attr()
	 */
	public function setAttribute( $aName, $aValue )
	{
		if (preg_match( "/\"/", $aValue ))
		{
			$aValue = preg_replace( "/\"/", "'", $aValue );
		}
		
		$this->attributes->offsetSet( $aName, $aValue );
		
		return $this;
	}
	
	/**
	 * Disable rendering as short notation, tag will always have a end tag
	 * 
	 * &lt;input /&gt; will be rendered as &lt;input&gt;&lt;/input&gt;
	 * 
	 * @param boolean $aDisableShortNotation
	 * 
	 * @return XmlNode $this
	 */
	public function setDisableShortNotation( $aDisableShortNotation )
	{
		$this->disableShortNotation = $aDisableShortNotation;
		
		return $this;
	}
	
	/**
	 * Sets the text for this node
	 * 
	 * @param string $aText
	 */
	public function setText( $aText )
	{
		$this->text = $aText;
		
		return $this;
	}
	
	/**
	 * Sets the xml version for the node, the node must be a root node..
	 * 
	 * @param string $aXmlVersion
	 */
	public function setXmlVersion( $aXmlVersion )
	{
		$this->xmlVersion = $aXmlVersion;
	}
	
	/**
	 * @see XmlNode::render()
	 */
	public function toString()
	{
		return $this->render();
	}
	
	/**
	 * Checks whether or not we can use a short node notation when printing this node
	 * 
	 * @return boolean
	 */
	private function useShortNotation()
	{
		$result = true;
		
		if ($this->disableShortNotation || $this->hasText() || $this->hasChilds())
		{
			$result = false;
		}
		
		return $result;
	}
}
