<?php  

namespace core;

/* Класс Роутера определяет какую страницу отобразить при данном URL запросе */
class Router
{
	// ТУТ ! надо указвать именно обратн слеш иначе класс неподключ и нейпрйодет проверку на существованив в функции finedController
	private static $controllerPath = 'page\\';
	private static $param;
	private static $controller;
	private static $action;

	public static function dispatch($param)
	{
		self::$param = self::splitArray($param);

		// Вызвать нужный контроллер
		if (isset(self::$param['method'])) 
		{
			$controller = self::finedController(self::$param['method']);
			if(!isset(self::$param['action'])) self::$param['action'] = 'index';
			$action = self::finedAction($controller, self::$param['action']);

			$obj = new $controller(self::$controller, self::$action, self::$param);
			$obj->$action();
			
		}else{
			header('Location:index.php?method=main');// Если запрос некорректен то отправить на главную страницу
		}
	}

	// Метод нахходит Дествие(Метод) Котроллера
	private static function finedAction($controller, $action)
	{
		unset(self::$param['action']);
		$action = self::lowerCamel($action);

		if (method_exists($controller, $action.'Action')) {
			self::$action = $action;
			return $action.'Action';
		}else{
			$action = 'indexAction';
			self::$action = $action;
			return $action.'Action';
		}
	}

	// Метод находит Контроллер
	private static function finedController($controller)
	{
		unset(self::$param['method']);
		$controller = self::upperCamel($controller);
		$path = self::$controllerPath.$controller.'Controller';

		if (class_exists($path)){
			self::$controller = $controller;
		}else{
			self::$controller = 'Main';
		}
		return $path;
	}


	/* Привести всю строку к нищ.регистр и поднять только первый символ в верх.регистр */
	private static function upperCamel($name)
	{
		return ucfirst(strtolower($name));
	}

	/* Привести всю строку к низ.регистр */
	private static function lowerCamel($name)
	{
		return lcfirst($name);
	}

	// Разбивает URL запрос на Ассоуиативный Массив 
	private static function splitArray(string $gets)
	{
		$array = explode("&", $gets);
		$count = count($array);
		for ($i=0; $i < $count; $i++) { 
			list($name, $value) = explode("=", $array[$i]);
			$result[$name] = $value;
		}
		return $result;
	}

	// Вывести содержимое Массива в удобночитаемом виде
	private static function d($array)
	{
		echo "<pre>".print_r($array,1)."</pre>";
	}

}