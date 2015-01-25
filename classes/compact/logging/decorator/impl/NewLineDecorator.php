<?php
namespace compact\logging\decorator\impl;

use compact\logging\decorator\IMessageDecorator;

/**
 *
 * @author elger
 */
class NewLineDecorator implements IMessageDecorator
{

    /**
     *
     * @param sytring $aLogMessage            
     * @return string The log message
     * @see IMessageDecorator::decorate()
     */
    public function decorate($aLogMessage)
    {
        return $aLogMessage . "\n";
    }
}