// Function to fetch exercises
async function fetchExercises() {
    const apiUrl = 'https://exercisedb.p.rapidapi.com/exercises';
    const options = {
        method: 'GET',
        headers: {
            'X-RapidAPI-Host': 'exercisedb.p.rapidapi.com',
            'X-RapidAPI-Key': '50f16bf47amshd0990582e7d2205p1d59dcjsncae1f55f3577'
        }
    };

    const response = await fetch(apiUrl, options);
    const data = await response.json();
    return data;
}

// Function to fetch body parts
async function fetchBodyParts() {
    const apiUrl = 'https://exercisedb.p.rapidapi.com/exercises/bodyPartList';
    const options = {
        method: 'GET',
        headers: {
            'X-RapidAPI-Host': 'exercisedb.p.rapidapi.com',
            'X-RapidAPI-Key': 'your-rapidapi-key' // Replace with your actual key
        }
    };

    const response = await fetch(apiUrl, options);
    const data = await response.json();
    return data;
}

// Handle search functionality
document.getElementById('search-btn').addEventListener('click', async () => {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const exercises = await fetchExercises();

    // Filter exercises based on search term
    const searchResults = exercises.filter(exercise =>
        exercise.name.toLowerCase().includes(searchTerm) ||
        exercise.target.toLowerCase().includes(searchTerm) ||
        exercise.bodyPart.toLowerCase().includes(searchTerm) ||
        exercise.equipment.toLowerCase().includes(searchTerm)
    );

    displayResults(searchResults);
});

// Function to display search results in the DOM
function displayResults(exercises) {
    const resultsContainer = document.getElementById('search-results');
    resultsContainer.innerHTML = ''; // Clear previous results

    if (exercises.length === 0) {
        resultsContainer.innerHTML = '<p>No results found.</p>';
        return;
    }

    exercises.forEach(exercise => {
        const exerciseElement = document.createElement('div');
        exerciseElement.classList.add('exercise-item');
        exerciseElement.innerHTML = `
            <h3>${exercise.name}</h3>
            <p>Target: ${exercise.target}</p>
            <p>Equipment: ${exercise.equipment}</p>
        `;
        resultsContainer.appendChild(exerciseElement);
    });
}

// Function to display body parts in horizontal scroll
async function displayBodyParts() {
    const bodyParts = await fetchBodyParts();
    const scrollContainer = document.getElementById('body-parts-scroll');
    scrollContainer.innerHTML = ''; // Clear previous body parts

    // Add 'all' as a default option to display all exercises
    bodyParts.unshift('all');

    // Loop through body parts and create clickable elements
    bodyParts.forEach(bodyPart => {
        const bodyPartElement = document.createElement('div');
        bodyPartElement.innerText = bodyPart;
        bodyPartElement.classList.add('body-part-item');

        // Add click event to filter exercises based on selected body part
        bodyPartElement.addEventListener('click', async () => {
            const exercises = await fetchExercises();
            const filteredExercises = exercises.filter(exercise =>
                bodyPart === 'all' || exercise.bodyPart.toLowerCase() === bodyPart.toLowerCase()
            );
            displayResults(filteredExercises);
        });

        scrollContainer.appendChild(bodyPartElement);
    });
}

// Call the function to display body parts on page load
displayBodyParts();
