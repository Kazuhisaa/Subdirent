//import 'bootstrap/dist/js/bootstrap.bundle.min.js';


document.addEventListener("DOMContentLoaded", () => {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    console.log("Subdirent App.js loaded ✅");
});

window.addEventListener("scroll", () => {
    const navbar = document.querySelector(".navbar");
    if (window.scrollY > 50) {
        navbar.classList.add("shadow", "bg-white", "navbar-light");
        navbar.classList.remove("bg-dark", "navbar-dark");
    } else {
        navbar.classList.remove("shadow", "bg-white", "navbar-light");
        navbar.classList.add("bg-dark", "navbar-dark");
    }
});

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener("click", function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute("href")).scrollIntoView({
            behavior: "smooth"
        });
    });
});
