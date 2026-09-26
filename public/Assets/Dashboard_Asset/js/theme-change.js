 let chnage_theme = document.getElementById("chnage_theme");
  chnage_theme.addEventListener("click",()=>{
    document.body.classList.toggle("dark-mode");

    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("theme", "dark");
        chnage_theme.innerHTML = `<i class="bi bi-moon-fill"></i>`;

    } else {
        localStorage.setItem("theme", "light");
        chnage_theme.innerHTML = `<i class="bi bi-sun-fill"></i>`;
        
    }
  })
if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark-mode");
}