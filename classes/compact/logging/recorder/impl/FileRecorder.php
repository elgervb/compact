<?php
namespace compact\logging\recorder\impl;

use compact\logging\Logger;
use compact\io\AbstractStreamAccesser;
use compact\io\writer\FileWriter;
use compact\logging\decorator\IMessageDecorator;
use compact\logging\decorator\impl\PassthruDecorator;
use compact\logging\recorder\IRecorder;

class FileRecorder implements IRecorder
{

    /**
     *
     * @var SplFileInfo
     */
    private $logFile;

    /**
     *
     * @var FileWriter
     */
    private $writer;

    /**
     *
     * @var IMessageDecorator
     */
    private $decorator;

    /**
     *
     * @var int
     */
    private $threshold;

    /**
     * Creates a new FileRecorder
     *
     * @param $aLogFilePath FileInfo
     *            The logfile path
     * @param $aDecorator compact\logging\decorator\IMessageDecorator
     *            = null
     * @param $aThreshold int
     *            defaults to Logger::ALL
     */
    public function __construct(\SplFileInfo $aLogFilePath, IMessageDecorator $aDecorator = null, $aThreshold = Logger::ALL)
    {
        assert('!$aLogFilePath->isDir()');

        // check that dir exitsts to prevent errors when logging 
        if (!is_dir($aLogFilePath->getPath())){
            mkdir($aLogFilePath->getPath());
        }
        
        $this->logFile = $this->setPath($aLogFilePath);
        $this->writer = $this->createWriter($this->logFile);
        $this->decorator = ($aDecorator === null ? new PassthruDecorator() : $aDecorator);
        $this->threshold = $aThreshold;
    }

    /**
     * On destruct, close the writer
     */
    public function __destruct()
    {
        if ($this->writer !== null && $this->writer->isOpened()) {
            $this->writer->close();
        }
    }

    /**
     * Creates a new log filename
     *
     * @return string the log filename
     */
    protected function createFileName()
    {
        return date("Ymd") . "_framework.log";
    }

    /**
     * Creates a new FileWriter for logging purposes
     *
     * @param $aFile FileInfo
     *            The file to append the log to
     *            
     * @return FileWriter
     */
    protected function createWriter(\SplFileInfo $aFile)
    {
        return new FileWriter($aFile, AbstractStreamAccesser::APPEND_BINARY);
    }

    /**
     * Flushes the output buffer
     */
    public function flush()
    {
        //
    }

    /**
     *
     * @return compact\logging\decorator\IMessageDecorator
     */
    protected function getDecorator()
    {
        return $this->decorator;
    }

    /**
     *
     * @see compact\logging\recorder.IRecorder::getLogThreshold()
     */
    public function getLogThreshold()
    {
        return $this->threshold;
    }

    /**
     * Returns the writer
     *
     * @return FileWriter
     */
    protected function getWriter()
    {
        return $this->writer;
    }
    
    /*
     * (non-PHPdoc) @see IRecorder::isScreenRecorder()
     */
    public function isScreenRecorder()
    {
        return false;
    }

    /**
     * Sets the logfile for this logger.
     * If the param $aPath is not a file,
     * then we assume this is the logdir and append the log filename to it.
     *
     * @param $aPath \SplFileInfo            
     *
     * @return \SplFileInfo
     */
    private function setPath(\SplFileInfo $aPath)
    {
        if ($aPath->isDir()) {
            $classname = get_class($aPath);
            $path = new $classname($aPath . "/" . $this->createFileName());
        } else {
            $path = $aPath;
        }
        
        return $path;
    }

    /**
     * Records the message
     *
     * @param $aMessage String            
     * @see IRecorder::record()
     *
     * @throws FilesystemException when opening the file was not possible
     */
    public function record($aMessage, $aLogLevel)
    {
        if ($this->getLogThreshold() >= $aLogLevel) {
            $this->writer->open();
            $this->writer->writeLine($this->getDecorator()
                ->decorate($aMessage));
            $this->writer->close();
        }
    }
}