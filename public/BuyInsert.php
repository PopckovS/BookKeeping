
<form action="" method="post">
	<input type="hidden" name="action" value="insert">
	<input type="hidden" name="table" value="Buy">
	<input type="hidden" name="id" value="null">

	<h4>Внести данныен о покупке</h4>
	<p>
		<label for="day">Введите день :</label><br>
		<input type="text" name="day">
	</p>

	<p>
		<label for="name">Название покупки :</label><br>
		<input type="text" name="name">
	</p>

	<p>
		<label for="price">Цена :</label><br>
		<input type="text" name="price">
	</p>

	<p>
		<label for="count">Количество :</label><br>
		<input type="text" name="count" value="1">
	</p>

	<p>
		<label for="place">Место покупки :</label><br>
		<input type="text" name="place">
	</p>

	<p>
		<label for="expenditure">Это стоило покупать ?</label><br>
		<select name="expenditure">
			<option>yes</option>
			<option>no</option>
		</select>
	</p>

	<p>
		<input type="submit" name="Buy_insert">
	</p>
</form>