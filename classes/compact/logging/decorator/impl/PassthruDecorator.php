<?php
namespace compact\logging\decorator\impl;

use compact\logging\decorator\IMessageDecorator;

/**
 * Just a no-op file decorator, which passes thru the log message
 *
 * @author elger
 *        
 */
class PassthruDecorator implements IMessageDecorator
{

    /**
     * (non-PHPdoc)
     *
     * @see compact\logging\decorator.IMessageDecorator::decorate()
     */
    public function decorate($aLogMessage)
    {
        return $aLogMessage;
    }
}