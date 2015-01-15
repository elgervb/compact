<?php
namespace compact\logging\recorder\impl;

use compact\logging\Logger;
use compact\logging\recorder\IRecorder;

class CompositeLogRecorder implements IRecorder
{

    private $recorders;

    /**
     *
     * @param $aRecorders IRecorder
     *            as many recorders as you like
     */
    public function __construct(IRecorder $aRecorders)
    {
        $this->recorders = new \ArrayObject();
        
        $nrArgs = func_num_args();
        $i = 0;
        while ($i < $nrArgs) {
            
            $this->recorders->append(func_get_arg($i));
            $i ++;
        }
    }

    public function flush()
    {
        /* @var $recorder IRecorder */
        foreach ($this->recorders as $recorder) {
            $recorder->flush();
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\logging\recorder.IRecorder::getLogThreshold()
     */
    public function getLogThreshold()
    {
        return Logger::ALL;
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\logging\recorder.IRecorder::isScreenRecorder()
     */
    public function isScreenRecorder()
    {
        /* @var $recorder IRecorder */
        foreach ($this->recorders as $recorder) {
            if ($recorder->isScreenRecorder()) {
                return true;
            }
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\logging\recorder.IRecorder::record()
     */
    public function record($aMessage, $aLogLevel)
    {
        /* @var $recorder IRecorder */
        foreach ($this->recorders as $recorder) {
            $recorder->record($aMessage, $aLogLevel);
        }
    }
}