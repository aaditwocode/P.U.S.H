document.querySelector('.btn').addEventListener('click', function () {
    alert('Consultation request submitted!');
});

document.querySelectorAll('nav a').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector('nav a.active').classList.remove('active');
        this.classList.add('active');
    });
});

function exploreService(service) {
    const serviceInfo = {
        individual: {
            title: "Individual Therapy",
            details: "Our individual therapy sessions provide a safe, confidential space for personal growth and healing. Work one-on-one with our trained professionals to address your specific needs."
        },
        couple: {
            title: "Couple Therapy",
            details: "Whether married or in a relationship, our couple therapy helps improve communication, resolve conflicts, and strengthen your bond together."
        },
        teen: {
            title: "Teen Counseling",
            details: "Specialized counseling services designed specifically for teenagers, addressing their unique challenges in a comfortable and understanding environment."
        }
    };

    const selectedService = serviceInfo[service];

    const modal = document.createElement('div');
    modal.className = 'modal';

    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay';

    modal.innerHTML = `
        <h2 style="margin-top: 0; color: #333;">${selectedService.title}</h2>
        <p style="color: #666; line-height: 1.6;">${selectedService.details}</p>
        <button onclick="closeModal()" class="modal-button">Close</button>
        <button onclick="bookMeeting('${service}')" class="modal-button">Book a Meeting</button>
    `;

    document.body.appendChild(overlay);
    document.body.appendChild(modal);
}

function closeModal() {
    document.querySelector('.modal').remove();
    document.querySelector('.modal-overlay').remove();
}

function bookMeeting(service) {
    alert(`Booking a meeting for ${service} service.`);
    closeModal();
}

function redirectToWebsite() {
    window.location.href = "https://pmc.ncbi.nlm.nih.gov/articles/PMC1525119/";
}

function redirectToConsult() {
    window.location.href = `/contact.html`;
}
document.querySelectorAll('.start-meditation').forEach(button => {
    button.addEventListener('click', function () {
        const meditationClass = this.getAttribute('data-class');
        alert(`Starting ${meditationClass} meditation session.`);

        window.location.href = `/meditation.html`;
    });
});
