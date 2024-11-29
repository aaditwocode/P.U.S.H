import { exerciseOptions } from './utils/exerciseOptions.js';
import { fetchData } from './utils/fetchData.js';
const Icon = './assets/diet.jpg';

const bodyPartImages = {
  all: './assets/equipment.png',
  back: './assets/back.png',
  cardio: './assets/cardio.png',
  chest: './assets/chest-.png',
  "lower arms": './assets/002-muscle.png',
  "lower legs": './assets/003-leg.png',
  neck: './assets/007-neck.png',
  shoulders: './assets/005-shoulder-press.png',
  "upper arms": './assets/001-arm-muscle.png',
  "upper legs": './assets/004-leg-1.png',
  waist: './assets/006-muscles.png',
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
    scrollMenu.innerHTML = '';
    data.forEach((item) => {
      const cardDiv = document.createElement('div');
      cardDiv.classList.add('bodyPart-card');
      if (item === selectedBodyPart) {
        cardDiv.classList.add('active');
      }

      const icon = document.createElement('img');
      icon.src = bodyPartImages[item] || './assets/default.jpg';;
      icon.alt = item;
      icon.classList.add('card-icon');

      const text = document.createElement('h3');
      text.textContent = item;

      cardDiv.appendChild(icon);
      cardDiv.appendChild(text);

      cardDiv.addEventListener('click', () => handleBodyPartClick(item, cardDiv));

      scrollMenu.appendChild(cardDiv);
    });
  };

  document.querySelector('.left-arrow').addEventListener('click', () => {
    scrollMenu.scrollLeft -= 300;
  });

  document.querySelector('.right-arrow').addEventListener('click', () => {
    scrollMenu.scrollLeft += 300;
  });

  const handleBodyPartClick = (bodyPart, cardDiv) => {
    selectedBodyPart = bodyPart;

    document.querySelectorAll('.bodyPart-card').forEach(card => card.classList.remove('active'));
    cardDiv.classList.add('active');

    loadExercisesData(selectedBodyPart);
  };

  const loadExercisesData = async (bodyPart, searchTerm = '') => {
    let exercisesData = [];

    if (bodyPart === 'all') {
      exercisesData = await fetchData('https://exercisedb.p.rapidapi.com/exercises', exerciseOptions);
    } else {
      exercisesData = await fetchData(`https://exercisedb.p.rapidapi.com/exercises/bodyPart/${bodyPart}`, exerciseOptions);
    }

    exercises = exercisesData;
    filteredExercises = exercises.filter(exercise => {
      return (
        exercise.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        exercise.target.toLowerCase().includes(searchTerm.toLowerCase()) ||
        exercise.equipment.toLowerCase().includes(searchTerm.toLowerCase()) ||
        exercise.bodyPart.toLowerCase().includes(searchTerm.toLowerCase())
      );
    });

    currentPage = 1;
    renderExercises();
  };

  const renderExercises = () => {
    exerciseCardsContainer.innerHTML = '';

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

  fetchExercisesData();

  searchButton.addEventListener('click', () => {
    const search = searchInput.value.trim();
    if (search) {
      loadExercisesData(selectedBodyPart, search);
      searchInput.value = '';
    }
  });
});
