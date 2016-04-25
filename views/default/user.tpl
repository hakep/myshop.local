{* страница пользователя *}

<h1>Ваши регистрационные данные:</h1>

<table border="0" id="updateUserData">
	<tr>
		<td>Логин (email)</td>
		<td>{$arUser['email']}</td>
	</tr>
	<tr>
		<td>Имя</td>
		<td><input type="text" id="newName" name="newName" value="{$arUser['name']}"> </td>
	</tr>
	<tr>
		<td>Тел</td>
		<td><input type="text" id="newPhone" name="newPhone" value="{$arUser['phone']}"> </td>
	</tr>
	<tr>
		<td>Адрес</td>
		<td><textarea id="newAdress" name="newAdress" value="{$arUser['adress']}"></textarea></td>
	</tr>
	<tr>
		<td>Новый пароль</td>
		<td><input type="password" id="newPwd1" name="newPwd1" value=""> </td>
	</tr>
	<tr>
		<td>Повтор пароля</td>
		<td><input type="password" id="newPwd2" name="newPwd2" value=""> </td>
	</tr>
	<tr>
		<td>Для того чтобы сохранить данные введите текущий пароль</td>
		<td><input type="password" id="curPwd" name="curPwd" value=""> </td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="button" value="Сохранить изменения" onclick="updateUserData();"> </td>
	</tr>
</table>