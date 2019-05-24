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
?>


<!DOCTYPE html>
<html lang="en" >
<head>
    <title>BookKeeping</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" type="text/css" href="public/style.css" >   
</head>   
<body>

<h1>ШАБЛОН</h1><hr>
<?php 
	require_once 'public/header.php';
	// Создание обьекта класса Роутера и передача ему всех переменных типа $_GET 
	Router::dispatch($_SERVER['QUERY_STRING']); 
?>
<hr><h1>КОНЕЦ ШАБЛОН</h1>

</body>
</html>


