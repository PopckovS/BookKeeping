<?php

namespace libs;

class FormBuilder 
{
	protected $fieldsConfig = '';// Содержит путь к кнфигам с названиями полей
	protected $fieldsName; // Содержит массив с псевдоименами для названий полей формы
	protected $table; // Содержит нразвание таблицы и по совместит название формы  и кнопки отправки
	protected $action;// Тип действия формы 1 из insert/select/delete/update
	protected $count; // Количество полей в текущей таблице

	protected $fields = [];

	public function __construct()
	{
		
	}


	/*
	* @param - $fields = Ассоц массив полей, выборка из БД
	* @return - Возвращает буфериз форму  
	*/
	public function createForm(array $fields = [])
	{
		$this->fields = $fields;

		if ($this->fields != []) {
			ob_start();
			$this->d($fields);
			return ob_get_clean();
		}else{
			echo "Поля для формы неустановлены";
		}
	}


	/* 
	* @param - $fields = Ассоц массив полей, выборка из БД
	* @param - $table = Название таблицы
	* @param - $crud = Тип операции insert/select/delete/update
	* @param - $path = Путь к файлу в котором будет описаны алиасы для названий полей
	* @return - Возвращает буфериз форму 
	* Создание формы из расчета передачи в нее результатов выборки SQL запроса: 
	* "SHOW COLUMNS FROM НАЗВАНИЕ_ТАБЛИЦЫ " с расчетом что это будет Ассоциативный массив */
	public function createDataBaseForm(array $fields = [], string $table, string $action, string $path)
	{
		$this->fields = $fields;
		$this->table  = $table;
		$this->action = $action;
		$this->count  = count($fields);
		$this->clearFieldsName($path);

		$this->d($fields);

		if ($this->fields != []) {
			ob_start();
				$action .= 'Form'; 
				$this->getHeaderForm();
				$this->$action($fields);
				$this->getButtonForm($table.'_go', 'Отправить');
			return ob_get_clean();
		}else{
			echo "Поля для формы неустановлены";
		}
		
	}	


	// Очистка массива с названиями полей от лишних таблиц, оставляет толко Нум.Массив с названиями полей
	// той таблицы с которой работаем в текущей момент
	protected function clearFieldsName($path)
	{
		// Записать в поле параметры для названий полей таблицы
		$this->fieldsName = require $path;
		foreach ($this->fieldsName[$this->table] as $key => $value) {
			$mass[] = $value;
		}
		$this->fieldsName = $mass;
		unset($mass);
	}




	//================ МЕТОДЫ ЗАПРОСОВ INSERT / SELECT / DELETE / UPDATE =================
	// Формирование формы для запросов INSERT
	// @param ассоц массив с полями текущей таблицы
	protected function insertForm($array)
	{
		echo "<h4>Внести данные для таблицы - {$this->table}</h4>";

		$this->getHidden('action', 'insert');
		$this->getHidden('table', $this->table);

 		for ($i=0; $i < $this->count; $i++) 
 		{ 
 			if (($array[$i]['Extra'] == 'auto_increment') & ($this->action == 'insert')) {
 				$this->getHidden($array[$i]['Field'], 'NULL');
 			}elseif($array[$i]['Type'] == 'text'){
 				echo "<p>";
 				$this->getLabel($array[$i]["Field"], 'Укажите '.$this->fieldsName[$i].' : ');
 				$this->getTextArea($array[$i]["Field"]);
 				echo "</p>";
 			}else{
 				echo "<p>";
 				$this->getLabel($array[$i]["Field"], 'Укажите '.$this->fieldsName[$i].' : ');
 				$this->getInput($array[$i]["Field"]);
 				echo "</p>";
 			}
 		}
	}


	// Формирование формы для запросов SELECT
	protected function selectForm($array)
	{
		echo "<h4>Выбрать данные из таблицы - {$this->table}</h4>";

		$this->getHidden('action', 'select');
		$this->getHidden('table', $this->table);

		$this->getSelect('Тип выборки', 'type_select', ['all']);
		$this->getSelect('Сортировка по полю', 'fields_select', $this->getOnlyFields());
		
		for ($i=0; $i < $this->count; $i++) 
 		{ 
 			echo "<p>";
 			$this->getLabel($array[$i]["Field"], 'Там где '.$this->fieldsName[$i].' : ');
 			$this->getInput($array[$i]["Field"]);
 			$this->getChecbox('Выбрать','select_'.$array[$i]["Field"], $array[$i]["Field"]);
 			echo "</p>";
		}
	}

	
	// Формирование формы для запросов DELETE
	protected function deleteForm($array)
	{
		echo "<h4>Удалить из таблицы - {$this->table}</h4>";

		$this->getHidden('action', 'delete');
		$this->getHidden('table', $this->table);

		for ($i=0; $i < $this->count; $i++) 
 		{ 
			echo "<p>";
 			$this->getLabel($array[$i]["Field"], 'Удалить по '.$this->fieldsName[$i].' : ');
 			$this->getInput($array[$i]["Field"]);
 			echo "</p>";
		}
	}


	// Формирование формы для запросов UPDATE
	protected function updateForm($array)
	{
		echo "<h4>Обновить данные для таблицы - {$this->table}</h4>";

		$this->getHidden('action', 'update');
		$this->getHidden('table', $this->table);


		for ($i=0; $i < $this->count; $i++) 
 		{ 
 			echo "<p>";
 			$this->getLabel($array[$i]["Field"], 'Внести '.$this->fieldsName[$i].' : ');
 			$this->getInput('set_'.$array[$i]["Field"]);
 			echo "</p>";
		}
		for ($i=0; $i < $this->count; $i++) 
 		{ 
 			echo "<p>";
 			$this->getLabel($array[$i]["Field"], 'Там где '.$this->fieldsName[$i].' : ');
 			$this->getInput('where_'.$array[$i]["Field"]);
 			echo "</p>";
		}
	}



	// Метод для вывода содержимого
	public function d($array)
	{
		echo "<pre>".print_r($array,1)."</pre>";
	}

	/*==========МЕТОДЫ ДЛЯ СОЗДАНИЯ ФОРМЫ НА ОСНОВЕ ТАБЛИЦЫ ИЗ БД=========*/

	/**/
	protected function getHidden(string $name,string  $value)
	{
		echo "<input type='hidden' name={$name} value={$value} >";
	}

	/**/
	protected function getSelect(string $label, string $name, array $options)
	{
		array_unshift($options, '');
		echo "string";
		echo "<p><label for={$name}>{$label}</label>
		<select name={$name}>";
		$count = count($options);
		for ($i=0; $i < $count; $i++) 
		{ 
			echo '<option>'.$options[$i].'</option>';
		}
		echo "</select></p>";
	}

	/**/
	protected function getLabel(string $for, string $name)
	{
		echo "<label for={$for}>{$name}</label><br>";
	}

	protected function getInput(string $name)
	{
		echo "<input type='text' name={$name} class='textbox'> </input>";
	}

	// Создание кнопки 
	protected function getButtonForm($button, $label)
	{
		echo "<button name={$button} value='true' class='button'>{$label}</button></form>";
	}

	// Создание кнопки 
	protected function getHeaderForm()
	{
		echo "<form method='POST' action=''>";
	}

	// Создание кнопки 
	protected function getTextArea($name)
	{
		echo "<textarea name={$name} class='textarea'></textarea>";
	}
}
