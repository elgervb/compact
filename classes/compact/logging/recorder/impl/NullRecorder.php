<?php
namespace compact\logging\recorder\impl;

/**
 * No logging
 */
use compact\logging\recorder\IRecorder;

class NullRecorder implements IRecorder
{

    public function __construct()
    {
        //
    }
    
    /*
     * (non-PHPdoc) @see IRecorder::flush()
     */
    public function flush()
    {
        //
    }
    
    /*
     * (non-PHPdoc) @see IRecorder::isScreenRecorder()
     */
    public function isScreenRecorder()
    {
        return false;
    }
    
    /*
     * (non-PHPdoc) @see \compact\logging\recorder\IRecorder::record()
     */
    public function record($aMessage, $aLogLevel)
    {
        // do nothing with the message
    }
    
    /*
     * (non-PHPdoc) @see \compact\logging\recorder\IRecorder::getLogThreshold()
     */
    public function getLogThreshold()
    {
        // TODO: Auto-generated method stub
    }
}
