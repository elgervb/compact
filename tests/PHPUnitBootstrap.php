<?php
use compact\ClassLoader;
use compact\logging\Logger;
use compact\logging\recorder\impl\FileRecorder;
use compact\mvvm\FrontController;
use compact\Context;

// include the classloader
include __DIR__ . '/../classes/compact/ClassLoader.php';

// add classpath
ClassLoader::create()->addClassPath(__DIR__ . '/');

/* MOCKS */
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

// implement logging
Context::get()->addService(Context::SERVICE_LOGGING, function ()
{
    $path = sys_get_temp_dir() . '/compact-' . date('Ymd', time()) . '.log';
    return new Logger(new FileRecorder(new \SplFileInfo($path)), Logger::ALL);
});

// init default services, like logging
new FrontController();