<?php
namespace compact\hash\decoder;

use compact\hash\HashFactory;

class Md5Decoder
{
	const DEFAULT_DEPTH = 6;

	private $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890`~!@#$%^&*()_+-=[]\;\,./{}|:"<>? ';
	private $countChars;
	private $depth;
	private $algorythm;
	private $useEcho;
	private $echoInterval;
	private $currentInterval = 0;
	
	
	/**
	 *
	 * @param $aDepth int
	 * @param $aAlgorythm string
	 *
	 * @see http://php.net/hash_algos for available algorithms
	 */
	public function __construct( $aAlgorythm = "md5", $aDepth = 6,  $aUseEcho = false, $aEchoInterval = 1 )
	{
		$this->algorythm = $aAlgorythm;
		$this->depth = $aDepth;
		$this->countChars = strlen($this->chars);
		$this->useEcho = $aUseEcho;
		$this->echoInterval = $aEchoInterval;
	}
	
	private function check( $aHash, $aString )
	{
		$this->currentInterval++;
		if ($this->currentInterval % $this->echoInterval == 0){
			$this->log( "Checking " . $aString);
		}
		$encoder = HashFactory::createHash($this->algorythm);
		if ($encoder->verify($aString, $aHash))
		{
			$this->log( "FOUND MATCH, password: " . $aString . "\n");
			return true;
		}
		
		return false;
	}
	
	private function log($aString){
		if ($this->useEcho){
			echo $aString . "\n";
		}
	}
	
	private function recurse( $aHash, $aWidth, $aPosition, $aBaseString )
	{
		for ($i = 0; $i < $this->countChars; ++ $i)
		{
			$result = $aBaseString . $this->chars[$i];
			if ($aPosition < $aWidth - 1)
			{
				$result = $this->recurse($aHash, $aWidth, $aPosition+1, $result );
				if ($result){
					return $result;
				}
			}
			if ($this->check($aHash, $result )){
				return $result;
			}
		}
		
		return null;
	}
	
	public function run($aHash){
		$this->log( "Target hash: " . $aHash);
		for ($i = 1; $i < $this->depth +1; ++ $i)
		{
			$this->log( "Checking passwords with length:" . $i);
			$result = $this->recurse($aHash, $i, 0, '' );
			if ($result !== null)
			{
				return $result;
			}
		}
		
		$this->log( "Execution complete, no password found");
		return null;
	}
}