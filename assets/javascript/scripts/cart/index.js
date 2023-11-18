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
            .then(data => alert('Dodano produkt'))
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
                        const trElement = button.closest('tr');
                        if (trElement) {
                            trElement.remove();
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    }

});


  


