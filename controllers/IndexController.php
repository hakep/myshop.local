<?php
/**
 * контроллер главной страницы
 */

// подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';

function testAction(){
	echo "IndexController.php > testAction";
}

/**
 * [формирование главной страницы сайта]
 * @param  [type] $smarty [шаблонизатор]
 * @return [type]         [description]
 */
function indexAction($smarty){

	// получение всех категорий с дочерними категориями
	$rsCategories = getAllMainCatsWithChildren();

	// получение необходимых товаров
	$rsProducts = getLastProducts(16);

	$smarty->assign('pageTitle', 'Главная страница сайта');
	$smarty->assign('rsCategories', $rsCategories);
	$smarty->assign('rsProducts', $rsProducts);

	loadTemplate($smarty, 'header');
	loadTemplate($smarty, 'index');
	loadTemplate($smarty, 'footer');
	
}