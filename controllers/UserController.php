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


/**
 * [AJAX авторизация пользователя]
 * @return [json] [массив данных пользователя]
 */
function loginAction(){
	$email = isset($_REQUEST['loginEmail']) ? $_REQUEST['loginEmail'] : null;
	$email = trim($email);
	$pwd = isset($_REQUEST['loginPwd']) ? $_REQUEST['loginPwd'] : null;
	$pwd = trim($pwd);

	$userData = loginUser($email, $pwd);

	if ($userData['success']) {
		$userData = $userData[0];

		$_SESSION['user'] = $userData;
		$_SESSION['user']['displayName'] = $userData['name'] ? $userData['name'] : $userData['email'];

		$resData = $_SESSION['user'];
		$resData['success'] = true;
	} else {
		$resData['success'] = false;
		$resData['message'] = 'Неверный логин или пароль';
	}

	echo json_encode($resData);
}



/**
 * [формирование главной страницы пользователя (/user/)]
 * @param  [type] $smarty [шаблонизатор]
 * @return [type]         [description]
 */
function indexAction($smarty){
// d($_SESSION);
	// если пользователь не залогинен, то редирект на главную страницу
	if (!isset($_SESSION['user'])) {
		redirect();
	}

	// получение всех категорий с дочерними категориями для меню
	$rsCategories = getAllMainCatsWithChildren();

	$smarty->assign('pageTitle', 'Страница пользователя');
	$smarty->assign('rsCategories', $rsCategories);

	loadTemplate($smarty, 'header');
	loadTemplate($smarty, 'user');
	loadTemplate($smarty, 'footer');

	// d($smarty->getTemplateVars());
}


/**
 * [обновление данных пользователя]
 * @return [json] [результаты выполнения функции]
 */
function updateAction(){



	// если пользователя не залогинен, то выходим
	if (!isset($_SESSION['user'])) {
		redirect();
	}




	// инициализация переменных
	$resData = array();

	$phone = isset($_POST['newPhone']) ? htmlspecialchars($_POST['newPhone']) : null;
	$adress = isset($_POST['newAdress']) ? htmlspecialchars($_POST['newAdress']) : null;
	$name = isset($_POST['newName']) ? htmlspecialchars($_POST['newName']) : null;
	$pwd1 = isset($_POST['newPwd1']) ? $_POST['newPwd1'] : null;
	$pwd2 = isset($_POST['newPwd2']) ? $_POST['newPwd2'] : null;
	$curPwd = isset($_POST['curPwd']) ? $_POST['curPwd'] : null;




	// проверка правильности пароля (введенный и тот под которым залогинились
	$curPwdMD5 = md5($curPwd);
	if (!$curPwd || ($_SESSION['user']['pwd'] != $curPwdMD5)) {
		$resData['success'] = false;
		$resData['message'] = 'Текущий пароль не верный';
		echo json_encode($resData);
		return false;
	}


// d($_POST,0);


	// обновление данных пользователя
	$res = updateUserData($name, $phone, $adress, $pwd1, $pwd2, $curPwdMD5);



d($_SESSION,0);
	if ($res) {
		$resData['success'] = true;
		$resData['message'] = 'Данные сохранены';
		$resData['userName'] = $name;

		$_SESSION['user']['name'] = $name;
		$_SESSION['user']['phone'] = $phone;
		$_SESSION['user']['adress'] = $adress;
		$_SESSION['user']['pwd'] = $curPwdMD5;
		$_SESSION['user']['displayName'] = $name ? $name : $_SESSION['user']['email'];
	} else {
		$resData['success'] = false;
		$resData['message'] = 'Ошибка сохранения данных';
	}
	d($_SESSION,0);
	d($curPwdMD5,0);
	d($resData,1);
	echo json_encode($resData);
}