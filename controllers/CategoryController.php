<?php
/**
 * контроллер страницы категории (/category/1)
 */

// подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';


/**
 * [формирование страницы категории]
 * @param  [type] $smarty [шаблонизатор]
 * @return [type]         [description]
 */
function indexAction($smarty){
	
	// определяем с какой подкатегорией (id) будем работать
	$catId = isset($_GET['id']) ? $_GET['id'] : null;
	if($catId == null) exit();

	$rsCategory = getCatById($catId);



	$rsChildCats = null;
	$rsProducts = null;
	// если главная категория то показываем дочернии категории,
	// иначе показываем товар 
	if($rsCategory['parent_id'] == 0){
		$rsChildCats = getChildrenForCat($catId);
	} else {
		$rsProducts = getProductsByCat($catId);
	}

	// получение всех категорий с дочерними категориями для меню
	$rsCategories = getAllMainCatsWithChildren();

	$smarty->assign('pageTitle', 'Товары категории ' . $rsCategory['name']);
	$smarty->assign('rsCategory', $rsCategory);
	$smarty->assign('rsCategories', $rsCategories);
	$smarty->assign('rsChildCats', $rsChildCats);
	$smarty->assign('rsProducts', $rsProducts);

	loadTemplate($smarty, 'header');
	loadTemplate($smarty, 'category');
	loadTemplate($smarty, 'footer');

}
