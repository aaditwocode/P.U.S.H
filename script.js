var Swiper = new Swiper(".mySwiper", {
  slidesPerView: 1,
  grabCursor: true,
  loop: true,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});

function subscribePlan(planName, price) {
  localStorage.setItem('selectedPlanName', planName);
  localStorage.setItem('selectedPlanPrice', price);
  window.location.href = 'payment.html';
}