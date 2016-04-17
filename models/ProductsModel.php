<?php 

/**
 * модель для таблицы продукции (products)
 */

/**
 * [получаем последние добавленные товары]
 * @param  [type] $limit [лимит товаров]
 * @return [type]        [массив товаров]
 */
function getLastProducts($limit = null){
	global $mysqli;

	$sql = "SELECT * FROM `products` ORDER BY `id` DESC";
	
	if($limit){
		$sql .= " LIMIT {$limit}";
	}

	$rs = $mysqli->query($sql);
	return createSmartyRsArray($rs);
}


/**
 * [получить продукты для категории $itemId]
 * @param  [type] $itemId [Id категории]
 * @return [type]         [массив продуктов]
 */
function getProductsByCat($itemId){
	global $mysqli;

	$itemId = intval($itemId);
	$sql = "SELECT * FROM `products` WHERE `category_id` = '{$itemId}' AND `status` = 1";

	$rs = $mysqli->query($sql);
	return createSmartyRsArray($rs);
}


/**
 * [получить данные продукта по ID]
 * @param  [type] $itemId [ID продукта]
 * @return [type]         [массив данных продукта]
 */
function getProductById($itemId){
	global $mysqli;

	$itemId = intval($itemId);
	$sql = "SELECT * FROM `products` WHERE `id` = '{$itemId}'";

	$rs = $mysqli->query($sql);
	return $rs->fetch_assoc();
}


/**
 * [получить список продуктов из массива идентификаторов (ID's)]
 * @param  [type] $itemIds [массив идентификаторов продуктов]
 * @return [type]          [массив данных продуктов]
 */
function getProductsFromArray($itemIds){
	global $mysqli;

	$strIds = implode($itemIds, ', ');
	$sql = "SELECT * FROM `products` WHERE `id` IN ({$strIds})";

	$rs = $mysqli->query($sql);
	return createSmartyRsArray($rs);
}