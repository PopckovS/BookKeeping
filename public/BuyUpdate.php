
<form action="" method="post">
	<input type="hidden" name="action" value="update">
	<input type="hidden" name="table" value="Buy">

	<h4>Изменить запись о покупке</h4>
	<p>
		<label for="day">Внести День :</label><br>
		<input type="text" name="set_day"><br>
		<label for="day">Куда внести :</label><br>
		<input type="text" name="where_day">
	</p><br>

	<p>
		<label for="name">Внести Название покупки :</label><br>
		<input type="text" name="set_name"><br>
		<label for="name">Куда внести :</label><br>
		<input type="text" name="where_name">
	</p><br>

	<p>
		<label for="price">Внести цену :</label><br>
		<input type="text" name="set_price"><br>
		<label for="price">Куда внести :</label><br>
		<input type="text" name="where_price">
	</p><br>

	<p>
		<label for="place">Внести место покупки :</label><br>
		<input type="text" name="set_place"><br>
		<label for="place">Куда внести :</label><br>
		<input type="text" name="where_place">
	</p><br>

	<p>
		<label for="expenditure">Внести расходы</label><br>
		<select name="set_expenditure">
			<option></option>
			<option>yes</option>
			<option>no</option>
		</select><br>
		<label for="expenditure">Куда внести</label><br>
		<select name="where_expenditure">
			<option></option>
			<option>yes</option>
			<option>no</option>
		</select>
	</p><br>

	<p>
		<input type="submit" name="Buy_update">
	</p>
</form>