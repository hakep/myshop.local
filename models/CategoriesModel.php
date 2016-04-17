<?php

/**
 * модель для таблицы категорий (catetories)
 */


/**
 * [получить дочернии категории для категории $catId]
 * @param  [type] $catId [ID категории]
 * @return [type]        [массив дочерних категорий]
 */
function getChildrenForCat($catId){
	global $mysqli;

	$sql = "SELECT * FROM `categories` WHERE `parent_id` = '{$catId}'";
	$rs = $mysqli->query($sql);

	return createSmartyRsArray($rs);
}



/**
 * [получить главные категории с привязками дочерних]
 * @return [type] [description]
 */
function getAllMainCatsWithChildren(){
	global $mysqli;

	$sql = "SELECT * FROM `categories` WHERE `parent_id` = 0";
	$rs = $mysqli->query($sql);

	$smartyRs = array();
	while ($row = $rs->fetch_assoc()) {

		$rsChildren = getChildrenForCat($row['id']);
		if ($rsChildren) {
			$row['children'] = $rsChildren;
		}

		$smartyRs[] = $row;
	}

	return $smartyRs;
}

/**
 * [получить данные категории по id]
 * @param  [type] $catId [id категории]
 * @return [type]        [строка категории]
 */
function getCatById($catId){
	global $mysqli;
	
	$catId = intval($catId);
	$sql = "SELECT * FROM `categories` WHERE `id` = '{$catId}'";
	$rs = $mysqli->query($sql);

	return $rs->fetch_assoc();
}