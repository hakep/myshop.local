<?php 
// основные функции

/**
 * [формирование запрашиваемой страницы]
 * @param  [type] $smarty         [сам smarty объект]
 * @param  [type] $controllerName [название контроллера]
 * @param  string $actionName     [название функции обработки страницы]
 * @return [type]                 [none]
 */
function loadPage($smarty, $controllerName, $actionName = 'index'){
	include_once PathPrefix . $controllerName . PathPostfix;
	$function = $actionName . 'Action';

	$function($smarty);
}


/**
 * [загрузка шаблона]
 * @param  [type] $smarty       [объект шаблонизатора]
 * @param  [type] $templateName [название файла шаблона]
 * @return [type]               [none]
 */
function loadTemplate($smarty, $templateName){
	$smarty->display($templateName . TemplatePostfix);
}


/**
 * [функция отладки. Останавливает работу программы выводя значение переменной value]
 * @param  [type]  $value [переменная для вывода ее на страницу]
 * @param  integer $die   [description]
 * @return [type]         [description]
 */
function d($value = null, $die = 1){
	global $start;
	$end = round(microtime(true) - $start, 4) * 10000;
	echo  "DEBUG: {$end} миллисекунд<pre>";
	print_r($value);
	echo '</pre>';

	if($die) die;
}

/**
 * [преобразование результата работы функции выборки в ассоциативный массив ]
 * @param  [type] $rs [набор строк - результат работы SELECT]
 * @return [type]     [description]
 */
function createSmartyRsArray($rs){
	global $mysqli;

	if (!$rs)	return false;

	$smartyRs = array();
    while ($row = $rs->fetch_assoc()) {
        $smartyRs[] = $row;
    }

	return $smartyRs;
}




function redirect($url='/'){
	header("Location: {$url}");
	exit();
}