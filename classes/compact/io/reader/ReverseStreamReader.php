<?php
namespace compact\io\reader;

/**
 * Read a stream in reverse
 */
class ReverseStreamReader extends StreamReader
{

    /**
     *
     * @param int $aLineNr            
     */
    protected function gotoLine($aLineNr)
    {
        assert('$this->isOpened()');
        assert('$aLineNr > 0');
        
        if ($aLineNr < 1) {
            $this->rewind();
            return;
        }
        
        $linecounter = $aLineNr;
        
        $beginning = false;
        $pos = - 2;
        while ($linecounter > 0) {
            $t = "";
            while ($t != "\n") {
                if ($this->seek($pos, SEEK_END) == - 1) {
                    $beginning = true;
                    break;
                }
                $t = $this->getChar();
                $pos --;
            }
            $linecounter --;
            
            if ($beginning)
                parent::rewind();
            if ($beginning)
                break;
        }
    }

    /**
     * Opens a stream to the file in binary read mode
     *
     * @throws Exception when open file was not possible
     */
    public function open($aOpenMode = 'rb')
    {
        parent::open($aOpenMode);
        $this->rewind();
    }

    /**
     * Reads a $aLength bytes from the socket connection
     *
     * @param int $aLength            
     * @return String
     */
    public function read($aLength)
    {
        $curPos = $this->tell();
        $newPos = $curPos - $aLength;
        $this->seek(($newPos < 0) ? 0 : - $aLength, SEEK_CUR);
        
        $content = parent::read($aLength);
        
        $this->seek(($newPos < 0) ? 0 : - $this->getBytesRead(), SEEK_CUR);
        
        return $content;
    }

    /**
     * Reads a line from the file
     *
     * @return String
     */
    public function readLine()
    {
        // first find the previous line, which should be read ...
        $beginning = false;
        $pos = $this->tell() - 1;
        $t = "";
        while ($t != "\n") {
            if ($this->seek($pos, SEEK_SET) == - 1) {
                $beginning = true;
                break;
            }
            $t = $this->getChar();
            $pos --;
        }
        
        if ($beginning)
            parent::rewind();
        if ($beginning)
            break;
        
        $content = parent::readLine();
        $this->seek(- ($this->getBytesRead() + 1), SEEK_CUR);
        
        return rtrim($content, "\r\n");
    }

    /**
     * Rewinds the position to the last line
     */
    public function rewind()
    {
        $this->seek(0, SEEK_END);
    }
}