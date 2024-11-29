let weight, height, gender;
function submitUserDetails() {
    weight = parseFloat(document.getElementById('user-weight').value);
    height = parseFloat(document.getElementById('user-height').value);
    gender = document.querySelector('input[name="gender"]:checked').value;

    if (isNaN(weight) || isNaN(height) || (!gender)) {
        alert("Please enter valid weight, height, and select a gender.");
        return;
    }

    alert("Details submitted successfully! You can now calculate your BMI.");
}


function calculateBMI() {
    if (weight > 0 && height > 0) {
        const bmi = (weight / (height * height)).toFixed(2);
        let category = '';
        let analysis = '';

        if (gender === 'male') {
            if (bmi < 20) {
                category = 'Underweight';
                analysis = 'You are considered underweight. It may be beneficial to follow our bulk plan followed by the toned plan.';
            } else if (bmi < 25) {
                category = 'Normal weight';
                analysis = 'Congratulations! You have a normal weight. Maintain the balanced diet that you are currently taking. We recommend you try all the plans.';
            } else if (bmi < 30) {
                category = 'Overweight';
                analysis = 'You are classified as overweight. Consider adopting our slim weight plan.';
            } else {
                category = 'Obesity';
                analysis = 'You are considered obese. It is highly recommended to seek advice from a healthcare provider and to strictly follow the slim diet plan.';
            }
        } else if (gender === 'female') {
            if (bmi < 18.5) {
                category = 'Underweight';
                analysis = 'You are considered underweight. It may be beneficial to follow our bulk plan followed by the toned plan.';
            } else if (bmi < 24.9) {
                category = 'Normal weight';
                analysis = 'Congratulations! You have a normal weight. Maintain a balanced diet that you are currently taking. We recommend you try all the plans.';
            } else if (bmi < 29.9) {
                category = 'Overweight';
                analysis = 'You are classified as overweight. Consider adopting our slim weight plan.';
            } else {
                category = 'Obesity';
                analysis = 'You are considered obese. It is highly recommended to seek guidance from a healthcare provider and to strictly follow the slim diet plan.';
            }
        }

        document.getElementById('result').innerText = `Your BMI is ${bmi} - ${category}`;
        document.getElementById('analysis').innerText = analysis;
    } else {
        document.getElementById('result').innerText = 'Please enter valid weight and height.';
        document.getElementById('analysis').innerText = '';
    }
}

function selectBodyType(bodyType) {
    bodyTypeSelected = bodyType;
    alert('You selected the ' + bodyType + ' body type.');
    document.getElementById('meal-plan-section').style.display = 'block';
}

let totalCalories = 0;
let consumedCalories = 0;
let dailyCalorieGoal = 0;
let startDate = null;

function setStartDate() {
    const startDateInput = document.getElementById('start-date').value;
    if (!startDateInput) {
        alert('Please select a start date.');
        return;
    }
    startDate = new Date(startDateInput);
    totalCalories = dailyCalorieGoal * 30;
    document.getElementById('total-calories-display').innerText = totalCalories + ' kcal';
    document.getElementById('final-results').innerText = `Tracking started from ${startDateInput}. Total monthly calorie goal set to ${totalCalories} kcal.`;
    document.getElementById('results-section').style.display = 'block';
    document.getElementById('progress-section').style.display = 'block';
    updateDisplayedDates();
}

