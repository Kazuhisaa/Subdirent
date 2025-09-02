import 'bootstrap';


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



document.addEventListener("DOMContentLoaded", function() {
    const unitModal = document.getElementById('unitModal');
    const bookNowBtn = document.getElementById('bookNowBtn');

    unitModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;

        let title = button.getAttribute('data-title');
        let img = button.getAttribute('data-img');
        let desc = button.getAttribute('data-description');
        let price = button.getAttribute('data-price');
        let unitCode = button.getAttribute('data-unit');

       
        document.getElementById('unitModalLabel').textContent = title;
        document.getElementById('unitModalImg').src = img;
        document.getElementById('unitModalDesc').textContent = desc;
        document.getElementById('unitModalPrice').textContent = price;

       
        bookNowBtn.onclick = function () {
            localStorage.setItem('selectedUnit', unitCode);
            localStorage.setItem('selectedPrice', price);
            localStorage.setItem('selectedTitle', title);
            window.location.href = "/apply";
        };
    });
});


document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("applyForm");
    const successMsg = document.getElementById("successMsg");
    const unitInput = document.getElementById("unit");

    // If a unit was clicked in the modal, we store it in localStorage
    const selectedUnit = localStorage.getItem("selectedUnit");
    if (selectedUnit) {
        unitInput.value = selectedUnit;
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        // For now: just simulate success
        form.classList.add("d-none");
        successMsg.classList.remove("d-none");

        // Later: backend devs can POST this data to Laravel
        console.log({
            name: document.getElementById("name").value,
            email: document.getElementById("email").value,
            contact: document.getElementById("contact").value,
            unit: document.getElementById("unit").value,
        });
    });
});
document.querySelectorAll('.unit-img').forEach(img => {
    img.addEventListener('click', function() {
        document.getElementById('unitModalImg').src = this.dataset.img;
        document.getElementById('unitModalTitle').innerText = this.dataset.title;
        document.getElementById('unitModalDesc').innerText = this.dataset.description;
        document.getElementById('unitModalPrice').innerText = this.dataset.price;

        // Update modal Book Now button with selected unit
        const modalBtn = document.getElementById('bookNowBtn');
        modalBtn.dataset.unit = this.dataset.unit;
    });
});
