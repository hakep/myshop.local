<?php 

/**
 * контроллер страницы товара (/product/1)
 */

// подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';


/**
 * [формирование страницы продукта]
 * @param  [type] $smarty [шаблонизатор]
 * @return [type]         [description]
 */
function indexAction($smarty){
	// определяем с какой подкатегорией (id) будем работать
	$itemId = isset($_GET['id']) ? $_GET['id'] : null;
	if($itemId == null) exit();

	// получаем данные продукта
	$rsProduct = getProductById($itemId);

	// получение всех категорий с дочерними категориями
	$rsCategories = getAllMainCatsWithChildren();

	// содержится ли данный товар в корзине
	if(in_array($itemId, $_SESSION['cart'])){
		$smarty->assign('itemInCart', 1);
	} else {
		$smarty->assign('itemInCart', 0);
	}

	$smarty->assign('pageTitle', $rsProduct['name']);
	$smarty->assign('rsCategories', $rsCategories);
	$smarty->assign('rsProduct', $rsProduct);
	

	loadTemplate($smarty, 'header');
	loadTemplate($smarty, 'product');
	loadTemplate($smarty, 'footer');


}