function setDailyCalorieGoal() {
    const newDailyGoal = parseInt(document.getElementById('daily-calorie-input').value);
    if (isNaN(newDailyGoal) || newDailyGoal <= 0) {
        alert('Please enter a valid daily calorie goal.');
        return;
    }
    dailyCalorieGoal = newDailyGoal;
    totalCalories = dailyCalorieGoal * 30;
    document.getElementById('total-calories-display').innerText = totalCalories + ' kcal';
    const progressPercent = (consumedCalories / totalCalories) * 100;
    document.getElementById('progress-bar').style.width = progressPercent + '%';
    document.getElementById('progress-text').innerText = 'Progress: ' + Math.floor(progressPercent) + '%';
    document.getElementById('final-results').innerText = `Daily calorie goal updated to ${dailyCalorieGoal} kcal. Monthly calorie goal is now ${totalCalories} kcal. Progress has been recalculated.`;

    const remainingCalories = totalCalories - consumedCalories;
    document.getElementById('remaining-calories-display').innerText = `Remaining Calories for the Month: ${remainingCalories} kcal`;

    alert(`Daily calorie goal set to ${dailyCalorieGoal} kcal. Monthly calorie goal updated to ${totalCalories} kcal. Progress has been recalculated.`);
    updateDisplayedDates();
}

function calculateNextMonthDate(currentDate) {
    const nextMonthDate = new Date(currentDate);
    nextMonthDate.setMonth(nextMonthDate.getMonth() + 1);
    return nextMonthDate;
}

function checkAndResetProgress() {
    const currentDate = new Date();
    const monthDiff = currentDate.getMonth() - startDate.getMonth() +
        (12 * (currentDate.getFullYear() - startDate.getFullYear()));
    if (monthDiff >= 1) {
        const progressPercent = (consumedCalories / totalCalories) * 100;
        document.getElementById('tracking-results').innerText = `A month has passed. Final tracking results before reset:\n
- Start Date: ${startDate.toDateString()}\n
- Current Date: ${currentDate.toDateString()}\n
- Total Consumed: ${consumedCalories} kcal\n
- Total Calorie Goal: ${totalCalories} kcal\n
- Progress Achieved: ${Math.floor(progressPercent)}%`;
        document.getElementById('reset-section').style.display = 'block';
        resetProgress();
        startDate = calculateNextMonthDate(startDate);
        totalCalories = dailyCalorieGoal * 30;
        document.getElementById('total-calories-display').innerText = totalCalories + ' kcal';
        document.getElementById('new-tracking-period').innerText = `New tracking period started from ${startDate.toDateString()}.`;
        document.getElementById('final-results').innerText = `Tracking restarted from ${startDate.toDateString()}. Total monthly calorie goal is now ${totalCalories} kcal.`;
    }
}
function updateDisplayedDates() {
    document.getElementById('current-date-display').innerText = new Date().toDateString();
    if (startDate) {
        document.getElementById('start-date-display').innerText = startDate.toDateString();
    } else {
        document.getElementById('start-date-display').innerText = 'Not set';
    }
}
function addCalories() {
    checkAndResetProgress();
    const caloriesInput = document.getElementById('calories-consumed').value;
    if (caloriesInput === '' || isNaN(caloriesInput)) {
        alert('Please enter a valid number of calories.');
        return;
    }
    const calories = parseInt(caloriesInput);
    consumedCalories += calories;
    const progressPercent = (consumedCalories / totalCalories) * 100;
    document.getElementById('progress-bar').style.width = progressPercent + '%';
    document.getElementById('progress-text').innerText = 'Progress: ' + Math.floor(progressPercent) + '%';
    const remainingCalories = totalCalories - consumedCalories;
    document.getElementById('remaining-calories-display').innerText = `Remaining Calories for the Month: ${remainingCalories} kcal`;
    updateDisplayedDates(); // Call to update the dates
}
function resetProgress() {
    consumedCalories = 0;
    document.getElementById('progress-bar').style.width = '0%';
    document.getElementById('progress-text').innerText = 'Progress: 0%';
    document.getElementById('remaining-calories-display').innerText = `Remaining Calories for the Month: ${totalCalories} kcal`;
    alert('Calorie progress has been reset.');
}
window.onload = function () {
    updateDisplayedDates();
};

class MealPlan {
    constructor(type) {
        this.type = type;
    }

    getVegetarianPlan() {
        return `
        <h4>Vegetarian</h4>
        <ol>
            <li>Breakfast: Oatmeal with fruits</li>
            <li>Lunch: Grilled vegetable sandwich</li>
            <li>Dinner: Stir-fried veggies with quinoa</li>
        </ol>
    `;
    }

