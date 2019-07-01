<?php

namespace page;

use core\BaseModel;

/* Данный класс загружает выпадающее меню для таблицы Buy, и загружает одну из трех форм 
отправки SQL запросса для этой таблицы. Проверяет нажатие кнопки формы и отправляет на 
исполненеи SQL запрос*/
class CrudController
{
	/* Загрузка выпадающего меню до отображения конкретной формы*/
	public function __construct()
	{

	}


	/* Главная страница для контроллера CrudController содержит общую информацию */
	public function indexAction()
	{

	}


	// Вывести содержимое
	public  function d($array)
	{
		echo "<pre>".print_r($array,1)."</pre>";
	}


	/* Форма для отправки SQL запросса INSERT */
	public function insertAction()
	{
		require_once 'public/forms/BuyInsert.php';

		switch ($_POST['action'])
		{
			case 'insert':
				return BaseModel::insertBuy($_POST);
			break;
		}
		
	}



	/* Функцция выводит текущую дату в формате : 2019-5-14 */
	public function getToday()
	{
		$date = getdate();
		echo $today = $date['year'].'-'.$date['mon'].'-'.$date['mday'];
	}

}