<?php
/** @var array $items */
/** @var string $total */
 if($items) : ?>

<div class="wc-cart-api">

    <div class="cart-items">
        <?php foreach($items as $cart_item) : ?>

            <div class="cart-item" data-key="<?= $cart_item['key']; ?>">
                <div class="cart-item-img"><?= $cart_item['image']; ?></div>
                <div class="cart-item-title"><?= $cart_item['title']; ?></div>
                <div class="cart-item-qty">
                    <span class="qty__item minus">-</span>
                    <input type="text" name="qty" value="<?= $cart_item['quantity']; ?>">
                    <span class="qty__item plus">+</span>
                </div>
                <div class="cart-item-price"><?= $cart_item['price']; ?><?= $cart_item['currency']; ?></div>
                <div class="cart-item-price"><?= $cart_item['total']; ?><?= $cart_item['currency']; ?></div>
                <div class="cart-item-delete">X</div>
            </div>

        <?php endforeach; ?>
    </div>

        <div class="cart-row">
            <div class="cart-total">Total:&nbsp;<?= $total; ?></div>
        </div>

    <?php else : ?>

        <div class="cart-empty">Your cart is empty!</div>

    <?php endif; ?>

</div>