    getNonVegetarianPlan() {
        return `
        <h4>Non-Vegetarian</h4>
        <ol>
            <li>Breakfast: Eggs with toast</li>
            <li>Lunch: Grilled chicken salad</li>
            <li>Dinner: Salmon with steamed vegetables</li>
        </ol>
    `;
    }
}

class SlimMealPlan extends MealPlan {
    constructor() {
        super("Slim");
    }

    getVegetarianPlan() {
        return `
        <h4>Vegetarian - Slim Plan</h4>
        <ol>
            <li>Breakfast: Smoothie with spinach and banana (300 kcal)</li>
            <li>Lunch: Quinoa salad with mixed veggies (400 kcal)</li>
            <li>Dinner: Grilled vegetable skewers (400 kcal)</li>
        </ol>
    `;
    }

    getNonVegetarianPlan() {
        return `
        <h4>Non-Vegetarian - Slim Plan</h4>
        <ol>
            <li>Breakfast: Scrambled eggs with avocado (350 kcal)</li>
            <li>Lunch: Chicken Caesar salad (450 kcal)</li>
            <li>Dinner: Grilled fish with broccoli (400 kcal)</li>
        </ol>
    `;
    }
}

class BulkMealPlan extends MealPlan {
    constructor() {
        super("Bulk");
    }

    getVegetarianPlan() {
        return `
        <h4>Vegetarian - Bulk Plan</h4>
        <ol>
            <li>Breakfast: Oatmeal with peanut butter (500 kcal)</li>
            <li>Lunch: Chickpea curry with rice (700 kcal)</li>
            <li>Dinner: Pasta with pesto and roasted vegetables (600 kcal)</li>
        </ol>
    `;
    }

    getNonVegetarianPlan() {
        return `
        <h4>Non-Vegetarian - Bulk Plan</h4>
        <ol>
            <li>Breakfast: Omelette with cheese and toast (600 kcal)</li>
            <li>Lunch: Beef steak with mashed potatoes (750 kcal)</li>
            <li>Dinner: Grilled chicken with rice (700 kcal)</li>
        </ol>
    `;
    }
}

class ToneMealPlan extends MealPlan {
    constructor() {
        super("Tone");
    }

    getVegetarianPlan() {
        return `
        <h4>Vegetarian - Tone Plan</h4>
        <ol>
            <li>Breakfast: Chia pudding with fruits (300 kcal)</li>
            <li>Lunch: Lentil salad with feta (400 kcal)</li>
            <li>Dinner: Zucchini noodles with marinara sauce (400 kcal)</li>
        </ol>
    `;
    }

    getNonVegetarianPlan() {
        return `
        <h4>Non-Vegetarian - Tone Plan</h4>
        <ol>
            <li>Breakfast: Boiled eggs with toast (350 kcal)</li>
            <li>Lunch: Grilled turkey sandwich (450 kcal)</li>
            <li>Dinner: Grilled shrimp with veggies (400 kcal)</li>
        </ol>
    `;
    }
}

function generateMealPlan() {
    const mealPlanDiv = document.getElementById('meal-plan');
    let mealPlan = '';
    const isVegetarian = document.querySelector('input[name="diet"]:checked').value === 'vegetarian';

    let selectedPlan;
    if (bodyTypeSelected === 'slim') {
        selectedPlan = new SlimMealPlan();
    } else if (bodyTypeSelected === 'bulk') {
        selectedPlan = new BulkMealPlan();
    } else if (bodyTypeSelected === 'tone') {
        selectedPlan = new ToneMealPlan();
    }

    if (isVegetarian) {
        mealPlan = selectedPlan.getVegetarianPlan();
    } else {
        mealPlan = selectedPlan.getNonVegetarianPlan();
    }

    mealPlanDiv.innerHTML = mealPlan;
}