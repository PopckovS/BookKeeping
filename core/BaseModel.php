<?php 

namespace core;

class BaseModel
{

    public static $pdo;
	public static $tables;
	public static $fields;
	public static $crud = ['insert', 'select', 'delete', 'update'];
	public static $host;
	public static $db;
	public static $login;
	public static $password;


	public static function connection()
	{
		$config = require 'config/ConfigDb.php';
		extract($config);

		self::$pdo = new \PDO("mysql:host=".$HOST.";dbname=".$DB.";charset=utf8",$LOGIN,$PASSWORD);

		if(!self::$pdo){
			die("Нету соединения с БД"); // Соединение с БД неустановлено убить скрипт
		}else{
			self::$host = $HOST;
			self::$db = $DB;
			self::$login = $LOGIN;
			self::$password = $PASSWORD;
			//self::dropBuy();
			//self::dropDay();
			self::createDay(); // Создание таблицы Клиентов
			self::createBuy(); // Создание таблицы Производителей
		}
	}



/*=============================== БАЗОВЫЕ МЕТОДЫ КЛАССА =========================*/
	
		
	// Простой запрос типа SELECT
	public static function simpleSelect($sql, $table)
	{
		BaseModel::connection();
		$result = self::$pdo->query($sql);
		$count = 0;

		while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
			$array[] = $row;
			$count ++;
		}

		$fields = self::getFields($table);

		return ['sql' => $sql, 'result' => $count, 'mass' => $array, 'fields' => $fields];
	}



	// Вывести содержимое
	public static function d($array)
	{
		echo "<pre>".print_r($array,1)."</pre>";
	}



	// Возвращает Асоц Массив из полей указаной таблицы Назв.Поля и Тип.Поля / Для любого режима работы
	public static function getFields(string $table):array
	{
		return self::doReturn('SHOW COLUMNS FROM '.$table, 'ASSOC');
	}



	// Получить Список всех таблиц в БД / Для обычного режима работы
	public static function Alltables():array
	{
		return self::doReturn('SHOW TABLES FROM '.self::$db, 'NUM', 'one');
	}


	/* Возвращает результат SQL запроса в 2 формах(Асоц или Нум массива)
		@param $query = сам SQL запрос
		@param $fetch = тип возвращ массива Нумерованный/Ассоциативный
		@param $nesting = количество вложенности в массив one = один массив; two =двойная вложенность

		@return это всегда миссив ассоц/нумер с выборкой данных
		Суть такова в зависимости от параметров мы получаем выборку либо в ассоц массиве либо
		в нумер массиве с различной степенью вложенности
	*/
	public static function doReturn(string $query, string $fetch = 'NUM', string $nesting = 'two'):array
	{
		 $result = self::$pdo->query($query);
		switch ($fetch)
		{
			case 'ASSOC':
				while ($row = $result->fetch(\PDO::FETCH_ASSOC))
				{
					$array[] = $row;
				}
			break;
			case 'NUM':
				while ($row = $result->fetch(\PDO::FETCH_NUM))
				{
					if ($nesting == 'two') {
						$array[] = $row;
					}elseif($nesting == 'one'){
						$count = count($row);
						for ($i=0; $i < $count; $i++) { 
							$array[] = $row[$i];
						}
					}
				}
			break;
		}
 
		return $array;	
	}


	// Очистка полей таблицы от нежелательных
	public static function clearFieldsOf($fields, $delete = [])
	{
		$count_i = count($fields);
		$count_y = count($delete);

		for ($i=0; $i < $count_i; $i++) 
		{ 
			for ($y=0; $y < $count_y; $y++) 
			{ 
				if ($fields[$i]['Field'] == $delete[$y]) 
				{
					unset($fields[$i]);
				}
			}
		}


		foreach ($fields as $key) {
			$mass[] = $key;
		}


		$count = count($mass);
		$result = [];
		for ($i=0; $i < $count; $i++) 
		{ 
			$result[$i]['Field'] = $mass[$i]['Field'];
			if (strpos($mass[$i]['Type'], '(')) {
				$result[$i]['Type'] = strstr($mass[$i]['Type'], '(', true);
			}else{
				$result[$i]['Type']  = $mass[$i]['Type'];
			}
		} 

		return $result;
	} 


	//
	public static function clear(array $array): array
	{
		$count = count($array);
		$result = [];
		for ($i=0; $i < $count; $i++) 
		{ 
			$result[$i]['Field'] = $array[$i]['Field'];
			if (strpos($array[$i]['Type'], '(')) {
				$result[$i]['Type'] = strstr($array[$i]['Type'], '(', true);
			}else{
				$result[$i]['Type']  = $array[$i]['Type'];
			}
		}

		return $result;
	}

