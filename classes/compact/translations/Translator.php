<?php
namespace compact\translations;

use compact\translations\bundle\ITranslationBundle;
use compact\translations\bundle\impl\MemoryTranslationBundle;
use compact\logging\Logger;
class Translator
{
	const DEFAULT_LANGUAGE = 'en';
	private static $instance;
	private $language;
	
	/**
	 *
	 * @var \ArrayObject
	 */
	private $bundles;
	
	/**
	 * Creates a new Translator
	 *
	 * @var string $aLanguage
	 */
	public function __construct( $aLanguage = 'en' )
	{
		if (self::$instance !== null)
		{
			throw new \Exception( $this );
		}
		
		self::$instance = $this;
		$this->setLanguage( $aLanguage );
		
		$this->bundles = new \ArrayObject();
		
		// add system default
		$bundleName = "Translations_".strtoupper($aLanguage);
		$bundle = __DIR__ . '/bundle/impl/'.$bundleName.'.php';
		if (is_file($bundle)){
			$bundleClass = 'compact\\translations\\bundle\\impl\\'.$bundleName;
			$this->addBundle(new $bundleClass());
		}
	}
	
	/**
	 * Returns the single instance of the translator
	 *
	 * @return Translator
	 */
	public static function get()
	{
		if (self::$instance == null)
		{
			self::$instance = new Translator();
		}
		
		return self::$instance;
	}
	
	/**
	 * Add a bundle to the translator
	 *
	 * @param $aLanguage string
	 * @param $aBundle \SplFileInfo
	 */
	public function addBundle( ITranslationBundle $aBundle )
	{
		if ($this->language === $aBundle->getLanguage())
		{
			$this->bundles->append( new MemoryTranslationBundle( $aBundle ) );
		}
	}
	
	/**
	 *
	 * @return Iterator with ITranslationBundle
	 */
	public function getBundles()
	{
		return new ReverseIterator( $this->bundles->getIterator() );
	}
	
	/**
	 *
	 * @return string
	 */
	public function getLanguage()
	{
		return $this->language;
	}
	
	/**
	 * Sets the language for the translator, this also results in resetting the bundle cache
	 *
	 * @param $language string
	 *
	 * @return Translator
	 */
	public function setLanguage( $aLanguage )
	{
		$this->language = strtolower( $aLanguage );
		
		return $this;
	}
	
	/**
	 * Translate a string
	 *
	 * @param $aString string
	 * @param $aArgs string... varargs of strings
	 *       
	 * @return String the translated string
	 */
	public static function translate( $aKey, $aArgs = null )
	{
		$result = null;
		$translator = Translator::get();
		
		$bundles = $translator->getBundles();
		
		/* @var $bundle ITranslationBundle */
		foreach ($bundles as $bundle)
		{
			$translations = $bundle->getTranslations();
			if (isset( $translations[$aKey] ))
			{
				$result = $translations[$aKey];
				break;
			}
		}
		
		if ($result === null)
		{
			Logger::get()->logWarning( 'Translator: String ' . $aKey . ' was not found by the translator for language ' . $translator->getLanguage() );
			
			// Set the key as result to avoid runtime exceptions
			$result = $aKey;
		}
		
		$args = null;
		if ($aArgs !== null)
		{
			$args = func_get_args();
			array_shift( $args );
		}
		return $translator->replaceArgs( $result, $args );
	}
	
	public function replaceArgs( $aString, $aArgs )
	{
		$result = $aString;
		$match = preg_match_all( "/\{[a-zA-Z0-9]+\}/i", $aString, $matches );
		if ($match)
		{
			$result = str_replace( $matches[0], $aArgs, $result );
		}
		
		return $result;
	}
}
