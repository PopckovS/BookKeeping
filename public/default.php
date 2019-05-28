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
	<?php 
	require_once 'public/header.php'
	?>
</header>
<div id="content">
	<?php 
	require_once 'public/header.php';
	//Создание обьекта класса Роутера и передача ему всех переменных типа $_GET 
	Router::dispatch($_SERVER['QUERY_STRING']); 
	?>
</div>

</body>
</html>




<style type="text/css">
	
  body{
    background-color: #12161C;
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
    padding-top: 20px;
    float: left;
    border-radius: 6px;
    background-color: white;
    width: 90%;
    margin-top: 50px;
    margin-left: 5%;
    min-height: 500px;
    height: auto;
    padding-bottom: 30px;
  }

  /* Базовые стили формы */
form{
    margin:0 auto;
  
  border-radius:5px; 
  background:RGBA(255,255,255,1);
  -webkit-box-shadow:  0px 0px 15px 0px rgba(0, 0, 0, .45);        
  box-shadow:  0px 0px 15px 0px rgba(0, 0, 0, .45); 
  width: 400px; 
  border: 1px solid #12161C;
}
form h4{
    font-family: 'Open Sans', sans-serif;
  text-align: center;
}
/* Стили для названий label */
form label{
  margin-left: 5%;
  font-family: 'Open Sans', sans-serif;
  font-size:16px; 
  color: #757575;
}
form input{
  
  height: 18px;
  margin-left: 5%;
    width: 90%;
}
form button{
  cursor: pointer;
  width: 16%;
  text-align: center;
  line-height: 30px;
  height: 30px;
  margin-left: 42%;
}

</style>