
<p>В поля введите те значения по которым будет вестись поиск записей, 
галачками нужно отметить какие именно поля должны быть выбраны из записей.</p>

<form action="" method="post">
	<input type="hidden" name="action" value="select">
	<input type="hidden" name="table" value="Buy">

	<h4>Выбрать данные из таблицы Покупки</h4>

	<p><label for="type_select">Выбрать </label>
	<select name="type_select" >
		<option></option>
		<option>all</option>
	</select>
	</p>

	<p>
		<label for="id">Номер </label><br>
		<input type="text" name="id" value="" >
		<input type='checkbox' name="select_id" value="id" class='checkbox'>
	</p>

	<p>
		<label for="day">Введите день в формате (2019-7-12)</label><br>
		<input type="text" name="day" value="" >
		<input type='checkbox' name="select_day" value="day" class='checkbox'>
	</p>

	<p>
		<label for="name">Название покупки </label><br>
		<input type="text" name="name">
		<input type='checkbox' name="select_name" value="name" class='checkbox'>
	</p>

	<p>
		<label for="price">Цена </label><br>
		<input type="text" name="price">
		<input type='checkbox' name="select_price" value="price" class='checkbox'>
	</p>

	<p>
		<label for="count">Количество </label><br>
		<input type="text" name="count">
		<input type='checkbox' name="select_count" value="count" class='checkbox'>
	</p>

	<p>
		<label for="place">Место покупки </label><br>
		<input type="text" name="place">
		<input type='checkbox' name="select_place" value="place" class='checkbox'>
	</p>


	<p>
		<label for="expenditure">Это стоило покупать ?</label>
		<input type='checkbox' name="select_expenditure" value="expenditure" class='checkbox'></p>
		<select name="expenditure">
			<option></option>
			<option>yes</option>
			<option>no</option>
		</select><br>

		
		
	</p>


	<p>
		<button name="Buy_select">Готово</button>
	</p>
</form>














<style>
:-moz-placeholder {
    color: #7e7e7e;
}

::-webkit-input-placeholder {
    color: #7e7e7e;
}

div {
    clear: both;
    padding: 8px 0;
    overflow: hidden;
}

form {
	border-radius: 4px;
    padding: 20px;
    font-family: Arial;
    font-size: 13px;
    display: inline-block;
	background-color: #37393c;
    color: white;
}
form h4{
	text-align: center;
	font-family: Arial;
}
label {
    padding-top: 6px;
    line-height: 18px;
    float: left;
    color: #b6daff;
    margin-right: 7px;
    
}

input,select,textarea {
    display: inline-block;
    width: 250px;
    height: 18px;
    padding: 4px;
    line-height: 18px;
    color: #efefef;
    border: 1px solid #a4a4a4;
	background-color: #202223;
	-webkit-transition: border linear 0.2s, box-shadow linear 0.2s;
	-moz-transition: border linear 0.2s, box-shadow linear 0.2s;
	-ms-transition: border linear 0.2s, box-shadow linear 0.2s;
	-o-transition: border linear 0.2s, box-shadow linear 0.2s;
	transition: border linear 0.2s, box-shadow linear 0.2s;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}


input:focus,select:focus,textarea:focus {
    border: 1px solid #97b7d9;
    outline: none;
	-webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.5), 0 0 8px rgba(151, 183, 217, 0.6);
	-moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.5), 0 0 8px rgba(151, 183, 217, 0.6);
	box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.5), 0 0 8px rgba(151, 183, 217, 0.6);
}

select {
    width: 60px;
    height: auto;
}

textarea {
    height: auto;
}

button {
	/**/
	margin-left: 35%;
	/**/
    display: inline-block;
    width: auto;
    height: auto;
	background-color: #3d6182;
    background-repeat: repeat-x;
    background-image: -khtml-gradient(linear, left top, left bottom, from(#8db7dd), to(#3d6182));
    background-image: -moz-linear-gradient(top, #8db7dd, #3d6182);
    background-image: -ms-linear-gradient(top, #8db7dd, #3d6182);
    background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #8db7dd), color-stop(100%, #3d6182));
    background-image: -webkit-linear-gradient(top, #8db7dd, #3d6182);
    background-image: -o-linear-gradient(top, #8db7dd, #3d6182);
    background-image: linear-gradient(top, #8db7dd, #3d6182);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#8db7dd', endColorstr='#3d6182', GradientType=0);
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    border: 1px solid #19252f;
    padding: 8px 17px 9px;
    color: white;
    cursor: pointer;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	-webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
    -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
    -webkit-transition: 0.1s linear all;
    -moz-transition: 0.1s linear all;
    -ms-transition: 0.1s linear all;
    -o-transition: 0.1s linear all;
    transition: 0.1s linear all;
}

button[type=reset] {
	background-color: #292929;
    background-repeat: repeat-x;
    background-image: -khtml-gradient(linear, left top, left bottom, from(#646464), to(#292929));
    background-image: -moz-linear-gradient(top, #646464, #292929);
    background-image: -ms-linear-gradient(top, #646464, #292929);
    background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #646464), color-stop(100%, #292929));
    background-image: -webkit-linear-gradient(top, #646464, #292929);
    background-image: -o-linear-gradient(top, #646464, #292929);
    background-image: linear-gradient(top, #646464, #292929);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#646464', endColorstr='#292929', GradientType=0);
	border: 1px solid #161616;
}

button:hover,button[type=reset]:hover {
    background-position: 0 -15px;
}

input[type=checkbox],input[type=radio] {
    width: 20px;
    height: 20px;
    margin-top:5px;
    margin-left: 0;
    padding: 0;
    line-height: normal;
    float: left;
    border: none;
}

input[type=file] {
	background-color: #37393c;
    height: 27px;
    border: none;
}

input[type=file]:focus, input[type=checkbox]:focus, select:focus {
    outline: 1px dotted #666;
}

input[disabled] {
    background-color: #343536;
    border-color: #585858;
    cursor: not-allowed;
}

button::-moz-focus-inner, input::-moz-focus-inner {
    border: 0;
    padding: 0;
}

</style>
