document.addEventListener('DOMContentLoaded', function () {
    const challenges = [
        {
            name: '7-Day Fitness Challenge',
            checkboxes: document.querySelectorAll('.fitness-theme input[type="checkbox"]'),
            progressBar: document.querySelector('.progress-container tr:nth-child(2) .progress-bar div'),
            coinTracker: document.querySelector('.fitness-theme .coin-tracker'),
            coinsPerDay: 10,
            bonusCoins: 50
        },
        {
            name: 'Healthy Meal Plan Challenge',
            checkboxes: document.querySelectorAll('.meal-theme input[type="checkbox"]'),
            progressBar: document.querySelector('.progress-container tr:nth-child(3) .progress-bar div'),
            coinTracker: document.querySelector('.meal-theme .coin-tracker'),
            coinsPerDay: 5,
            bonusCoins: 10
        },
        {
            name: 'Mindfulness & Meditation Challenge',
            checkboxes: document.querySelectorAll('.mindfulness-theme input[type="checkbox"]'),
            progressBar: document.querySelector('.progress-container tr:nth-child(4) .progress-bar div'),
            coinTracker: document.querySelector('.mindfulness-theme .coin-tracker'),
            coinsPerDay: 15,
            bonusCoins: 25
        },
        {
            name: 'Hydration Challenge',
            checkboxes: document.querySelectorAll('.hydration-theme input[type="checkbox"]'),
            progressBar: document.querySelector('.progress-container tr:nth-child(5) .progress-bar div'),
            coinTracker: document.querySelector('.hydration-theme .coin-tracker'),
            coinsPerDay: 2,
            bonusCoins: 10
        },
        {
            name: 'Sleep Improvement Challenge',
            checkboxes: document.querySelectorAll('.sleep-theme input[type="checkbox"]'),
            progressBar: document.querySelector('.progress-container tr:nth-child(6) .progress-bar div'),
            coinTracker: document.querySelector('.sleep-theme .coin-tracker'),
            coinsPerDay: 10,
            bonusCoins: 30
        }
    ];

    let totalCoins = 0;
    const totalCoinsElement = document.getElementById('total-coins');

    fetch('update_coins.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                totalCoins = data.coins;
                totalCoinsElement.textContent = totalCoins;
            } else {
                console.error('Failed to fetch coins:', data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching coins:', error);
        });

    challenges.forEach(challenge => {
        challenge.checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                updateProgress(challenge);
            });
        });
        updateProgress(challenge);
    });

    function updateProgress(challenge) {
        const completed = Array.from(challenge.checkboxes).filter(checkbox => checkbox.checked).length;
        const total = challenge.checkboxes.length;
        const progressPercentage = (completed / total) * 100;

        challenge.progressBar.style.width = `${progressPercentage}%`;

        let coins = completed * challenge.coinsPerDay;
        if (completed === total) {
            coins += challenge.bonusCoins;
        }
        challenge.coinTracker.textContent = `Coin tracker: ${coins} coins`;

        updateTotalCoins();
    }

    function updateTotalCoins() {
        totalCoins = 0;
        challenges.forEach(challenge => {
            const coinText = challenge.coinTracker.textContent;
            const coins = parseInt(coinText.match(/\d+/)[0]);
            totalCoins += coins;
        });
        totalCoinsElement.textContent = totalCoins;
        updateCoinsInDatabase(totalCoins); 
    }

    function updateCoinsInDatabase(totalCoins) {
        fetch('update_coins.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ totalCoins }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Coins updated successfully');
                } else {
                    console.error('Failed to update coins:', data.message);
                }
            })
            .catch(error => {
                console.error('Error updating coins:', error);
            });
    }
});
