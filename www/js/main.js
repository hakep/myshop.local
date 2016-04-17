/**
 * [функция добавления товара в корзину]
 * @param {[type]} itemId [ID продукта]
 * @return [в случае успеха обновятся данные корзины на странице]
 */
function addToCart(itemId){
	console.log('js - addToCart()');
	$.ajax({
		type: 'POST',
		async: true,
		url: "/cart/addtocart/" + itemId + "/",
		dataType: 'json',
		success: function (data){
			if (data['success']) {
				$('#cartCntItems').html(data['cntItems']);
				$('#addCart_' + itemId).hide();
				$('#removeCart_' + itemId).show();
			}
		}
	}); 
}


/**
 * [функция удаление товара из корзины]
 * @param {[type]} itemId [ID продукта]
 * @return [в случае успеха обновятся данные корзины на странице]
 */
function removeFromCart(itemId){
	console.log("js - removeFromCart(" + itemId + ")");
	$.ajax({
		type: 'POST',
		async: true,
		url: "/cart/removefromcart/" + itemId + "/",
		dataType: 'json',
		success: function (data){
			if (data['success']) {
				$('#cartCntItems').html(data['cntItems']);
				$('#addCart_' + itemId).show();
				$('#removeCart_' + itemId).hide();
			}
		}
	}); 
}


/**
 * [подсчет стоимости купленного товара]
 * @param  {[type]} itemId [ID продукта]
 * @return {[type]}        [description]
 */
function conversionPrice(itemId){
	var newCnt = $('#itemCnt_' + itemId).val();
	var itemPrice = $('#itemPrice_' + itemId).attr('value');
	var itemRealPrice = newCnt * itemPrice;
// alert(newCnt);
	$('#itemRealPrice_' + itemId).html(itemRealPrice);
}