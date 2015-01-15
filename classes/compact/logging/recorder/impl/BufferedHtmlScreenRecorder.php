<?php
namespace compact\logging\recorder\impl;

use compact\logging\Logger;
use compact\logging\recorder\impl\BufferedScreenRecorder;
use compact\logging\decorator\IMessageDecorator;

/**
 * Buffered screen recorder.
 * Logs messages and outputs them to screen on destruct of the object.
 */
class BufferedHtmlScreenRecorder extends BufferedScreenRecorder
{

    private $hasError = false;

    /**
     * Creates a new BufferedScreenRecorder
     *
     * @param $aDecorator compact\logging\decorator\IMessageDecorator
     *            = null
     */
    public function __construct(IMessageDecorator $aDecorator = null, $aThreshold = Logger::ALL)
    {
        parent::__construct($aDecorator, $aThreshold);
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\logging\recorder.BufferedScreenRecorder::flush()
     */
    public function flush()
    {
        if ($this->hasError === true) {
            $this->getBuffer()->writeLine("</div>");
        }
        parent::flush();
    }

    /**
     * Records the log message
     *
     * @param $aMessage String
     *            The log message to record
     *            
     * @see IRecorder::record()
     */
    public function record($aMessage, $aLogLevel)
    {
        if ($this->getLogThreshold() >= $aLogLevel) {
            $decorator = $this->getDecorator();
            if ($this->hasError === false) {
                $this->getBuffer()->writeLine("<div style='border:1px solid #065F1A;text-align:left;padding:4px;margin:4px;border-radius:4px;'>");
            }
            $this->getBuffer()->writeLine($this->getDecorator()
                ->decorate($aMessage));
            $this->hasError = true;
        }
    }
}