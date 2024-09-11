
function handleSearch(){
document.addEventListener("DOMContentLoaded", function() {
    var searchBox = document.getElementById('search-bar');
    var resultDropdown = document.getElementById('search-bar-result');

    searchBox.addEventListener("keyup", function(event) {
        var inputVal = event.target.value.trim();
        
        if (inputVal.length) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../actions/action_backend_search.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            resultDropdown.innerHTML = xhr.responseText;
            }
        };
        xhr.send("term=" + encodeURIComponent(inputVal));
        } else {
            resultDropdown.innerHTML = '';
        }
    
    });

    document.addEventListener("click", function(event) {
        if (event.target && event.target.classList.contains("result-item")) {
            searchBox.value = event.target.textContent;
            resultDropdown.innerHTML = '';
        }
    });
});
}


handleSearch();