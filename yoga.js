let progressInterval;
let progressBar = document.getElementById('progress');
let percentageElement = document.getElementById('percentage');
let startButton = document.getElementById('start-button');
let pauseButton = document.getElementById('pause-button');
let resetButton = document.getElementById('reset-button');
let progress = 0;

startButton.addEventListener('click', startProgress);
pauseButton.addEventListener('click', pauseProgress);
resetButton.addEventListener('click', resetProgress);

function startProgress() {
    progressInterval = setInterval(updateProgress, 100);
}

function pauseProgress() {
    clearInterval(progressInterval);
}

function resetProgress() {
    clearInterval(progressInterval);
    progress = 0;
    progressBar.style.width = '0%';
    percentageElement.textContent = '0%';
}

function updateProgress() {
    progress += 1;
    progressBar.style.width = `${progress}%`;
    percentageElement.textContent = `${progress}%`;
    if (progress >= 100) {
        clearInterval(progressInterval);
    }
}