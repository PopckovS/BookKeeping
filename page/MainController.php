<?php

namespace page;

use core\BaseModel;

// Главный Контроллер (Класс) - отображает Главную страницу
class MainController
{
	//Главный Метод контроллера вызывается по дефолту
	public function indexAction()
	{
		require_once 'public/main/index.php';

		$average = BaseModel::averageAllCount();
		echo '<br><p style="color:red">В среднем вы тратите : '.$average.'</p>';
	}
}