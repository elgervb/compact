<?php
namespace compact\logging\recorder\impl;

use compact\logging\Logger;
use compact\logging\recorder\IRecorder;
use compact\logging\decorator\impl\PassthruDecorator;
use compact\logging\decorator\IMessageDecorator;

/**
 * Screen recorder.
 * Logs messages and outputs them directly to screen.
 */
class ScreenRecorder implements IRecorder
{

    /**
     *
     * @var resource
     */
    protected $out;

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
     * Creates a new ScreenRecorder
     *
     * @param IMessageDecorator $aDecorator
     *            = null
     * @param $aThreshold int
     *            defaults to Logger::ALL
     */
    public function __construct(IMessageDecorator $aDecorator = null, $aThreshold = Logger::ALL)
    {
        $this->out = fopen("php://output", "a");
        $this->decorator = ($aDecorator === null ? new PassthruDecorator() : $aDecorator);
        $this->threshold = $aThreshold;
    }

    public function __destruct()
    {
        fclose($this->out);
    }

    /**
     * Returns the stream to write to
     *
     * @return resource
     */
    protected function getHandle()
    {
        return $this->out;
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
     * Flushes the output buffer
     */
    public function flush()
    {
        fflush($this->out);
    }

    /**
     * Returns the log message decorator
     *
     * @return compact\logging\decorator\IMessageDecorator
     */
    public function getDecorator()
    {
        assert('$this->decorator instanceof compact\logging\decorator\IMessageDecorator');
        return $this->decorator;
    }
    
    /* (non-PHPdoc) @see IRecorder::isScreenRecorder() */
    public function isScreenRecorder()
    {
        return true;
    }

    /**
     *
     * @param $aMessage String
     *            The log message to record
     * @see IRecorder::record()
     */
    public function record($aMessage, $aLogLevel)
    {
        if ($this->getLogThreshold() >= $aLogLevel) {
            fwrite($this->out, $this->getDecorator()->decorate($aMessage));
        }
    }
}