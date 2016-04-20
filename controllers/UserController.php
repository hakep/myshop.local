<?php 

/**
 * UserController.php
 * контроллер функций пользователя
 */

// подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/UsersModel.php';

/**
 * [AJAX регистрация пользователя]
 * [Инициализация сессионной переменной ($_SESSION['user'])]
 * @return [type] [массив данных нового пользователя]
 */
function registerAction(){

	$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
	$email = trim($email);

	$pwd1 = isset($_REQUEST['pwd1']) ? $_REQUEST['pwd1'] : null;
	$pwd2 = isset($_REQUEST['pwd2']) ? $_REQUEST['pwd2'] : null;

	$phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : null;
	$adress = isset($_REQUEST['adress']) ? $_REQUEST['adress'] : null;
	$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;
	$name = trim($name);


	$resData = null;
	// если все введено, то $resData равна null, иначе описание ошибки
	$resData = checkRegisterParams($email, $pwd1, $pwd2);

	// если все данные введены, но такой пользователь уже есть в базе, то ошибка
	if (!$resData && checkUserEmail($email)) {
		$resData['success'] = false;
		$resData['message'] = "Пользователь с таким email ({$email}) уже зарегистрирован";
	}

	if (!$resData) {
		$pwdMD5 = md5($pwd1);

		$userData = registerNewUser($email, $pwdMD5, $name, $phone, $adress);

		if ($userData['success']) {
			$resData['message'] = 'Пользователь успешно зарегистрирован';
			$resData['success'] = true;

			$userData = $userData[0];
			$resData['userName'] = $userData['name'] ? $userData['name'] : $userData['email'];
			$resData['userEmail'] = $email;

			$_SESSION['user'] = $userData;
			$_SESSION['user']['displayName'] = $userData['name'] ? $userData['name'] : $userData['email'];
		} else {
			$resData['success'] = false;
			$resData['message'] = 'Ошибка регистрации';
		}
	}
	echo json_encode($resData);
}


/**
 * [разлогинивание пользователя]
 * @return [type] [description]
 */
function logoutAction(){
	if (isset($_SESSION['user'])) {
		unset($_SESSION['user']);
		unset($_SESSION['cart']);
	}

	redirect();
}