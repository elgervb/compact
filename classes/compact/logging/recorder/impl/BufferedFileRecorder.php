<?php
namespace compact\logging\recorder\impl;

use compact\logging\Logger;
use compact\io\AbstractStreamAccesser;
use compact\io\writer\BufferedStreamWriter;
use compact\logging\recorder\impl\FileRecorder;
use compact\logging\decorator\IMessageDecorator;

/**
 * Buffered file recorder.
 * Logs messages and outputs them to a file on destruct of the object.
 */
class BufferedFileRecorder extends FileRecorder
{

    /**
     * Creates a new BufferedFileRecorder
     *
     * @param $aLogFilePath FileInfo
     *            The logfile path
     */
    public function __construct(\SplFileInfo $aLogFilePath, IMessageDecorator $aDecorator = null, $aThreshold = Logger::ALL)
    {
        parent::__construct($aLogFilePath, $aDecorator, $aThreshold);
    }

    /**
     * Creates a new BufferedFileWriter for logging purposes
     *
     * @param $aFile SplFileInfo
     *            The file to append the log to
     *            
     * @return FileWriter
     */
    protected function createWriter(\SplFileInfo $aFile)
    {
        return new BufferedStreamWriter($aFile, AbstractStreamAccesser::APPEND_BINARY);
    }

    /**
     *
     * @see FileRecorder::record()
     *
     * @param $aMessage String            
     */
    public function record($aMessage, $aLogLevel)
    {
        if ($this->getLogThreshold() >= $aLogLevel) {
            $msg = $this->getDecorator()->decorate($aMessage . "\n");
            $this->getWriter()->write($msg);
        }
    }
}