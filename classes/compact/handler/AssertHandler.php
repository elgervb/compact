<?php
namespace compact\handler
{

    /**
     * Enabe or disable asserts
     *
     * @author eaboxt
     *        
     */
    class AssertHandler
    {

        /**
         * Disable all assertions
         */
        public static function disable()
        {
            assert_options(ASSERT_ACTIVE, 0);
            assert_options(ASSERT_WARNING, 0);
            assert_options(ASSERT_BAIL, 0);
            assert_options(ASSERT_QUIET_EVAL, 0);
        }

        /**
         * Enable all assertions, optionally supply a handle function
         *
         * @param \Closure $aHandle
         *            = null Handle the assertion, params: $aFile, $aLine, $aCode
         *            
         * @throws AssertionFailedException by default, override this behavior by supplying a handle function
         */
        public static function enable(\Closure $aHandle = null)
        {
            if ($aHandle === null) {
                $aHandle = function ($aFile, $aLine, $aCode)
                {
                    $message = "Assertion failed:\n";
                    $message .= "\tFile: " . $aFile . "\n";
                    $message .= "\tLine: " . $aLine . "\n";
                    $message .= "\tCode: " . $aCode . "\n";
                    
                    throw new AssertionFailedException($message, 0, E_ERROR, $aFile, $aLine);
                };
            }
            
            assert_options(ASSERT_ACTIVE, 1);
            assert_options(ASSERT_WARNING, 0);
            assert_options(ASSERT_BAIL, 0);
            assert_options(ASSERT_QUIET_EVAL, 0);
            assert_options(ASSERT_CALLBACK, $aHandle);
        }
    }

    class AssertionFailedException extends \ErrorException
    {
    }
}
