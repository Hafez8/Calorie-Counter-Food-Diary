document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM fully loaded and parsed"); // Debug statement

    const searchButton = document.getElementById("searchButton");
    const foodInput = document.getElementById("foodInput");
    const resultsSection = document.getElementById("resultsSection");
    const resultsContainer = document.getElementById("resultsContainer");

    if (!searchButton || !foodInput || !resultsSection || !resultsContainer) {
        console.error("One or more elements not found. Ensure your IDs are correct.");
        return;
    }

    searchButton.addEventListener("click", function() {
        const foodName = foodInput.value.trim();
        if (foodName !== "") {
            console.log(`Searching for: ${foodName}`); // Debug statement
            searchFoodNutrition(foodName);
        } else {
            alert("Please enter a food name.");
        }
    });

    function searchFoodNutrition(foodName) {
        const apiKey = "nFmYlTuIfvQsxCOgTerJ2QudvctZpEcLNT70oCDi"; 
        const apiUrl = `https://api.api-ninjas.com/v1/nutrition?query=${encodeURIComponent(foodName)}`;
        console.log(`API URL: ${apiUrl}`); // Debug statement

        fetch(apiUrl, {
            headers: {
                "X-Api-Key": apiKey
            }
        })
        .then(response => {
            console.log(`Response status: ${response.status}`); // Debug statement
            if (!response.ok) {
                throw new Error("Network response was not ok.");
            }
            return response.json();
        })
        .then(data => {
            console.log(data); // Debug statement
            displayResults(data);
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            alert("An error occurred while fetching data. Please try again later.");
        });
    }

    function displayResults(data) {
        resultsContainer.innerHTML = "";

        if (data.length === 0) {
            resultsContainer.innerHTML = "<p>No results found.</p>";
            resultsSection.classList.remove("hidden");
            return;
        }

        data.forEach(food => {
            const card = document.createElement("div");
            card.classList.add("card", "mt-2");

            const cardBody = document.createElement("div");
            cardBody.classList.add("card-body", "d-flex", "justify-content-between", "align-items-center");

            const foodDetails = document.createElement("div");
            foodDetails.innerHTML = `
                <strong>${food.name}</strong> - ${food.calories} calories, ${food.serving_size} serving size
            `;

            const addButton = document.createElement("button");
            addButton.classList.add("btn", "btn-primary", "btn-sm");
            addButton.textContent = "Add";
            addButton.onclick = function() {
                addFoodToSelected(food);
            };

            cardBody.appendChild(foodDetails);
            cardBody.appendChild(addButton);
            card.appendChild(cardBody);
            resultsContainer.appendChild(card);
        });

        resultsSection.classList.remove("hidden");
    }

    function addFoodToSelected(food) {
        const selectedFoodsDiv = document.getElementById('selected-foods');
        const foodItem = document.createElement('div');
        foodItem.classList.add('selected-food', 'd-flex', 'justify-content-between', 'align-items-center', 'mt-2');
        foodItem.innerHTML = `
            <div>
                <strong>${food.name}</strong> - ${food.calories} calories, ${food.serving_size} serving size
            </div>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeFood(this)">Remove</button>
        `;
        selectedFoodsDiv.appendChild(foodItem);
    }

    window.removeFood = function(button) {
        button.parentElement.remove();
    }
});
