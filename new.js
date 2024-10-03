import { exerciseOptions } from './utils/exerciseOptions.js';
import { fetchData } from './utils/fetchData.js';
const Icon = './assets/diet.jpg'; 

const bodyPartImages = {
  all: './assets/equipment.png',
  back: './assets/back.jpg',
  chest: './assets/chest.jpg',
  legs: './assets/legs.jpg',
  shoulders: './assets/shoulders.jpg',
  arms: './assets/arms.jpg',
  abs: './assets/abs.jpg',
  // Add other body parts and their respective images as needed
};

document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.querySelector('.search-input');
  const searchButton = document.querySelector('.search-btn');
  const scrollMenu = document.querySelector('.scroll-menu');
  const leftArrowButton = document.querySelector('.left-arrow');
  const rightArrowButton = document.querySelector('.right-arrow');
  const exerciseCardsContainer = document.querySelector('.exercise-cards');
  
  let bodyParts = [];
  let selectedBodyPart = 'all';
  let exercises = [];
  let filteredExercises = [];
  let currentPage = 1;
  const exercisesPerPage = 6;

  const fetchExercisesData = async () => {
    try {
      const bodyPartsData = await fetchData('https://exercisedb.p.rapidapi.com/exercises/bodyPartList', exerciseOptions);
      bodyParts = ['all', ...bodyPartsData];
      populateScrollMenu(bodyParts);
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  };

  const populateScrollMenu = (data) => {
    scrollMenu.innerHTML = ''; // Clear previous content
    data.forEach((item) => {
      const cardDiv = document.createElement('div');
      cardDiv.classList.add('bodyPart-card');
      if (item === selectedBodyPart) {
        cardDiv.classList.add('active'); // Highlight active body part
      }

      // Add content to the body part card
      const icon = document.createElement('img');
      icon.src = bodyPartImages[item] || './assets/default.jpg';;
      icon.alt = item;
      icon.classList.add('card-icon');

      const text = document.createElement('h3');
      text.textContent = item;

      cardDiv.appendChild(icon);
      cardDiv.appendChild(text);

      // Handle card click event
      cardDiv.addEventListener('click', () => handleBodyPartClick(item, cardDiv));

      scrollMenu.appendChild(cardDiv);
    });
  };

  document.querySelector('.left-arrow').addEventListener('click', () => {
    scrollMenu.scrollLeft -= 300; // Adjust scroll distance as needed
  });

  document.querySelector('.right-arrow').addEventListener('click', () => {
    scrollMenu.scrollLeft += 300;
  });

  // Handle body part click event
  const handleBodyPartClick = (bodyPart, cardDiv) => {
    selectedBodyPart = bodyPart;

    // Remove active class from previously selected card
    document.querySelectorAll('.bodyPart-card').forEach(card => card.classList.remove('active'));
    // Highlight the clicked card
    cardDiv.classList.add('active');

    loadExercisesData(selectedBodyPart); // Fetch exercises for the selected body part
  };

  // Fetch exercises data based on selected body part or search term
  const loadExercisesData = async (bodyPart, searchTerm = '') => {
    let exercisesData = [];

    if (bodyPart === 'all') {
      exercisesData = await fetchData('https://exercisedb.p.rapidapi.com/exercises', exerciseOptions);
    } else {
      exercisesData = await fetchData(`https://exercisedb.p.rapidapi.com/exercises/bodyPart/${bodyPart}`, exerciseOptions);
    }

    exercises = exercisesData; // Store all exercises
    filteredExercises = exercises.filter(exercise => {
      return (
        exercise.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        exercise.target.toLowerCase().includes(searchTerm.toLowerCase()) ||
        exercise.equipment.toLowerCase().includes(searchTerm.toLowerCase()) ||
        exercise.bodyPart.toLowerCase().includes(searchTerm.toLowerCase())
      );
    });

    currentPage = 1; // Reset to first page
    renderExercises();
  };

  // Render exercises based on the current page
  const renderExercises = () => {
    exerciseCardsContainer.innerHTML = ''; // Clear previous content

    const indexOfLastExercise = currentPage * exercisesPerPage;
    const indexOfFirstExercise = indexOfLastExercise - exercisesPerPage;
    const currentExercises = filteredExercises.slice(indexOfFirstExercise, indexOfLastExercise);

    currentExercises.forEach(exercise => {
      const cardDiv = document.createElement('div');
      cardDiv.classList.add('exercise-card');
      cardDiv.innerHTML = `
        <a href="/exercise/${exercise.id}">
          <img src="${exercise.gifUrl || './path/to/static/image.jpg'}" alt="${exercise.name}" loading="lazy" />
          <div>
            <button>${exercise.bodyPart}</button>
            <button>${exercise.target}</button>
          </div>
          <h3>${exercise.name}</h3>
        </a>
      `;
      exerciseCardsContainer.appendChild(cardDiv);
    });

    document.getElementById('page-number').innerText = currentPage;
  };

  // Pagination control
  document.getElementById('prev-page').addEventListener('click', () => {
    if (currentPage > 1) {
      currentPage--;
      renderExercises();
    }
  });

  document.getElementById('next-page').addEventListener('click', () => {
    if (currentPage < Math.ceil(filteredExercises.length / exercisesPerPage)) {
      currentPage++;
      renderExercises();
    }
  });

  // Initialize and fetch data
  fetchExercisesData();
  
  searchButton.addEventListener('click', () => {
    const search = searchInput.value.trim();
    if (search) {
      loadExercisesData(selectedBodyPart, search); // Load filtered exercises based on search
      searchInput.value = ''; // Clear input
    }
  });
});
