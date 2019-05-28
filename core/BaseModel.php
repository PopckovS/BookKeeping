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
	public static function simpleSelect($sql)
	{
		BaseModel::connection();
		$result = self::$pdo->query($sql);
		$count = 0;

		while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
			$array[] = $row;
			$count ++;
		}

		return ['sql' => $sql, 'result' => $count, 'array' => $array];
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

/*=============================== Методы создания таблиц =========================*/
	
	//
	public static function dropBuy()
	{
		$result = self::$pdo->query("DROP TABLE `Buy`");
	}

	//
	public static function dropDay()
	{
		$result = self::$pdo->query("DROP TABLE `Day`");
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
				`day` DATE NOT NULL ,
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


	*/
	/*================== МЕТОДЫ ДЛЯ ТАБЛИЦ Day,Buy ===============*/

	/* Внесение записи в таблицу Buy, подсчет всех записей в Buy по вносимой дате 
	и внесение этой стоимости в таблицу Day */
	public static function insertBuy($post)
	{
		//Внести данные в таблицу Buy о покупке
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
		$sql = "SELECT * FROM `Day` WHERE day = '{$day}'";
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





	/* Удаление записи из таблицы Buy, и перерасчет поля price для таблицы Day */
	public static function deleteBuy($post)
	{
		self::delete($post);

		$day = $_POST['day'];
		$sql = "SELECT * FROM `Buy` WHERE day = '{$day}'";
		$count = 0;
		$result = self::$pdo->query($sql);
		while ($row = $result->fetch(\PDO::FETCH_ASSOC)) 
		{
			$array[] = $row;
			$count += $row['price'];
		}

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
		//$fields = self::getFields($post['table'], $post['action']);

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



	// Конструктор запросов для типа DELETE
	public static function delete($post)
	{	
		BaseModel::connection();
		$sql = self::preDelete($post);
		$result = self::$pdo->exec($sql);
		return ['sql' => $sql, 'result' => $result];
	}

	// Удаление данные из Любой таблицы по любым полям
	protected static function preDelete($post)
	{
		//$fields = self::getFields($post['table'], $post['action']);
		$fields = self::getFields($post['table']);

		$sql = "DELETE FROM `{$post['table']}` WHERE ";

		$keys = array_keys($post);
		$count = count($fields);
		
		$count_y = count($keys);
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
		for ($i=0; $i < $count; $i++) 
		{ 
			for ($y=0; $y < $countName; $y++) 
			{ 
				if ($fields[$i]['Field'] == $names[$y]) {
					unset($fields[$i]);
				}
			}
		}

		foreach ($fields as $key) {
			$mass[] = $key;
		}
		$fields = $mass;




		$fields = self::clear($fields);

		$count_i = count($fields);
		for ($i=0; $i < $count; $i++) 
		{ 
			echo $fields[$i]['Type'];
			if ($fields[$i]['Type'] == 'int') 
			{
				
				$sql .= " {$fields[$i]['Field']} = ";

				for ($y=0; $y < $count_y; $y++) { 
					if ($keys[$y] == $fields[$i]['Field']) 
					{
						$sql .= " {$post[$keys[$y]]} ";
					}
				}

			}elseif($fields[$i]['Type'] == 'varchar'){
				$sql .= " {$fields[$i]['Field']} = ";

				for ($y=0; $y < $count_y; $y++) { 
					if ($keys[$y] == $fields[$i]['Field']) 
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

		$sql .=" ;";

		return $sql;
	}
	




	// Конструктор запросов для типа SELECT
	public static function select($post)
	{
		BaseModel::connection();
		echo $sql = self::preSelect($post);
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
		//$fields = self::getFields($post['table'], $post['action']);
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

	


	// Конструктор запросов для типа UPDATE
	public static function update($post)
	{
		BaseModel::connection();
		$sql = self::preUpdate($post);
		$result = self::$pdo->exec($sql);

		return ['sql' => $sql, 'result' => $result];
	}


	// Изменение любой записи в Таблице
	protected static function preUpdate($post)
	{
		//$fields = self::getFields($post['table'], $post['action']);
		$fields = self::getFields($post['table']);

		$sql = "UPDATE `{$post['table']}` SET ";

		//Очистка $fields от пустых значений
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

		// BВзять из массива $post элементы и создать массив $SET
			$keys = array_keys($post);
			$countKeys = count($keys);
			for ($i=0; $i < $countKeys; $i++) 
			{ 
				if (strstr($keys[$i], 'set_')) 
				{
					$set[] = str_replace('set_', '', $keys[$i]);
				}
			}

		// BВзять из массива $post элементы и создать массив $WHERE
			$keys = array_keys($post);
			$countKeys = count($keys);
			for ($i=0; $i < $countKeys; $i++) 
			{ 
				if (strstr($keys[$i], 'where_')) 
				{
					$where[] = str_replace('where_', '', $keys[$i]);
				}
			}


		$fields = self::clear($fields);

		// SET
		$countFields = count($fields);
		$countSelect = count($set);
		$count_i = count($set);//
		for ($i=0; $i < $countFields; $i++) 
		{

			for ($y=0; $y < $countFields; $y++) 
			{ 
				if ($set[$i] == $fields[$y]['Field']) 
				{
					$s = $post['set_'.$fields[$y]['Field']];

					if ('varchar' == $fields[$y]['Type']) {
						$sql .= " {$fields[$y]['Field']} = '{$s}'";
					}elseif('int' == $fields[$y]['Type']){
						$sql .= " {$fields[$y]['Field']} = {$s}";
					}
				}
			}

			if ($count_i != 1)
			{
				$sql .= ",";
				$count_i --;
			}
		}

		$sql .= " WHERE ";

		// WHERE
		$countFields = count($fields);
		$countWhere = count($where);
		$count_i = count($where);

		for ($i=0; $i < $countWhere; $i++) 
		{
			for ($y=0; $y < $countFields; $y++) 
			{ 

				if ($where[$i] == $fields[$y]['Field']) 
				{
					$s = $post['where_'.$fields[$y]['Field']];
					if ('varchar' == $fields[$y]['Type']) {
						$sql .= " {$fields[$y]['Field']} = '{$s}'";
					}elseif('int' == $fields[$y]['Type']){
						$sql .= " {$fields[$y]['Field']} = {$s}";
					}
				}
			}

			if ($count_i != 1) {
				$sql .= " AND ";
				$count_i --;
				
			}
		}

		return $sql;
	}
}

