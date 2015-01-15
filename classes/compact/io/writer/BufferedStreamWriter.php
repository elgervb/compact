<?php
namespace compact\io\writer;

use compact\io\writer\StreamWriter;

/**
 * Write to a stream in a buffered way.
 * The buffer is placed in a temporary stream (php://temp)
 */
class BufferedStreamWriter extends StreamWriter
{

    private $buffer;

    private $bytesInBuffer = 0;

    /**
     * Buffered file writer
     *
     * @param
     *            $aFilePath
     * @param
     *            $aOpenMode
     */
    public function __construct($aStreamUrl, $aOpenMode = 'ab')
    {
        parent::__construct($aStreamUrl, $aOpenMode);
        
        $this->createTempBuffer();
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\io\AbstractStreamAccesser::__destruct()
     */
    public function __destruct()
    {
        if ($this->bytesInBuffer > 0) {
            if (! $this->isOpened()) {
                $this->open();
            }
            
            $this->close();
        }
    }

    /**
     * Creates a new temporary memory buffer
     */
    private function createTempBuffer()
    {
        $this->buffer = fopen("php://temp", "w+");
        assert('$this->buffer && is_resource($this->buffer)');
    }

    /**
     * Flush the buffer and close the writer
     *
     * @see FileAccesser::close()
     */
    public function close()
    {
        $this->flush();
        
        if ($this->isOpened()) {
            parent::close();
        }
    }

    /**
     * Flushes the buffer
     */
    public function flush()
    {
        if ($this->bytesInBuffer > 0) {
            fflush($this->buffer);
            rewind($this->buffer);
            
            if (! $this->isOpened()) {
                $this->open();
            }
            
            parent::write(fread($this->buffer, $this->bytesInBuffer));
            
            ftruncate($this->buffer, 0);
            
            $this->bytesInBuffer = 0;
            
            parent::close();
        }
    }

    /**
     *
     * @see FileWriter::write()
     *
     * @param string $aString            
     * @param int $aLength            
     *
     * @return int the number of bytes written, false when write failed
     */
    public function write($aString, $aLength = null)
    {
        return $this->writeBuffer($aString, $aLength);
    }

    /**
     * Write to the temporary memory stream
     *
     * @param unknown_type $aString            
     * @param unknown_type $aLength            
     * @return number
     */
    private function writeBuffer($aString, $aLength = null)
    {
        if ($aLength === null) {
            $writtenBytes = fwrite($this->buffer, $aString);
            $this->bytesInBuffer += strlen($aString);
            assert('$writtenBytes === strlen( $aString )');
        } else {
            $writtenBytes = fwrite($this->buffer, substr($aString, 0, $aLength));
            $this->bytesInBuffer += $aLength;
            assert('$writtenBytes === $aLength');
        }
        
        return $writtenBytes;
    }
}
