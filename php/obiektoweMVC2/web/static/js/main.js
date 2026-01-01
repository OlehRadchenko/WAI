function searchImages(query) {
    const resultsContainer = document.getElementById("results");

    if (query.length === 0) {
        resultsContainer.innerHTML = "";
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?action=search", true);
    
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            resultsContainer.innerHTML = this.responseText;
        }
    };

    xhr.send("query=" + encodeURIComponent(query));
}

document.addEventListener("DOMContentLoaded", function() {
    const deleteButtons = document.querySelectorAll('button[name="delete"]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm("Czy na pewno chcesz usunąć to zdjęcie?")) {
                e.preventDefault();
            }
        });
    });
});