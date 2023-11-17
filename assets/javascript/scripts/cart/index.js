
const addToCartBtn = document.getElementById('addToCartBtn');

addToCartBtn.addEventListener('click', function(){
    idproduct = addToCartBtn.dataset.idproduct;
    quantity =  document.getElementById('qunatityInput').value;

    const url = '/cart/add';
    const data = {
        idproduct,
        quantity
    };

    console.log(data);

    fetch(url, {
    method: 'POST',
    body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => alert('Dodano produkt'))
    .catch(error => console.error('Error:', error));





});


  


