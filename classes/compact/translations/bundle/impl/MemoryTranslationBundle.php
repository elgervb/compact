<?php
namespace compact\translations\bundle\impl;

use compact\translations\bundle\ITranslationBundle;

class MemoryTranslationBundle implements ITranslationBundle
{
	private $bundle;
	private $translations;
	
	public function __construct( ITranslationBundle $aBundle )
	{
		$this->bundle = $aBundle;
	}
	/**
	 * (non-PHPdoc)
	 *
	 * @see compact\translations\bundle.ITranslationBundle::getLanguage()
	 */
	public function getLanguage()
	{
		return $this->bundle->getLanguage();
	}
	/**
	 * (non-PHPdoc)
	 *
	 * @see compact\translations\bundle.ITranslationBundle::getTranslations()
	 */
	public function getTranslations()
	{
		if ($this->translations === null)
		{
			$this->translations = $this->bundle->getTranslations();
		}
		
		return $this->translations;
	}
}