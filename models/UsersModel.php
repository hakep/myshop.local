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
			$rs['success'] = true;
		} else {
			$rs['success'] = false;
		}
	} else {
		$rs['success'] = false;
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

	if ($pwd1 !== $pwd2) {
		$res['success'] = false;
		$res['message'] = 'Пароли не совпадают';
	}
	if (!$pwd2) {
		$res['success'] = false;
		$res['message'] = 'Введите повтор пароля';
	}
	if (!$pwd1) {
		$res['success'] = false;
		$res['message'] = 'Введите пароль';
	}
	if (!$email) {
		$res['success'] = false;
		$res['message'] = 'Введите email';
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



/**
 * [авторизация пользователя]
 * @param  [type] $email [почта (логин)]
 * @param  [type] $pwd   [пароль]
 * @return [type]        [данные пользователя]
 */
function loginUser($email, $pwd){
	global $mysqli;

	$email = htmlspecialchars($mysqli->real_escape_string($email));
	$pwdMD5 = md5($pwd);

	$sql = "SELECT * FROM `users` WHERE (`email` = '{$email}' AND `pwd` = '{$pwdMD5}') LIMIT 1";
	$rs = $mysqli->query($sql);
	$rs = createSmartyRsArray($rs);

	if (isset($rs[0])) {
		$rs['success'] = true;
	} else {
		$rs['success'] = false;
	}

	return $rs;
}



/**
 * [изменение данных пользователя]
 * @param  [type] $name   [имя пользователя]
 * @param  [type] $phone  [телефон пользователя]
 * @param  [type] $adress [адрес пользователя]
 * @param  [type] $pwd1   [новый пароль]
 * @param  [type] $pwd2   [повтор нового пароля]
 * @param  [type] $curPwd [текущий пароль]
 * @return [type]         [TRUE в случае успеха]
 */
function updateUserData($name, $phone, $adress, $pwd1, $pwd2, $curPwd){
	global $mysqli;

	$email = htmlspecialchars($mysqli->real_escape_string($_SESSION['user']['email']));
	$name = htmlspecialchars($mysqli->real_escape_string($name));
	$phone = htmlspecialchars($mysqli->real_escape_string($phone));
	$adress = htmlspecialchars($mysqli->real_escape_string($adress));
	$pwd1 = trim($pwd1);
	$pwd2 = trim($pwd2);

	$newPwd = null;
	if($pwd1 && ($pwd1 == $pwd2)){
		$newPwd = md5($pwd1);
	}

	$sql = "1UPDATE users SET ";

	if ($newPwd) {
		$sql .= "`pwd` = '{$newPwd}', ";
	}

	$sql .= "`name` = '{$name}', `phone` = '{$phone}', `adress` = '{$adress}' WHERE `email` = '{$email}' AND `pwd` = '{$curPwd}' LIMIT 1";

	$rs = $mysqli->query($sql);

	return $rs;
}