<?php
namespace compact\io\reader;

use compact\io\AbstractStreamAccesser;

/**
 * StreamReader: used for reading a file in chunks
 *
 * @example $reader = new StreamReader( $filePath );
 *          $reader->open();
 *          while (! $reader->eof())
 *          {
 *          $line = $reader->readLine();
 *          }
 *          $reader->close();
 */
class StreamReader extends AbstractStreamAccesser
{

    /**
     * The number of bytes read by this reader
     *
     * @var int
     */
    private $bytesRead;

    /**
     * Creates a new StreamReader
     *
     * @param
     *            string aStreamUrl The path to the file
     * @param string $aOpenMode
     *            = 'rb'
     */
    public function __construct($aStreamUrl, $aOpenMode = 'rb')
    {
        parent::__construct($aStreamUrl, $aOpenMode);
        
        assert('is_readable($aStreamUrl)');
        
        $this->bytesRead = 0;
    }

    /**
     * Returns the number of bytes read from the file
     *
     * @return int
     */
    public function getBytesRead()
    {
        return $this->bytesRead;
    }

    /**
     * Reads a $aLength bytes from the socket connection, file will be locked (shared) during this operation
     *
     * @param int $aLength
     *            The number of bytes to read
     *            
     * @return string The content read
     */
    public function read($aLength)
    {
        $this->lockRead();
        $content = fread($this->getHandle(), $aLength);
        $this->lockRelease();
        $this->bytesRead += mb_strlen($content);
        
        return $content;
    }

    /**
     * Reads a line from the file, file will be locked (shared) during this operation
     *
     * @return String
     */
    public function readLine()
    {
        $this->lockRead();
        $content = fgets($this->getHandle());
        $this->lockRelease();
        $this->bytesRead += mb_strlen($content);
        
        return trim($content, "\r\n");
    }

    /**
     * Returns the line at $aLineNr
     *
     * @param int $aLineNr            
     *
     * @return String
     */
    public function readLineAt($aLineNr)
    {
        assert('is_int( $aLineNr )');
        
        $this->gotoLine($aLineNr);
        
        return $this->readLine();
    }
}