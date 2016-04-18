{* страница продукта *}
<h3>{$rsProduct['name']}</h3>

<img src="/img/products/{$rsProduct['image']}" width="575"><br>
стоимость: {$rsProduct['price']} руб.

<a id="removeCart_{$rsProduct['id']}" {if !$itemInCart} class="hideme" {/if} href="#" onclick="removeFromCart({$rsProduct['id']}); return false;" alt="Удалить из корзины">Удалить из корзины</a>
<a id="addCart_{$rsProduct['id']}" {if $itemInCart} class="hideme" {/if} href="#" onclick="addToCart({$rsProduct['id']}); return false;" alt="Добавить в корзину">Добавить в корзину</a>

<p>Описание <br>
{$rsProduct['description']}</p>