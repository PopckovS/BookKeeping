
<form action="" method="post">
	<input type="hidden" name="action" value="select">
	<input type="hidden" name="table" value="Buy">

	<h4>Выбрать данные с условиями из таблицы Покупки</h4>

	<p>Выбрать все : 
	<select name="type_select" >
		<option></option>
		<option>all</option>
	</select>
	</p>

	<p>
		<label for="id">Номер :</label><br>
		<input type="text" name="id" value="" >
		Выбрать : <input type='checkbox' name="select_id" value="id" class='checkbox'>
	</p>

	<p>
		<label for="day">Введите день в формате (2019-7-12):</label><br>
		<input type="text" name="day" value="" >
		Выбрать : <input type='checkbox' name="select_day" value="day" class='checkbox'>
	</p>

	<p>
		<label for="name">Название покупки :</label><br>
		<input type="text" name="name">
		Выбрать : <input type='checkbox' name="select_name" value="name" class='checkbox'>
	</p>

	<p>
		<label for="price">Цена :</label><br>
		<input type="text" name="price">
		Выбрать : <input type='checkbox' name="select_price" value="price" class='checkbox'>
	</p>

	<p>
		<label for="count">Количество :</label><br>
		<input type="text" name="count">
		Выбрать : <input type='checkbox' name="select_count" value="count" class='checkbox'>
	</p>

	<p>
		<label for="place">Место покупки :</label><br>
		<input type="text" name="place">
		Выбрать : <input type='checkbox' name="select_place" value="place" class='checkbox'>
	</p>

	<p>
		<label for="expenditure">Это стоило покупать ?</label>
		<select name="expenditure">
			<option></option>
			<option>yes</option>
			<option>no</option>
		</select><br>
		Выбрать : <input type='checkbox' name="select_expenditure" value="expenditure" class='checkbox'></p>
	</p>

	<p>
		<button name="Buy_select">Готово</button>
	</p>
</form>




<style>

</style>

