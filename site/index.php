<?php
use compact\mvvm\FrontController;
include_once '../classes/compact/ClassLoader.php';
compact\ClassLoader::create()->addClassPath(__DIR__."/app");

$fc = new FrontController();
$fc->run();
