<?php

/**
 * модель для таблицы заказов (orders)
 */


/**
 * [создание заказа (без привязки товара)]
 * @param  [type] $name   [description]
 * @param  [type] $phone  [description]
 * @param  [type] $adress [description]
 * @return [type]         [ID созданного заказа]
 */
function makeNewOrder($name, $phone, $adress){
	global $mysqli;

	$name = $mysqli->real_escape_string($name);
	$phone = $mysqli->real_escape_string($phone);
	$adress = $mysqli->real_escape_string($adress);


	// инициализация переменных
	$userId = intval($_SESSION['user']['id']);
	$comment = "id пользователя: {$userId}<br>Имя: {$name}<br>Тел: {$phone}<br>Адрес: {$adress}";
	$dateCreated = date('Y.m.d H:i:s');
	$userIp = $_SERVER['REMOTE_ADDR'];

	// формирование запроса к базе данных
	$sql = "INSERT INTO orders (`user_id`, `date_created`, `date_payment`, `status`, `comment`, `user_ip`) 
		VALUES ('{$userId}', '{$dateCreated}', null, '0', '{$comment}', '{$userIp}')";

	$rs = $mysqli->query($sql);

	// получить id созданного заказа
	if ($rs) {
		return $mysqli->insert_id;
	} else {
		return false;
	}
}