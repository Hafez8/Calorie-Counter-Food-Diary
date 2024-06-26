document.addEventListener("DOMContentLoaded", function() {
    const searchButton = document.getElementById("searchButton");
    const foodInput = document.getElementById("foodInput");
    const resultsSection = document.getElementById("resultsSection");
    const resultsContainer = document.getElementById("resultsContainer");
  
    searchButton.addEventListener("click", function() {
      const foodName = foodInput.value.trim();
      if (foodName !== "") {
        searchFoodNutrition(foodName);
      } else {
        alert("Please enter a food name.");
      }
    });
  
    function searchFoodNutrition(foodName) {
      const apiKey = "nFmYlTuIfvQsxCOgTerJ2QudvctZpEcLNT70oCDi"; 
      const apiUrl = `https://api.api-ninjas.com/v1/nutrition?query=${encodeURIComponent(foodName)}`;
  
      fetch(apiUrl, {
        headers: {
          "X-Api-Key": apiKey
        }
      })
        .then(response => {
          if (!response.ok) {
            throw new Error("Network response was not ok.");
          }
          return response.json();
        })
        .then(data => {
          displayResults(data);
        })
        .catch(error => {
          console.error('There was a problem with the fetch operation:', error);
          alert("An error occurred while fetching data. Please try again later.");
        });
    }
  
    function displayResults(data) {
      // Clear previous results
      resultsContainer.innerHTML = "";
  
      // Loop through the data array (assuming it contains one item in this example)
      data.forEach(food => {
        const card = document.createElement("div");
        card.classList.add("card");
  
        const title = document.createElement("h3");
        title.textContent = food.name;
        card.appendChild(title);
  
        const nutritionList = document.createElement("ul");
        for (const key in food) {
          if (key !== "name") {
            const listItem = document.createElement("li");
            listItem.textContent = `${key.replace(/_/g, ' ')}: ${food[key]}`;
            nutritionList.appendChild(listItem);
          }
        }
        card.appendChild(nutritionList);
  
        resultsContainer.appendChild(card);
      });
  
      // Show the results section
      resultsSection.classList.remove("hidden");
    }
  });
  