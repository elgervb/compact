<?php
namespace compact\logging\recorder\impl;

use compact\logging\Logger;
use compact\io\writer\BufferedStreamWriter;
use compact\logging\decorator\IMessageDecorator;
use compact\logging\recorder\impl\ScreenRecorder;

/**
 * Buffered screen recorder.
 * Logs messages and outputs them to screen on flush.
 */
class BufferedScreenRecorder extends ScreenRecorder
{

    /**
     *
     * @var compact\io\writer\StreamWriter
     */
    private $buffer;

    /**
     * Creates a new BufferedScreenRecorder
     *
     * @param $aDecorator compact\logging\decorator\IMessageDecorator
     *            = null
     */
    public function __construct(IMessageDecorator $aDecorator = null, $aThreshold = Logger::ALL)
    {
        parent::__construct($aDecorator, $aThreshold);
        
        $this->buffer = new BufferedStreamWriter("php://output");
    }

    public function flush()
    {
        $this->buffer->flush();
    }

    /**
     * Returns the buffer
     *
     * @return \compact\io\writer\StreamWriter
     */
    protected function getBuffer()
    {
        return $this->buffer;
    }

    /**
     * Records the log message
     *
     * @param $aMessage String
     *            The log message to record
     * @see IRecorder::record()
     */
    public function record($aMessage, $aLogLevel)
    {
        if ($this->getLogThreshold() >= $aLogLevel) {
            $this->buffer->writeLine($this->getDecorator()
                ->decorate($aMessage));
        }
    }
}