document.addEventListener('DOMContentLoaded', function() {
    const images = document.getElementsByTagName('img');
    for (let i = 0; i < images.length; i++) {
        images[i].onerror = function() {
            this.onerror = null;
            this.src = 'images/products/No-Image.jpg'; 
        };
    }

    const selectElement = document.getElementById("sort");
    if(selectElement){
        selectElement.addEventListener("change", () => {
            const selectedValue = selectElement.options[selectElement.selectedIndex].value;
            const date = new Date();
    
            date.setTime(date.getTime() + 1 * 24 * 60 * 60 * 1000); 
            const expires = `expires=${date.toUTCString()}`;
            
            document.cookie = `selectedSort=${selectedValue}; ${expires}; path=/`;
            location.reload();
        });

    }
  

    const checkbox = document.getElementById('bestsellers');

    checkbox.addEventListener('click', function(event) {
        if (checkbox.checked) {
            const date = new Date();

            date.setTime(date.getTime() + 1 * 24 * 60 * 60 * 1000); 
            const expires = `expires=${date.toUTCString()}`;

            document.cookie = `filterBestseller=1; ${expires}; path=/`;
            location.reload();
        } else {
            document.cookie = 'filterBestseller=0; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            location.reload();
        }
    });


});