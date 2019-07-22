<!-- Основной Дефолтный шаблон для отображения страницы -->
<?php use core\Router;?>

<!DOCTYPE html>
<html lang="en" >
<head>
    <title>BookKeeping</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" type="text/css" href="style.css" >   
</head>   
<body>


<header>
  <?php  require_once 'public/header.php'; ?>
</header>


<div id="content">
	<?php 
	//Создание обьекта класса Роутера и передача ему всех переменных типа $_GET 
	Router::dispatch($_SERVER['QUERY_STRING']); 
	?>
</div>


</body>
</html>




<style type="text/css">
	
  body{
  }
  header{
    position: center;
  }
  header ul li{
    float: left;
    font-family: sans-serif;
    text-align: center;
    line-height: 50px;
    height: 50px;
    width: 200px;
    list-style: none;
  }
  header ul li a{
    transition: 0,5s;
    color: #EA6915;
    text-decoration: none;
  }
  header ul li a:hover{
    text-decoration:underline;
  }
  #content{
    float: left;
    border-radius: 6px;
    background-color: white;
    width: 90%;
    margin-top: 20px;
    margin-left: 5%;
    min-height: 500px;
    height: auto;
    padding-bottom: 30px;
  }

  /* Базовые стили формы */
/*

*/
</style>