window.onscroll = function() {
    const scrollTopBtn = document.getElementById("scrollTopBtn");

    if (window.scrollY > 300) {
        scrollTopBtn.classList.replace('opacity-0', 'opacity-100');
    } else {
        scrollTopBtn.classList.replace('opacity-100', 'opacity-0');
    }
};

document.getElementById("scrollTopBtn").onclick = () => window.scrollTo({top: 0, behavior: "smooth"});