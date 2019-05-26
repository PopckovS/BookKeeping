<?php 
use core\Router;
use libs\Errors;

session_start();

// Для подключения через пространство имен в автозагрузке
define('ROOT', str_replace('\\', '/', dirname(__DIR__)));

/* КАК работает включ и загрузку базовых классов */
spl_autoload_register(function($class){
	$path = __DIR__.'/'.$class.'.php';
	$path = str_replace('\\','/',$path);
	//echo $path."<hr>";
	if (is_file($path)) {
		require $path;
	}
});

new Errors('index.php?','public/errors/');

require_once 'public/default.php';



