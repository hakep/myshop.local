<?php 

/**
 * модель для таблицы продукции (purchase)
 */



/**
 * [внесение в базу данных продуктов с привязкой к заказу]
 * @param [type] $orderId [ID заказа]
 * @param [type] $cart    [массив корзины]
 * TRUE в случае успешного добавления в базу данных
 */
function setPurchaseForOrder($orderId, $cart){
	global $mysqli;

	$sql = "INSERT INTO purchase (`order_id`, `product_id`, `price`, `amount`) VALUE ";

	$value = array();
	// формируем массив строк для запроса, для каждого товара
	foreach ($cart as $item) {
		$value[] = "('{$orderId}', '{$item['id']}', '{$item['price']}', '{$item['cnt']}')"; 
	}

	// преобразовываем массив в строку
	$sql .= implode($value, ', ');

	$rs = $mysqli->query($sql);

	return $rs;
}