document.addEventListener('DOMContentLoaded', () => {
    // 1. Logika untuk Mobile Menu (Hamburger)
    const menuToggle = document.getElementById('mobile-menu');
    const navLinks = document.querySelector('.nav-links');

    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            // Animasi sederhana untuk hamburger
            menuToggle.classList.toggle('is-active');
        });
    }

    // 2. Smooth Scroll untuk navigasi (Opsional)
    // Jika nanti kamu menambah section seperti "Tentang Kami" atau "Kontak"
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    console.log("SayurPraya: Index Script Loaded & Cleaned.");
});