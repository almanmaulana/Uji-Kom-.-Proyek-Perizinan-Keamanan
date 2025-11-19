document.addEventListener("DOMContentLoaded", () => {
    const body = document.body;
    const modeToggle = body.querySelector(".mode-toggle");
    const sidebar = body.querySelector("nav");
    const sidebarToggle = body.querySelector(".sidebar-toggle");
    const overlay = document.querySelector(".sidebar-overlay");

    // === Dark Mode ===
    if (modeToggle) {
        modeToggle.addEventListener("click", (e) => {
            e.preventDefault();
            body.classList.toggle("dark");
            localStorage.setItem("theme", body.classList.contains("dark") ? "dark" : "light");
        });
        if (localStorage.getItem("theme") === "dark") {
            body.classList.add("dark");
        }
    }

    // === Sidebar Toggle ===
    if (sidebar && sidebarToggle) {
        const isDesktop = window.innerWidth > 768;
        if (isDesktop && localStorage.getItem("sidebar") === "close") {
            sidebar.classList.add("close");
        }
        
        sidebarToggle.addEventListener("click", () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle("active");
                overlay.classList.toggle("active");
            } else {
                sidebar.classList.toggle("close");
                localStorage.setItem("sidebar", sidebar.classList.contains("close") ? "close" : "open");
            }
        });

        if (overlay) {
            overlay.addEventListener("click", () => {
                sidebar.classList.remove("active");
                overlay.classList.remove("active");
            });
        }
    }
});
