<?php
namespace compact\logging\decorator\impl;

use compact\logging\decorator\IMessageDecorator;
use compact\Context;

/**
 *
 * @author elger
 */
class UserContextDecorator implements IMessageDecorator
{

    /**
     *
     * @param sytring $aLogMessage            
     * @return string The log message
     * @see IMessageDecorator::decorate()
     */
    public function decorate($aLogMessage)
    {
        return date("Y-m-d H:i:s:u") . " " . Context::get()->http()->getRequest()->getUserIP() . " " . $aLogMessage;
    }
}