/*=============================== Методы создания таблиц и Базы Данных =========================*/
	
	//
	public static function dropBuy()
	{
		$sql = "DROP TABLE `Buy`";
		$result = self::$pdo->query($sql);
	}

	//
	public static function dropDay()
	{
		$sql = "DROP TABLE `Day`";
		$result = self::$pdo->query($sql);
	}


	// Таблица Покупок связь M::1 с таблицей Day по полю day
	public static function createBuy()
	{
		$result = self::$pdo->query(
			"CREATE TABLE `BookKeeping`.`Buy` 
			( 	`id` INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
				`day` DATE NOT NULL , 
				`name` VARCHAR(255) NOT NULL ,
				`price` FLOAT(10) NOT NULL , 
				`count` INT(10) NOT NULL , 
				`place` VARCHAR(255) NOT NULL , 
			 	`expenditure` VARCHAR(10) NOT NULL 
			) 
				ENGINE = InnoDB 
				CHARSET= utf8 COLLATE utf8_general_ci;
			"
		);
	}



	// Таблица Дней связь 1::M по отношению к таблице Buy по полю day
	public static function createDay()
	{
		$result = self::$pdo->query(
			"CREATE TABLE `BookKeeping`.`Day` 
			(
				`id` INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`day_date` DATE NOT NULL ,
				`all_price` INT(10) NOT NULL,
				`price` FLOAT(10) NOT NULL ,
				`expenditure` FLOAT(10) NOT NULL  
			)
				ENGINE = InnoDB 
				CHARSET= utf8 COLLATE utf8_general_ci;
			"
		);
	}


	/*
	ALTER TABLE Day ADD COLUMN all_price INT(10) NOT NULL AFTER day;
	"SELECT all_price FROM `Day` WHERE all_price != 0 " Выборка где не равно = 0
	*/

	/* Выборка всех цен за все даты 
	Вызвать метод countAverage 
	ввернуть результат*/
	public static function averageAllCount()
	{
		BaseModel::connection();
		$sql = "SELECT all_price FROM `Day` WHERE all_price > 100 ";
		$result = self::$pdo->query($sql);
		$count = 0;

		while ($row = $result->fetch(\PDO::FETCH_NUM)) 
		{
			$array[] = $row[0];
			$count ++;
		}
		//self::d($array);
		//var_dump($array);

		if ($array == null) {
			return 0; 
		}else{
			return self::countAverage($array); 
		}	
	}

    /*========================== Эта функция неработает пока ! =======================*/
	/* Выборка цен между введенными датами 
	Вызвать метод countAverage 
	ввернуть результат*/
	public static function averageAllCountBetween()
	{
		BaseModel::connection();

		$from = '2019-05-24';
		$to = '2019-05-26';


		$sql = "SELECT all_price FROM `Day` WHERE day_date BETWEEN '{$from}' AND '{$to}'"; 

		$result = self::$pdo->query($sql);
		$count = 0;

		while ($row = $result->fetch(\PDO::FETCH_NUM)) 
		{
			$array[] = $row[0];
			$count ++;
		}

		if ($array == null) {
			return 0; 
		}else{
			return self::countAverage($array); 
		}
	}


	/* @param array массив с общими ценами всех покупок за введенные даты
	посчитать и вернуть среднее значение трат */
	public static function countAverage(array $array): float
	{
		$count = count($array);
		$delitel = 0;
		for ($i=0; $i < $count; $i++) 
		{ 
			$float += $array[$i];
			$delitel ++;
		}

		$result = ($float/$delitel);
		return intval($result*100)/100;
	}


	
	/*================== МЕТОДЫ ДЛЯ ТАБЛИЦ Day,Buy ===============*/

	/* Внесение записи в таблицу Buy, подсчет всех записей в Buy по вносимой дате 
	и внесение этой стоимости в таблицу Day */
	public static function insertBuy($post)
	{
		//Внести данные в таблицу Buy о покупке, это стандартная функция описана ниже.
		self::insert($post);

		/* Выбрать данные все данные обо всех записях в у которых совпадает день,
		посчитать и записать поле price в переменную $count для всех записей по такой дате */
		$count = 0;
		$expenditure = 0;
		$day = $_POST['day'];
		$sql = "SELECT * FROM `Buy` WHERE day = '{$day}'";
		$result = self::$pdo->query($sql);
		while ($row = $result->fetch(\PDO::FETCH_ASSOC)) 
		{
			$array[] = $row;
			if ($row['expenditure'] == 'yes') {
				$count = $count + ($row['price'] * $row['count']);
			}elseif($row['expenditure'] == 'no'){
				$expenditure = $expenditure + ($row['price'] * $row['count']);
			}
		}

		/* запрос select для проверки есть ли запись в таблице Day по такой дате 
		если есть то обновить если нет то создать новую запись */
		$all_price = 0;
		$sql = "SELECT * FROM `Day` WHERE day_date = '{$day}'";
		$result = self::$pdo->query($sql);
		while ($row = $result->fetch(\PDO::FETCH_ASSOC)) 
		{
			$arr[] = $row;
			$all_price = $row['all_price'];
		}

		/* Если == null то выполнить операцию INSERT, если нет то выполнить UPDATE */
		if ($arr == null) {

			$all_price = $count + $expenditure;
			$sql = "INSERT INTO `Day` VALUES (null, '{$day}', '{$all_price}', '{$count}', '{$expenditure}')";
			$result = self::$pdo->exec($sql);
		}else{
			echo $count;
			echo $expenditure;
			echo $all_price;
			$all_price = $count + $expenditure;

			$sql = "UPDATE `Day` SET 
				all_price = '{$all_price}', 
				price = '{$count}', 
				expenditure = '{$expenditure}' 
				WHERE day = '{$day}'";
			$result = self::$pdo->exec($sql);
		}
	}





	/* Удаление записи из таблицы Buy, и перерасчет поля price для таблицы Day 
	Данный метод является обертко для метода preDelete а не delete как у метода insertBuy */
	public static function deleteBuy($post)
	{
		/**/
		BaseModel::connection();
		$sql = self::preDelete($post);
		var_dump($sql);
		//$result = self::$pdo->exec($sql);
		//return ['sql' => $sql, 'result' => $result];
		/**/
		
		die();

		$day = $_POST['day'];
		$sql = "SELECT * FROM `Buy` WHERE day = '{$day}'";
		$count = 0;
		$result = self::$pdo->query($sql);
		while ($row = $result->fetch(\PDO::FETCH_ASSOC)) 
		{
			$array[] = $row;
			$count += $row['price'];
		}

		//self::delete($post);// Удаляю запись из таблицы Buy

		echo "<hr>";
		echo $sql;
		echo "<hr>";
		self::d($array);
		echo "<hr>";
		echo $count;
		echo "<hr>";
	}



	/*================== МЕТОДЫ СИСТЕМЫ CRUD Insert/Selet/Delete/Update ===============*/
	// Конструктор запросов для типа INSERT
	public static function insert($post)
	{
		BaseModel::connection();
		$sql = self::preInsert($post);
		$result = self::$pdo->exec($sql);
		return  ['sql' => $sql, 'result' => $result];
	}

	// Внести данные в Любую таблицу 
	protected static function preInsert($post)
	{

		//var_dump($post['table']);
		$fields = self::getFields($post['table']);
		$sql = "INSERT INTO `{$post['table']}` (";
		$count = count($fields);
		$count_i = count($fields);

		for ($i=0; $i < $count; $i++) 
		{ 
			$sql .= " `{$fields[$i]['Field']}` ";
			if ($count_i > 1) 
			{
				$sql .= ", ";
			}
			$count_i--;
		}

		$sql .= ") VALUES ( ";

		$count_i = count($fields);
		$keys = array_keys($post);
		$count_y = count($keys);

		for ($i=0; $i < $count; $i++) 
		{ 
			for ($y=0; $y < $count_y; $y++) { 
				if ($keys[$y] == $fields[$i]['Field']) 
				{
					$sql .= " '{$post[$keys[$y]]}' ";
					if ($count_i > 1) 
					{
						$sql .= ", ";
					}
					$count_i--;
				}
			}
		}

		$sql .= " );";

		return $sql;
	}








	// Конструктор запросов для типа SELECT
	public static function select($post)
	{
		BaseModel::connection();
		$sql = self::preSelect($post);
		$result = self::$pdo->query($sql);
		$count = 0;

		while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
			$array[] = $row;
			$count ++;
		}

		return ['sql' => $sql, 'result' => $count, 'mass' => $array, 'fields' => self::$fields];
	}


	// Удаление данные из Любой таблицы по любым полям
	protected static function preSelect($post)
	{
		$fields = self::getFields($post['table']);

		if ($post['type_select'] == 'all') {
			$sql = "SELECT * FROM `{$post['table']}`";
			self::$fields = $fields;
		}else{

			self::$fields = [];
			$sql = "SELECT ";

			$keys = array_keys($post);
			$countPost = count($post);
			// Удалить из массива $post все значения == ''
			for ($i=0; $i < $countPost; $i++) 
			{ 
				if ($post[$keys[$i]] == '') {
					$names[] = $keys[$i];
					unset($post[$keys[$i]]);
				}
			}
			// Удалить из массива $fields все эллементы у которых нету аналогов из массива $post
			$countName = count($names);
			$count = count($fields);
			
			$fil = $fields;
			$f = count($fil);

			for ($i=0; $i < $f; $i++) 
			{ 
				for ($y=0; $y < $countName; $y++) 
				{ 
					if ($fil[$i]['Field'] == $names[$y]) {
						
						unset($fil[$i]);
					}
				}
			}



			// Перезапись массива чтобы начинался с 0...
			foreach ($fil as $key) {
				$mass[] = $key;
			}
			$fil = $mass;
			

			$p = count($post);
			foreach ($post as $key => $value) 
			{
				if (strstr($key, 'select') !== false) 
				{
					$select[] = $key;
				}
			}

			
			// Добавление полей по которые будут выбраны
			//echo $count_i = count($fil);
			$countSelect = count($select);
			for ($i=0; $i < $count; $i++) 
			{ 
				foreach ($post as $key => $value) 
				{
					if ($fields[$i]['Field'] == $value) 
					{
						$sql .= " {$fields[$i]['Field']} ";
						self::$fields[]['Field'] = $fields[$i]['Field'];
						if ($countSelect > 1)
						{
							$sql .= ",";
							$countSelect --;
						}
					}
				}
			}

			$sql .=" FROM `{$post['table']}` WHERE ";

			$fil = self::clear($fil);

			// Добавление полей по которым будет выборка 
			$count = count($fil);
			$keys = array_keys($post);
			$count_y = count($keys);
			$count_i = count($fil);
			for ($i=0; $i < $count; $i++) 
			{ 
				if ($fil[$i]['Type'] == 'int') 
				{
					$sql .= " {$fil[$i]['Field']} = ";

					for ($y=0; $y < $count_y; $y++) { 
						if ($keys[$y] == $fil[$i]['Field']) 
						{
							$sql .= " {$post[$keys[$y]]} ";
						}
					}

				}elseif($fil[$i]['Type'] == 'varchar'){

					$sql .= " {$fil[$i]['Field']} = ";

					for ($y=0; $y < $count_y; $y++) { 
						if ($keys[$y] == $fil[$i]['Field']) 
						{
							$sql .= " '{$post[$keys[$y]]}' ";
						}
					}
				}

				if ($count_i != 1) {
					$sql .= " AND ";
					$count_i --;
				}
			}
			
			$sql .= " ;";
		}


		return $sql;
	}

	

}

