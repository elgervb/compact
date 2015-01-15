<?php
namespace compact\io\writer;

use compact\io\reader\StreamReader;
use compact\io\writer\StreamWriter;

/**
 * Write content to a file stream and supply convenience methods for file streams
 */
class FileWriter extends StreamWriter
{

    /**
     * Streamreader used to delete lines
     *
     * @var StreamReader
     */
    private $reader;

    /**
     * Creates a new FileWriter
     *
     * @param
     *            string aStreamUrl The file path
     * @param string $aOpenMode
     *            default = 'ab' (append, binary)
     */
    public function __construct($aStreamUrl, $aOpenMode = 'ab')
    {
        parent::__construct($aStreamUrl, $aOpenMode);
    }

    /**
     * Delete a line at a specific line number
     *
     * @param int $aLineNr            
     */
    public function deleteLine($aLineNr)
    {
        assert('$this->isOpened()');
        assert('is_int($aLineNr)');
        
        // create reader & writer
        if ($this->reader === null) {
            $this->reader = new StreamReader($this->getStreamUrl());
            $this->reader->autoDetectLineEndings(true);
        }
        $this->reader->open();
        
        $tmpFileName = tempnam(sys_get_temp_dir(), 'mvc');
        $tmpWriter = new StreamWriter($tmpFileName);
        $tmpWriter->open();
        
        // read all lines except for the one which should not be read
        $line = 1;
        while (! $this->reader->eof()) {
            if ($line == $aLineNr) {
                $this->reader->readLine();
                $line ++;
                continue;
            }
            $con = $this->reader->readLine();
            $tmpWriter->writeLine($con);
            $line ++;
        }
        $this->reader->close();
        
        clearstatcache();
        $size = filesize($tmpFileName);
        
        // write all lines from the tmp filepointer to the current one
        $tmpReader = new StreamReader($tmpFileName);
        $tmpReader->open();
        
        $this->truncate();
        
        while (! $tmpReader->eof()) {
            $con = $tmpReader->readLine();
            if ($this->tell() != $size - 1) {
                $this->writeLine($con);
            }
        }
        
        $tmpReader->close();
        $tmpWriter->close();
        
        unlink($tmpFileName);
    }
}