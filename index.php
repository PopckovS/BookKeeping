<?php 
use core\Router;
use libs\Errors;

session_start();

require_once 'libs/phpExcel/PHPExcel/Classes/PHPExcel.php';

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


getExcelFile();/* Проверка отправки файла Excel на скачавание */
/* ЗАПУСК САМОГО САЙТА ТУТ ! */
require_once 'public/default.php';


/* Функция для вывода данных в Excel файл */
function getExcel($array)
{
  	//d($array);
  	$phpexcel = new PHPExcel(); // Создаём объект PHPExcel
  	 /* Каждый раз делаем активной 1-ю страницу и получаем её, потом записываем в неё данные */
  	$page = $phpexcel->setActiveSheetIndex(0); // Делаем активной первую страницу и получаем её

	$date = getdate();
	$today = $date['year'].'-'.$date['mon'].'-'.$date['mday'];
	$phpexcel->setActiveSheetIndex(0)->setCellValue("A1", 'Номер');
	$phpexcel->setActiveSheetIndex(0)->setCellValue("B1", 'День');
	$phpexcel->setActiveSheetIndex(0)->setCellValue("C1", 'Название');
	$phpexcel->setActiveSheetIndex(0)->setCellValue("D1", 'Цена');
	$phpexcel->setActiveSheetIndex(0)->setCellValue("E1", 'Количество');
	$phpexcel->setActiveSheetIndex(0)->setCellValue("F1", 'Место');
	$phpexcel->setActiveSheetIndex(0)->setCellValue("G1", 'Траты');

	$i = 1;
	foreach($array as $val)
	{
		$i++;
		$phpexcel->setActiveSheetIndex(0)->setCellValue("A$i", $val[id]);
		$phpexcel->setActiveSheetIndex(0)->setCellValue("B$i", $val[day]);
		$phpexcel->setActiveSheetIndex(0)->setCellValue("C$i", $val[name]);
		$phpexcel->setActiveSheetIndex(0)->setCellValue("D$i", $val[price]);
		$phpexcel->setActiveSheetIndex(0)->setCellValue("E$i", $val[count]);
		$phpexcel->setActiveSheetIndex(0)->setCellValue("F$i", $val[place]);
		$phpexcel->setActiveSheetIndex(0)->setCellValue("G$i", $val[expenditure]);
	}

  	$page->setTitle("Example"); // Заголовок делаем "Example"
  	/* Начинаем готовиться к записи информации в xlsx-файл */
  	$objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
  	/* Записываем в файл */
  	$name = "tmpExcel/Excel-".$today.".xlsx";
  	$objWriter->save($name);

  	echo "<a href='index.php?filename={$name}'>Скачать в формате файла Excle</a>";
}


 function getExcelFile()
	{
		if ($_GET['filename']) 
		{
			
		$name = $_GET['filename'];

		header("Pragma: public"); 
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); // нужен для некоторых браузеров
		header("Content-Type: $ctype");
		header("Content-Disposition: attachment; filename=\"".basename($name)."\";" );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($name)); // необходимо доделать подсчет размера файла по абсолютному пути
		readfile($name);
		unlink($name);
		
		}

		//header('Location:index.php?method=Show&action=select');
		

	}


function d($array)
{
	echo "<pre>".print_r($array,1)."</pre>";
}