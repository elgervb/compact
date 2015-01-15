<?php
namespace compact\logging\decorator\impl;

use compact\logging\decorator\IMessageDecorator;
/**
 * Decorates the log with the current time and the IP address of the visitor
 *
 * @author elger
 */
class LogDecorator implements IMessageDecorator
{

    /**
     *
     * @param sytring $aLogMessage            
     * @return string The log message
     * @see IMessageDecorator::decorate()
     */
    public function decorate($aLogMessage)
    {
        return date("Y-m-d H:i:s") . " - " . $_SERVER['REMOTE_ADDR'] . " - " . $aLogMessage;
    }
}