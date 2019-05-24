<?php 

namespace libs;

/* Класс осуществляет переадресацию на другой URL, Выводит ошибки если таковые присутствуют
Также может просто выводить предупреждение для пользователя */
class Errors
{
	protected static $default = '';//
	protected static $location = '';

	//
	public function __construct(string $location, string $default)
	{
		self::$location = $location;
		self::$default  = $default;
	}

	// Перенаправить пользователя на другой URL
	public static function warning(string $message, array $array = null)
	{
		require_once self::$default.'warning.php';
	}

	// Перенаправить пользователя на другой URL
	public static function relocation(string $param)
	{
		header('Location:'.self::$location.$param);
	}

	public static function d($array)
	{
		echo "<pre>".print_r($array,1)."</pre>";
	}
}