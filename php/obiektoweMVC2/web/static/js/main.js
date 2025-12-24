/**
 * Obsługa wyszukiwarki AJAX
 * Funkcja wywoływana przy wpisywaniu tekstu w pole wyszukiwania
 */
function searchImages(query) {
    const resultsContainer = document.getElementById("results");

    // Jeśli pole jest puste, czyścimy wyniki
    if (query.length === 0) {
        resultsContainer.innerHTML = "";
        return;
    }

    // Tworzymy obiekt XMLHttpRequest (czysty JS, bez jQuery)
    const xhr = new XMLHttpRequest();
    
    // Konfiguracja żądania POST do naszego kontrolera
    xhr.open("POST", "index.php?action=search", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Obsługa odpowiedzi z serwera
    xhr.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            // Wstawienie otrzymanego HTML do kontenera wyników
            resultsContainer.innerHTML = this.responseText;
        }
    };

    // Wysłanie danych
    xhr.send("query=" + encodeURIComponent(query));
}

// Opcjonalnie: potwierdzenie usuwania (dla lepszego UX)
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