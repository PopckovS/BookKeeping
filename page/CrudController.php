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
		require_once 'public/BuyHeader.php';
	}

	/* Фрма для отправки SQL запросса INSERT */
	public function insertAction()
	{
		require_once 'public/forms/BuyInsert.php';
		$this->formExecute();
	}

	/* Фрма для отправки SQL запросса UPDATE */
	public function updateAction()
	{
		require_once 'public/forms/BuyUpdate.php';
		$this->formExecute();
	}

	/* Фрма для отправки SQL запросса DELETE */
	public function deleteAction()
	{
		require_once 'public/forms/BuyDelete.php';
		$this->formExecute();
	}

	/* Прверка нажатия кнопки отправки формы */
	public function formExecute()
	{
		/* $_POST['action'] имеется только если отправлена форма в виде hidden поля */
		switch ($_POST['action'])
		{
			case 'insert':
				return BaseModel::insertBuy($_POST);
			break;
			case 'delete':
				return BaseModel::deleteBuy($_POST);
			break;
			case 'update':
				return BaseModel::updateBuy($_POST);
			break;
		}
	}
}