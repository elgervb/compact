<?php
namespace compact\io\writer;

use compact\io\reader\StreamReader;
use compact\io\AbstractStreamAccesser;

/**
 * Write content to a stream
 */
class StreamWriter extends AbstractStreamAccesser
{

    /**
     * The number of bytes written
     *
     * @var int
     */
    private $bytesWritten;

    /**
     * Creates a new StreamWriter
     *
     * @param
     *            string aStreamUrl eg. php://output, file path, etc
     * @param string $aOpenMode
     *            default = 'ab' (append, binary)
     */
    public function __construct($aStreamUrl, $aOpenMode = 'ab')
    {
        parent::__construct($aStreamUrl, $aOpenMode);
        
        $this->bytesWritten = 0;
    }

    /**
     * Creates a new reader based on this write (eg.
     * uses the same underlying stream)
     */
    public function createReader()
    {
        $reader = new StreamReader($this->getStreamUrl());
        $reader->setFileHandle($this->getHandle());
        
        return $reader;
    }

    /**
     * Return the number of bytes written
     *
     * @return int
     */
    public function getBytesWritten()
    {
        return $this->bytesWritten;
    }

    /**
     * Opens a stream to the file in binary write mode
     *
     * @throws FilesystemException when opening the file was not possible
     */
    public function open()
    {
        parent::open();
    }

    /**
     * Clears all text from the file.
     * This results in an empty file.
     * File will be locked (exclusive) during this operation
     */
    public function truncate()
    {
        assert('$this->isOpened()');
        
        $this->lockWrite();
        ftruncate($this->getHandle(), 0);
        $this->lockRelease();
        $this->seek(0, SEEK_SET);
    }

    /**
     * Writes a string to a file, file will be locked (exclusive) during this operation
     *
     * @param string $aString            
     * @param int $aLength[optional]            
     *
     * @return int the number of bytes written, false when write failed
     */
    public function write($aString, $aLength = null)
    {
        assert('$this->isOpened()');
        
        $this->lockWrite();
        if ($aLength === null) {
            $result = fwrite($this->getHandle(), $aString);
        } else {
            $result = fwrite($this->getHandle(), $aString, $aLength);
        }
        $this->lockRelease();
        
        if ($result !== false) {
            $this->bytesWritten += $result;
        }
        return $result;
    }

    /**
     * Writes a line to the stream
     *
     * @param String $line            
     *
     * @return int false when write failed
     */
    public function writeLine($aLine)
    {
        $line = $aLine . "\n";
        
        return $this->write($line);
    }
}
