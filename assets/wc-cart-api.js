
document.addEventListener('DOMContentLoaded', function(){

    const btn = document.querySelectorAll('.add-to-cart-api');
    const modal = document.querySelector('#modal-cart-api');
    const cartIcon = document.querySelector('.wc-cart-icon');
    const cartCount = document.querySelector('.wc-cart-count');
    const modalBg = document.querySelector('.modal-cart-api__bg');
    const url = '/wp-json/wc-cart-api/v1/cart';

    if(cartIcon){
        /**
         * Show cart
         */
        cartIcon.addEventListener('click', () => {

            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
                .then(res => res.json())
                .then(data => {
                   // console.log(data);

                        getCart(data, true);
                })
                .catch(err => console.error('Ошибка при вызове корзины:', err));
        })
    }



        /**
         * Add to cart
         */
        document.addEventListener('click', (e) => {

            if(e.target.classList.contains('add-to-cart')){
                e.preventDefault();

                const quantityInput = document.querySelector('input.product-quantity');
                const quantity = (quantityInput) ? quantityInput.value : 1;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        product_id: e.target.getAttribute('data-product'),
                        quantity: quantity
                    }),
                    credentials: 'same-origin'
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);

                        getCart(data, true);
                    })
                    .catch(err => console.error('Ошибка при добавлении товара:', err));

            }
        })


        /**
         * Update quantity cart item
         */
        document.addEventListener('click', (e) => {
            let value;
            let key;
            if(e.target.classList.contains('qty__item') && e.target.classList.contains('plus')){
                value = +e.target.closest('.cart-item').querySelector('.cart-item-qty input').value;
                value++;
                e.target.closest('.cart-item').querySelector('.cart-item-qty input').value = value;
                key = e.target.closest('.cart-item').getAttribute('data-key');
            }
            if(e.target.classList.contains('qty__item') && e.target.classList.contains('minus')){
                value = +e.target.closest('.cart-item').querySelector('.cart-item-qty input').value;
                value--;
                if(value < 1){
                    value = 1;
                }
                e.target.closest('.cart-item').querySelector('.cart-item-qty input').value = value;
                key = e.target.closest('.cart-item').getAttribute('data-key');
            }
            if(value){
               fetch(url, {
                   method: 'PATCH',
                   headers: {
                       'Content-Type': 'application/x-www-form-urlencoded'
                   },
                    body: new URLSearchParams({
                        cart_key: key,
                        quantity: value
                    })
               })
                   .then(res => res.json())

                   .then(data => {

                       getCart(data);
                   })
                   .catch(err => console.error('Ошибка при обновлении корзины:', err));
            }

        })


    /**
     * Delete cart item
     */
    document.addEventListener('click', (e) => {
        if(e.target.classList.contains('cart-item-delete') && e.target.closest('.wc-cart-api')){
            const key = e.target.closest('.cart-item').getAttribute('data-key');

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    cart_key: key
                })
            })
                .then(res => res.json())
                .then(data => {

                    getCart(data);
                })
                .catch(err => console.error('Ошибка при удалении элемента корзины:', err));
        }
    })

    if(modal){
        modal.querySelector('.close').addEventListener('click', () => {
            closeModal();
        })

        document.querySelector('.modal-cart-api__bg').addEventListener('click', () => {
            closeModal();
        })
    }


    function closeModal(){
        modal.classList.remove('active');
        modalBg.classList.remove('active');
    }

    function getCart(data, showModal = false){
        //console.log(data);
        if(data['status'] == 'success'){
            const cart = data['cart'];
            if(cartCount){
                cartCount.innerText = cart['count'];
            }
            modal.querySelector('.cart-content').innerHTML = cart['html'];
            if(showModal){
                modal.classList.add('active');
                modalBg.classList.add('active');
            }
        } else {
            return data.message;
        }
    }

})

