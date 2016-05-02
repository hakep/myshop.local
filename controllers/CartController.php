<?php 

/**
 * CartController.php
 * контроллер работы с корзиной (/cart/)
 */

// подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';
include_once '../models/OrdersModel.php';
include_once '../models/PurchaseModel.php';

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
		$resData['success'] = true;
	} else {
		$resData['success'] = false;
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
		$resData['success'] = true;
	} else {
		$resData['success'] = false;
	}

	echo json_encode($resData);
}


/**
 * [формирование страницы заказа]
 * @return [type] [description]
 */
function orderAction($smarty){

	// получаем массив идентификаторов (ID) продуктов корзины
	$itemsIds = isset($_SESSION['cart']) ? $_SESSION['cart'] : null;

	// если корзина пуста, то редиректим в корзину
	if (!$itemsIds) {
		redirect('/cart/');
		return;
	}

	// получаем из массива $_POST количество покупаемых товаров
	$itemsCnt = array();
	foreach ($itemsIds as $item) {
		// формируем ключ для массива
		$postVar = 'itemCnt_' . $item;

		// создаем элемент массива количество покупаемого товара
		// ключ массива - ID товара, значение массива - количество товара
		// $itemsCnt[1] = 3; товар с ID == 1 покупают 3 штуки
		$itemsCnt[$item] = isset($_POST[$postVar]) ? $_POST[$postVar] : null;
	}

	// получаем список продуктов по массиву корзины
	$rsProducts = getProductsFromArray($itemsIds);

	// добавляем каждому продукту дополнительное поле
	// "realPrice = количество продуктов * на цену продукта"
	// "cnt" = количество покупаемого товара

	// &$item - для того чтобы при изменении переменной $item
	// менялся и элемент массива $rsProducts
	$i = 0;
	foreach ($rsProducts as &$item) {
		$item['cnt'] = isset($itemsCnt[$item['id']]) ? $itemsCnt[$item['id']] : 0;
		if ($item['cnt']) {
			$item['realPrice'] = $item['cnt'] * $item['price'];
		} else {
			// если вдруг получилось так, что товар в корзине есть, а количество равно нулю, то удаляем этот товар
			unset($rsProducts[$i]);
		}
		$i++;
	}

	if (!$rsProducts) {
		echo "Корзина пуста";
		return;
	}

	// полученный массив, покупаемых товаров помещаем в сессионную переменную
	$_SESSION['saleCart'] = $rsProducts;

	// получение всех категорий с дочерними категориями
	$rsCategories = getAllMainCatsWithChildren();

	// hideLoginBox переменная - флаг для того, чтобы спрятать блок логина и регистрации в боковой панели
	if(!isset($_SESSION['user'])){
		$smarty->assign('hideLoginBox', 1);
	}

	$smarty->assign('pageTitle', 'Данные заказа');
	$smarty->assign('rsCategories', $rsCategories);
	$smarty->assign('rsProducts', $rsProducts);
	
	loadTemplate($smarty, 'header');
	loadTemplate($smarty, 'order');
	loadTemplate($smarty, 'footer');
}



/**
 * [AJAX фунция сохранение заказа]
 * $_SESSION['saleCart'] массив покупаемых продуктов
 * @return [type] [информация о результате выполнения]
 */
function saveorderAction(){

	// получаем массив покупаемых товаров
	$cart = isset($_SESSION['saleCart']) ? $_SESSION['saleCart'] : null;
	// если корзина пуста, то формируем ответ с ошибкой, отдаем его в формате json и выходим из функции
	if (!$cart) {
		$resData['success'] = false;
		$resData['message'] = "Нет товаров для заказа";
		echo json_encode($resData);
		return;
	}

	$phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : null;
	$adress = isset($_POST['adress']) ? htmlspecialchars($_POST['adress']) : null;
	$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : null;
	

	// создаем новый заказ и получаем его ID
	$orderId = makeNewOrder($name, $phone, $adress);

	// если заказ не создан, то выдаем ошибку и завершаем функцию
	if (!$orderId) {
		$resData['success'] = false;
		$resData['message'] = 'Ошибка создания заказа';
		echo json_encode($resData);
		return;
	}

	// сохраняем товары для созданного заказа
	$res = setPurchaseForOrder($orderId, $cart);

	if ($res) {
		$resData['success'] = true;
		$resData['message'] = 'Заказ сохранен';
		unset($_SESSION['saleCart']);
		unset($_SESSION['cart']);	
	} else {
		$resData['success'] = false;
		$resData['message'] = 'Ошибка внесения лданных для заказа № ' . $orderId;
	}

d($_SESSION);

	echo json_encode($resData);
}