document.addEventListener('DOMContentLoaded', function() {
    const images = document.getElementsByTagName('img');
    for (let i = 0; i < images.length; i++) {
        images[i].onerror = function() {
            this.onerror = null;
            this.src = 'images/products/No-Image.jpg'; 
        };
    }

    const sort = document.getElementById("sort");
    if (sort) {
        sort.addEventListener("change", () => {
            const selectedValue = sort.options[sort.selectedIndex].value;
            const date = new Date();
    
            date.setTime(date.getTime() + 1 * 24 * 60 * 60 * 1000); 
            const expires = `expires=${date.toUTCString()}`;
            
            document.cookie = `selectedSort=${selectedValue}; ${expires}; path=/`;
            location.reload();
        });
    }

    const filterBestseller = document.getElementById('filterBestseller');
    if (filterBestseller) {
        filterBestseller.addEventListener('click', function(event) {
            if (filterBestseller.checked) {
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
    }

    const filterLatest = document.getElementById('filterLatest');
    if (filterLatest) {
        filterLatest.addEventListener('click', function(event) {
            if (filterLatest.checked) {
                const date = new Date();
        
                date.setTime(date.getTime() + 1 * 24 * 60 * 60 * 1000); 
                const expires = `expires=${date.toUTCString()}`;
        
                document.cookie = `filterLatest=1; ${expires}; path=/`;
                location.reload();
            } else {
                document.cookie = 'filterLatest=0; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                location.reload();
            }
        });

        const filterMinPrice = document.getElementById('filterMinPrice');
        if (filterMinPrice) {
            filterMinPrice.addEventListener('input', function(event) {
                const date = new Date();
                date.setTime(date.getTime() + 1 * 24 * 60 * 60 * 1000);
                const expires = `expires=${date.toUTCString()}`;
                
                const minPriceValue = filterMinPrice.value;  // Poprawione ID elementu
                document.cookie = `filterMinPrice=${minPriceValue}; ${expires}; path=/`;
                location.reload();
            });
        }
        
        const filterMaxPrice = document.getElementById('filterMaxPrice');
        if (filterMaxPrice) {
            filterMaxPrice.addEventListener('input', function(event) {
                const date = new Date();
                date.setTime(date.getTime() + 1 * 24 * 60 * 60 * 1000);
                const expires = `expires=${date.toUTCString()}`;
                
                const maxPriceValue = filterMaxPrice.value;  // Poprawione ID elementu
                document.cookie = `filterMaxPrice=${maxPriceValue}; ${expires}; path=/`;
                location.reload();
            });
        }
        
    }
});