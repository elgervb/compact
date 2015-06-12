<?php
namespace compact\io;

/**
 * Base class for file or url accessing operations (reading and writing)
 *
 * Package for file or url accessing operations (both read & write).
 * Any of the stream wrappers natively supported by PHP of registered through the stream_wrapper_register() function are supported. 0
 * For a list of native supported stream wrappers see: http://www.php.net/manual/en/wrappers.php
 *
 * The package supplies the following classes:
 * - Reading:
 * - StreamReader
 * - writing:
 * - StreamWriter
 * - BufferedStreamWriter
 * - FileWriter
 *
 * @see http://nl.php.net/manual/en/wrappers.php
 */
abstract class AbstractStreamAccesser
{

    /**
     * Open for reading only; place the file pointer at the beginning of the file.
     */
    const READ = 'r';

    /**
     * Open in binary mode
     */
    const BINARY = 'b';

    /**
     * Open for writing only; place the file pointer at the beginning of the file and truncate the file to zero length.
     * If the file does not exist, attempt to create it.
     */
    const WRITE = 'w';

    /**
     * Open for reading and writing; place the file pointer at the beginning of the file.
     */
    const READWRITE = 'r+';

    /**
     * Open for reading and writing; place the file pointer at the beginning of the file and truncate the file to zero length.
     * If the file does not exist, attempt to create it.
     */
    const READWRITECREATE = 'w+';

    /**
     * Open for writing only; place the file pointer at the end of the file.
     * If the file does not exist, attempt to create it.
     */
    const APPEND = 'a';

    /**
     * Append binary
     *
     * @var string
     *
     * @see AbstractStreamAccesser::APPEND
     */
    const APPEND_BINARY = 'ab';

    /**
     * Open for reading and writing; place the file pointer at the end of the file.
     * If the file does not exist, attempt to create it.
     */
    const READWRITEAPPEND = 'a+';

    /**
     * Open for writing only; place the file pointer at the beginning of the file and truncate the file to zero length.
     * If the file does not exist, attempt to create it.
     */
    const WRITEBINARY = 'wb';

    /**
     * The handle
     *
     * @var resource
     */
    private $handle;

    /**
     * The file or url to access
     *
     * @var string
     */
    private $streamUrl;

    /**
     * The mode in which the file was opened (r, w, a, etc)
     *
     * @var string
     */
    private $openMode;

    /**
     * Creates a new AbstractStreamAccesser
     *
     * @param
     *            aStreamUrl string
     * @param
     *            aStreamUrle string a read or write mode (r, w, a, etc)
     */
    public function __construct($aStreamUrl, $aMode)
    {
        $this->handle = null;
        $this->streamUrl = $aStreamUrl;
        $this->openMode = $aMode;
    }

    /**
     * Destructs the AbstractStreamAccesser.
     * If the AbstractStreamAccesser has not been closed properly,
     * this method will do that automatically
     */
    public function __destruct()
    {
        if (is_resource($this->handle)) {
            $this->close();
        }
    }

    /**
     * Closes the connected AbstractStreamAccesser
     */
    public function close()
    {
        assert('$this->isOpened() /* Calling close on a non-opened stream... */');
        
        fclose($this->handle);
    }

    /**
     * Checks for a end of file
     *
     * @return boolean
     */
    public function eof()
    {
        assert('$this->isOpened()');
        
        return feof($this->handle);
    }

    /**
     * Returns the current char from the filepointer
     *
     * @return string the character or false on end of file
     */
    protected function getChar()
    {
        return fgetc($this->getHandle());
    }

    /**
     * Returns the path to the file
     *
     * @return TPath
     */
    protected function getStreamUrl()
    {
        return $this->streamUrl;
    }

    /**
     * Returns the handle to the file
     *
     * @return resource
     */
    protected function getHandle()
    {
        return $this->handle;
    }

    /**
     * Go to a particular line in the file
     *
     * @param $aLineNr int            
     */
    protected function gotoLine($aLineNr)
    {
        assert('$this->isOpened()');
        
        if ($aLineNr <= 1) {
            $this->rewind($this->handle);
            return;
        }
        
        $linecounter = 1;
        $pos = 0;
        while (true) {
            $c = "";
            while ($c != "\n") {
                if (fseek($this->handle, $pos, SEEK_SET) == - 1) {
                    break;
                }
                $c = fgetc($this->handle);
                $pos ++;
            }
            $linecounter ++;
            
            if ($linecounter === $aLineNr) {
                break;
            }
        }
    }

    /**
     * This method places the file pointer at the beginning of the line
     */
    protected function gotoLineBegin()
    {
        $i = $this->tell();
        do {
            $s = $this->seek(-- $i, SEEK_SET);
            $c = $this->getChar();
        } while ($s !== - 1 && $c !== "\n");
    }

    /**
     * Checks if the file is opened
     *
     * @return boolean
     */
    public function isOpened()
    {
        return is_resource($this->handle);
    }

    /**
     * Obtain a read (shared lock) on the file
     */
    public function lockRead()
    {
        flock($this->handle, LOCK_SH);
    }

    /**
     * Release file lock
     */
    public function lockRelease()
    {
        flock($this->handle, LOCK_UN);
    }

    /**
     * Obtain a write (exclusive lock) on the file
     */
    public function lockWrite()
    {
        flock($this->handle, LOCK_EX);
    }

    /**
     * Opens a stream to the file in binary read mode
     *
     * @param $aOpenMode String
     *            = 'rb'
     *            
     * @throws FilesystemException when opening the file was not possible
     */
    public function open()
    {
        $this->handle = fopen($this->streamUrl, $this->openMode);
        
        if (! $this->handle || ! $this->isOpened()) {
            throw new \Exception('Could not open ' . $this->streamUrl);
        }
    }

    /**
     * Rewinds the position to the first line
     */
    public function rewind()
    {
        assert('$this->isOpened()');
        rewind($this->handle);
    }

    /**
     * Sets the file position indicator for the file
     *
     * Options for $aHow:
     * SEEK_SET - Set position equal to offset bytes.
     * SEEK_CUR - Set position to current location plus offset.
     * SEEK_END - Set position to end-of-file plus offset.
     *
     * @param $aOffset int            
     * @param $aHow int = SEEK_CUR Other options SEEK_SET, SEEK_END
     *            
     * @return Upon success, returns 0; otherwise, returns -1.
     */
    protected function seek($aOffset, $aHow = SEEK_CUR)
    {
        assert('is_int($aHow)');
        assert('$aHow === SEEK_CUR || $aHow === SEEK_SET || $aHow === SEEK_END');
        
        return fseek($this->getHandle(), $aOffset, $aHow);
    }

    /**
     * Sets the file handle for this stream accesser
     *
     * @param $aResource resource            
     */
    protected function setFileHandle($aResource)
    {
        assert('is_resource( $aResource )');
        assert('$this->handle === null');
        
        $this->handle = $aResource;
    }

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int
     */
    protected function tell()
    {
        return ftell($this->getHandle());
    }
}