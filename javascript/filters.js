document.addEventListener('DOMContentLoaded', function() {
    var filterForm = document.getElementById('filters-form');

    var pagination = document.getElementsByClassName("pagination");

    filterForm.addEventListener('change', function() {
        var formData = new FormData(filterForm);

        for (var i = 0; i < pagination.length; i++) {
            if(pagination[i].style.display == ""){
                pagination[i].style.display = "none";
            }else{
                pagination[i].style.display = ""
            }
        }

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var itemsContainer = document.getElementById("productsSection");
                    if(!itemsContainer){
                        var itemsContainer = document.getElementById("products");
                        itemsContainer.innerHTML = "";
                        itemsContainer.id = "productsSection";
                    }
                    itemsContainer.innerHTML = "";
                    itemsContainer.innerHTML = xhr.responseText;
                } else {
                    console.error('Error:', xhr.statusText);
                }
            }
        };

        xhr.open('POST', '../actions/action_filter_items.php', true);
        xhr.send(formData);
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.querySelector('.filter-button');
    const closeButton = document.querySelector('.close-button');
    const filters = document.querySelector('.filters');

    filterButton.addEventListener('click', () => {
        if (filters.style.transform === 'translateX(-100%)') {
            filters.style.transform = 'translateX(0)';
            filterButton.style.visibility = 'visible'; 
        } else {
            filters.style.transform = 'translateX(-100%)';
            filterButton.style.visibility = 'visible'; 
        }
    });

    closeButton.addEventListener('click', () => {
        filters.style.transform = 'translateX(-100%)';
        filterButton.style.visibility = 'visible'; 
    });
});