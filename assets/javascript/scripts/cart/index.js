document.addEventListener('DOMContentLoaded', function() {

    const addToCartBtn = document.getElementById('addToCartBtn');
    if(addToCartBtn){

        addToCartBtn.addEventListener('click', function(){

            idproduct = addToCartBtn.dataset.idproduct;
            quantity =  document.getElementById('qunatityInput').value;

            const url = '/cart/add';
            const data = {
                idproduct,
                quantity
            };

            fetch(url, {
            method: 'POST',
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(data => {
                const cartConfirmationModal = new bootstrap.Modal(document.getElementById('cartConfirmationModal'));
                cartConfirmationModal.show();
            })
            .catch(error => console.error('Error:', error));
        });
    }

    const removeButtons = document.querySelectorAll('.removeProductInCartBtn');
    if (removeButtons) {
        removeButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                idproduct = button.dataset.idproduct;
    
                const url = '/cart/remove';
                const data = {
                    idproduct
                };
    
                fetch(url, {
                    method: 'POST',
                    body: JSON.stringify(data),
                })
                .then(response => {
                    if (response.status === 200) {
                        const productCart = button.closest('.productCart');
                        if (productCart) {
                            productCart.remove();
                            const productCartElement = document.querySelector('.productCart');

                            if (!productCartElement) {
                                let newElement = document.createElement('div');
                                
                                newElement.className = 'row d-flex justify-content-center align-items-center h-100';
                                newElement.innerHTML = `
                                    <div class="col-10">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h3 class="fw-normal mb-0 text-black">Shopping Cart</h3>
                                        </div>
                                        <h1>No products in cart</h1>
                                    </div>
                                `;
                                
                                const containerElement = document.getElementById('cartContent');
                                
                                // Clear and save
                                containerElement.innerHTML = '';
                                containerElement.appendChild(newElement);
                            }



                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    }

});


  


