
<form action="" method="post">
	<input type="hidden" name="action" value="delete">
	<input type="hidden" name="table" value="Buy">

	<h4>Удалить данные о покупке</h4>
	<h5>Будет удалена запись по выбранным вам пунктам</h5>

	<p>
		<label for="id">Введите Номер :</label><br>
		<input type="text" name="id">
	</p>

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
		<input type="submit" name="Buy_delete">
	</p>
</form>
