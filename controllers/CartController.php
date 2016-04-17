<?php 

/**
 * CartController.php
 * контроллер работы с корзиной (/cart/)
 */

// подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';


/**
 * [формирование страницы корзины (/cart/)]
 * @param  [type] $smarty [шаблонизатор]
 * @return [type]         [description]
 */
function indexAction($smarty){
	$itemIds = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

	// получение всех категорий с дочерними категориями
	$rsCategories = getAllMainCatsWithChildren();
	// получение всех продуктов в корзине
	$rsProducts = getProductsFromArray($itemIds);

	$smarty->assign('pageTitle', 'Корзина');
	$smarty->assign('rsCategories', $rsCategories);
	$smarty->assign('rsProducts', $rsProducts);

	loadTemplate($smarty, 'header');
	loadTemplate($smarty, 'cart');
	loadTemplate($smarty, 'footer');
}



/**
 * [добавление продукта в корзину]
 * @param  [type] $id [GET параметр - ID добавляемого продукта]
 * @return [type]     [json информация об операции (успех, кол-во элементов в корзине)]
 */
function addtocartAction($id){
	$itemId = isset($_GET['id']) ? intval($_GET['id']) : null;
	if(!$itemId) return false;

	$resData = array();

	// если значение не найдено, то добавляем
	if (isset($_SESSION['cart']) && array_search($itemId, $_SESSION['cart']) === false) {
		$_SESSION['cart'][] = $itemId;
		$resData['cntItems'] = count($_SESSION['cart']);
		$resData['success'] = 1;
	} else {
		$resData['success'] = 0;
	}

	echo json_encode($resData);
}


/**
 * удаление продукта из корзины
 *
 * @param  [type] $id [GET параметр - ID удаляемого из корзины продукта]
 * @return [type]     [json информация об операции (успех, кол-во элементов в корзине)]
 */
function removefromcartAction () {
	$itemId = isset($_GET['id']) ? intval($_GET['id']) : null;
	if(!$itemId) exit();

	$resData = array();

	// если значение не найдено, то добавляем
	$key = array_search($itemId, $_SESSION['cart']);

	if ($key !== false) {
		unset($_SESSION['cart'][$key]);
		$resData['cntItems'] = count($_SESSION['cart']);
		$resData['success'] = 1;
	} else {
		$resData['success'] = 0;
	}

	echo json_encode($resData);
}