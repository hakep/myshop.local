<?php 

/**
 * модель для таблицы пользователей (users)
 */


/**
 * [регистрация нового пользователя]
 * @param  [type] $email  [почта]
 * @param  [type] $pwdMD5 [пароль зашифрованный в MD5]
 * @param  [type] $name   [имя пользователя]
 * @param  [type] $phone  [телефон]
 * @param  [type] $adress [адрес пользователя]
 * @return [type]         [массив данных нового пользователя]
 */
function registerNewUser($email, $pwdMD5, $name, $phone, $adress){
	global $mysqli;

	$email = htmlspecialchars($mysqli->real_escape_string($email));
	$name = htmlspecialchars($mysqli->real_escape_string($name));
	$phone = htmlspecialchars($mysqli->real_escape_string($phone));
	$adress = htmlspecialchars($mysqli->real_escape_string($adress));

	$sql = "INSERT INTO `users` (`email`, `pwd`, `name`, `phone`, `adress`) VALUE ('{$email}', '{$pwdMD5}', '{$name}', '{$phone}', '{$adress}')";
	$rs = $mysqli->query($sql);

	if ($rs) {
		$sql = "SELECT * FROM `users` WHERE (`email` = '{$email}' AND `pwd` = '{$pwdMD5}') LIMIT 1";
		$rs = $mysqli->query($sql);
		$rs = createSmartyRsArray($rs);

		if (isset($rs[0])) {
			$rs['success'] = 1;
		} else {
			$rs['success'] = 0;
		}
	} else {
		$rs['success'] = 0;
	}

	return $rs;
}


/**
 * [проверка параметров для регистрации пользователя]
 * @param  [type] $email [email]
 * @param  [type] $pwd1  [пароль]
 * @param  [type] $pwd2  [повтор пароля]
 * @return [type]        [рузульта]
 */
function checkRegisterParams($email, $pwd1, $pwd2){
	$res = null;

	if (!$pwd2) {
		$res['success'] = false;
		$res['massage'] = 'Введите повтор пароля';
	}
	if (!$pwd1) {
		$res['success'] = false;
		$res['massage'] = 'Введите пароль';
	}
	if (!$email) {
		$res['success'] = false;
		$res['massage'] = 'Введите email';
	}

	return $res;
}


/**
 * [проверка почты (есть ли email в БД)]
 * @param  [type] $email [description]
 * @return [type]        [строка из таблицы users, либо пустой массив]
 */
function checkUserEmail($email){
	global $mysqli;

	$email = $mysqli->real_escape_string($email);
	$sql = "SELECT `id` FROM `users` WHERE `email` = '{$email}'";
	$rs = $mysqli->query($sql);
	$rs = createSmartyRsArray($rs);

	return $rs;
}