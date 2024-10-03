let timer = null;
let seconds = 0;
let minutes = 0;
let hours = 0;

let timerProgress = document.querySelector('.timer-progress');
let timerDisplay = document.querySelector('.timer-display');
let startButton = document.querySelector('#start-button');
let pauseButton = document.querySelector('#pause-button');
let resetButton = document.querySelector('#reset-button');
let minusButton = document.querySelector('#minus-button');
let plusButton = document.querySelector('#plus-button');
let timerInput = document.querySelector('#timer-input');
let audio = new Audio('./assets/autumn-sky-meditaion-7618.mp3');

// Store the initial time entered by the user
let initialTime = 0;

startButton.addEventListener('click', startTimer);
pauseButton.addEventListener('click', pauseTimer);
resetButton.addEventListener('click', resetTimer);
minusButton.addEventListener('click', decreaseTime);
plusButton.addEventListener('click', increaseTime);

function startTimer() {
    if (timerInput.value <= 0) return; // If timer input is 0 or negative, do nothing
    timer = setInterval(updateTimer, 1000);
    audio.play();
    disableInputs(); // Disable inputs after starting the timer
}

function pauseTimer() {
    clearInterval(timer);
    audio.pause();
}

function resetTimer() {
    clearInterval(timer);
    // Reset time to the last entered time (if any)
    seconds = 0;
    minutes = 0;
    hours = 0;
    let lastTime = parseInt(timerInput.value) * 60; // Convert entered minutes to seconds
    seconds = lastTime % 60;
    minutes = Math.floor(lastTime / 60);
    
    updateDisplay();
    timerProgress.style.width = '0%';
    audio.pause();
    audio.currentTime = 0;
    
    enableInputs(); // Re-enable inputs after resetting
}

function decreaseTime() {
    let time = parseInt(timerInput.value);
    if (time > 1) {
        time -= 1;
        timerInput.value = time;
    }
}

function increaseTime() {
    let time = parseInt(timerInput.value);
    time += 1;
    timerInput.value = time;
}

function updateTimer() {
    seconds++;
    if (seconds === 60) {
        minutes++;
        seconds = 0;
    }
    if (minutes === 60) {
        hours++;
        minutes = 0;
    }
    
    updateDisplay();

    let progress = (seconds + minutes * 60 + hours * 3600) / (parseInt(timerInput.value) * 60) * 100;
    timerProgress.style.width = `${progress}%`;
    
    if (progress >= 100) {
        clearInterval(timer);
        audio.pause();
        alert('Time is up!');
    }
}

function updateDisplay() {
    let hoursText = hours.toString().padStart(2, '0');
    let minutesText = minutes.toString().padStart(2, '0');
    let secondsText = seconds.toString().padStart(2, '0');
    timerDisplay.textContent = `${hoursText}:${minutesText}:${secondsText}`;
}

function disableInputs() {
    timerInput.disabled = true;
    minusButton.disabled = true;
    plusButton.disabled = true;
}

function enableInputs() {
    timerInput.disabled = false;
    minusButton.disabled = false;
    plusButton.disabled = false;
}
