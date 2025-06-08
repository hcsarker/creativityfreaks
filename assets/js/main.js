// Toggle Mobile Menu
function toggleMobileMenu() {
  const nav = document.getElementById("navLinks");
  if (nav) nav.classList.toggle("show");
}

// Toggle Profile Dropdown
function toggleProfileMenu() {
  const dropdown = document.getElementById("dropdown");
  if (dropdown) dropdown.classList.toggle("hidden");
}

// Close dropdown when clicking outside
document.addEventListener("click", function (e) {
  const dropdown = document.getElementById("dropdown");
  const avatar = document.querySelector(".avatar");

  if (dropdown && !dropdown.classList.contains("hidden")) {
    if (!dropdown.contains(e.target) && avatar && !avatar.contains(e.target)) {
      dropdown.classList.add("hidden");
    }
  }
});

// Modal toggle
function toggleModal() {
  const modal = document.getElementById("authModal");
  if (modal) modal.classList.toggle("hidden");
}

// Switch between login/register
function showForm(type) {
  const loginForm = document.getElementById("loginForm");
  const registerForm = document.getElementById("registerForm");

  if (loginForm && registerForm) {
    if (type === 'login') {
      loginForm.classList.remove("hidden");
      registerForm.classList.add("hidden");
    } else {
      registerForm.classList.remove("hidden");
      loginForm.classList.add("hidden");
    }
  }
}

// Testimonial slider
let currentSlide = 0;
document.addEventListener("DOMContentLoaded", function () {
  const slides = document.querySelectorAll('.testimonial-slide');

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.remove('active');
      if (i === index) slide.classList.add('active');
    });
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  }

  if (slides.length > 0) {
    showSlide(currentSlide);
    setInterval(nextSlide, 4000);
  }

  // Counter animation
  const counters = document.querySelectorAll('.counter');

  counters.forEach(counter => {
    const updateCount = () => {
      const target = +counter.getAttribute('data-target');
      const current = +counter.innerText;
      const increment = Math.ceil(target / 100);

      if (current < target) {
        counter.innerText = current + increment;
        setTimeout(updateCount, 30);
      } else {
        counter.innerText = target;
      }
    };

    updateCount();
  });

  // FAQ toggle
  const faqs = document.querySelectorAll(".faq-question");
  faqs.forEach(faq => {
    faq.addEventListener("click", () => {
      const item = faq.parentElement;
      item.classList.toggle("active");
    });
  });

  // Smooth scrolling
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });

  // Newsletter form
  const newsletterForm = document.querySelector('.newsletter-form');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const email = this.querySelector('input[type="email"]').value;

      if (email) {
        alert("Thank you for subscribing, " + email + "!");
        this.reset();
      }
    });
  }
});
