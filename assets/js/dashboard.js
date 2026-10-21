const hamburger = document.getElementById("hamburger");
const sidebar = document.getElementById("sidebar");
const closeSidebar = document.getElementById("closeSidebar");
const sidebarOverlay = document.getElementById("sidebarOverlay");

function openSidebar() {
    sidebar.classList.add("active");
    sidebarOverlay.classList.add("active");
}

function closeSidebarMenu() {
    sidebar.classList.remove("active");
    sidebarOverlay.classList.remove("active");
}

hamburger.addEventListener("click", openSidebar);

closeSidebar.addEventListener("click", closeSidebarMenu);

sidebarOverlay.addEventListener("click", closeSidebarMenu); 