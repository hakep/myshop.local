

// показ объекта в js
function d(obj) { 
    var str = ""; 
    for(k in obj) { 
        str += k+": "+ obj[k]+"\r\n"; 
    } 
    alert(str); 
} 

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


/**
 * [получение данных с формы]
 * @param  {[type]} obj_form [description]
 * @return {[type]}          [description]
 */
function getData(obj_form){
	var hData = {};
	$('input, textarea, select', obj_form).each(function(){
		if(this.name && this.name!=''){
			hData[this.name] = this.value;
			console.log('hData[' + this.name + '] = ' + hData[this.name]);
		}
	});
	return hData;
}

/**
 * [регистрация нового пользователя]
 * @return {[type]} [description]
 */
function registerNewUser(){

	var postData = getData('#registerBox');

	$.ajax({
		type: 'POST',
		async: true,
		url: "/user/register/",
		data: postData,
		dataType: 'json',
		success: function(data){
			// alert(JSON.stringify(data));
			if (data['success']) {
				alert(data['message']);

				// > блок в левом столбце
				$('#registerBox').hide();

				$('#displayName').html(data['userName']);
				$('#userBox').show();
				// <
				
				// > страница заказа
				$('#loginBox').hide();
				$('#btnSaveOrder').show();
				// <
			} else {
				alert(data['message']);
			}
		}
	});

}


/**
 * [авторизация пользователя]
 * @return {[type]} [description]
 */
function login(){

	var postData = getData('#loginBox');

	$.ajax({
		type: 'POST',
		async: true,
		url: "/user/login/",
		data: postData,
		dataType: 'json',
		success: function(data){
			if (data['success']) {
				$('#loginBox').hide();
				$('#registerBox').hide();

				$('#displayName').html(data['displayName']);
				$('#userBox').show();

				// заполняем поля на странице заказа
				$('#name').val(data['name']);
				$('#phone').val(data['phone']);
				$('#adress').val(data['adress']);
				$('#btnSaveOrder').show();
			} else {
				alert(data['message']);
			}
		}
	});
}


/**
 * [показывать или прятать форму регистрации]
 * @return {[type]} [description]
 */
function showRegisterBox(){
	$('#registerBoxHidden').toggle("slow");
}


/**
 * [обновляет данные пользователя]
 * @return {[type]} [description]
 */
function updateUserData(){
	// console.log("js - updateUserData()");
	var postData = getData('#updateUserData');

	$.ajax({
		type: 'POST',
		async: true,
		url: "/user/update/",
		data: postData,
		dataType: 'json',
		success: function(data){
			if (data['success']) {
				$('#displayName').html(data['displayName']);
				alert(data['message']);
			} else {
				alert(data['message']);
			}
		}
	});
}


/**
 * [сохраняет данные заказа]
 * @return {[type]} [description]
 */
function saveorder(){
	var postData = getData('#frmOrder');
// d(postData);
	$.ajax({
		type: 'POST',
		async: true,
		url: "/cart/saveorder/",
		data: postData,
		dataType: 'json',
		success: function(data){
			if (data['success']) {
				alert(data['message']);
				document.location = "/";
			} else {
				alert(data['message']);
			}
		}
	});
}