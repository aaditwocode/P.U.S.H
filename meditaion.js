const progressRing = document.querySelector('.progress');
        const timeDisplay = document.getElementById('time-display');
        const timeInput = document.getElementById('time-input');
        const currentTimeDisplay = document.getElementById('current-time');
        const startPauseBtn = document.getElementById('start-pause-btn');
        const cancelBtn = document.getElementById('cancel-btn');
        const backgroundSelect = document.getElementById('background-select');
        const musicSelect = document.getElementById('music-select');
        const audio = document.getElementById('timer-audio');

        const CIRCUMFERENCE = 2 * Math.PI * 190;
        progressRing.style.strokeDasharray = CIRCUMFERENCE;
        progressRing.style.strokeDashoffset = 0;

        let timeLeft = 60;
        let totalTime = 60;
        let timerId = null;
        let isRunning = false;

        function parseTimeInput(timeString) {
            const [hours, minutes, seconds] = timeString.split(':').map(Number);
            return hours * 3600 + minutes * 60 + seconds;
        }

        function formatTimeInput(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        }

        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
            });
            currentTimeDisplay.textContent = timeString;
        }

        function updateProgress(timeLeft) {
            const progress = timeLeft / totalTime;
            const offset = CIRCUMFERENCE * (1 - progress);
            progressRing.style.strokeDashoffset = offset;
        }

        function startTimer() {
            if (!isRunning) {
                if (!timerId) {
                    timeLeft = parseTimeInput(timeInput.value);
                    totalTime = timeLeft;
                }
                
                isRunning = true;
                startPauseBtn.textContent = 'Pause';
                audio.play();
                
                timerId = setInterval(() => {
                    timeLeft--;
                    timeInput.value = formatTimeInput(timeLeft);
                    timeDisplay.textContent = formatTimeInput(timeLeft);
                    updateProgress(timeLeft);

                    if (timeLeft <= 0) {
                        clearInterval(timerId);
                        isRunning = false;
                        startPauseBtn.textContent = 'Start';
                        audio.pause();
                        audio.currentTime = 0;
                        timerId = null;
                    }
                }, 1000);
            } else {
                clearInterval(timerId);
                isRunning = false;
                startPauseBtn.textContent = 'Resume';
                audio.pause();
            }
        }

        function resetTimer() {
            clearInterval(timerId);
            isRunning = false;
            timerId = null;
            timeInput.value = '00:01:00';
            timeDisplay.textContent = '00:01:00';
            updateProgress(60);
            startPauseBtn.textContent = 'Start';
            audio.pause();
            audio.currentTime = 0;
        }

        timeInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d:]/g, '');
            if (value.length > 8) value = value.substr(0, 8);
            
            const parts = value.split(':');
            if (parts.length > 3) parts.length = 3;
            
            const formatted = parts.map(part => {
                let num = parseInt(part) || 0;
                if (num > 59) num = 59;
                return String(num).padStart(2, '0');
            }).join(':');

            e.target.value = formatted;
            timeDisplay.textContent = formatted;
        });

        startPauseBtn.addEventListener('click', startTimer);
        cancelBtn.addEventListener('click', resetTimer);

        backgroundSelect.addEventListener('change', (e) => {
            if (e.target.value) {
                document.body.style.backgroundImage = `url(${e.target.value})`;
            } else {
                document.body.style.backgroundImage = 'none';
            }
        });

        musicSelect.addEventListener('change', (e) => {
            if (e.target.value) {
                audio.src = e.target.value;
                if (isRunning) {
                    audio.play();
                }
            }
        });

        setInterval(updateCurrentTime, 1000);
        updateCurrentTime();

        updateProgress(60);
        document.addEventListener('DOMContentLoaded', function () {
            const backgroundSelect = document.getElementById('background-select');
        
            backgroundSelect.addEventListener('change', function () {
                const selectedBackground = backgroundSelect.value;
        
                if (selectedBackground) {
                    document.body.style.backgroundImage = `url('${selectedBackground}')`;
                    document.body.style.backgroundSize = 'cover';
                    document.body.style.backgroundRepeat = 'no-repeat';
                    document.body.style.backgroundPosition = 'center';
                } else {
                    document.body.style.backgroundImage = ''; 
                }
            });
        });
        