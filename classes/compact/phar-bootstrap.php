<?php
Phar::mapPhar();

$basePath = 'phar://' . __FILE__ . '/';
if (is_file($basePath . 'classes/compact/PharClassLoader.php')){
	require $basePath . 'classes/compact/PharClassLoader.php';
}
$classLoader = new \compact\PharClassLoader(__FILE__);
$classLoader->register();

__HALT_COMPILER();