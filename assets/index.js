var loader = document.getElementById("loader"),
    themetoggle = document.getElementById("themetoggle"),
    themeicon = document.getElementById("themeicon"),
    load = document.getElementById("load");

//Loading Animation Begin
window.addEventListener("load", () => {
    loader.style.display = "none";
    load.style.display = "none";
});
//Loading Animation End

//Theme Toggle Begin

//     window.addEventListener('load', () => {
//         var sessionTheme = localStorage.getItem("theme");
// document.body.classList(sessionTheme);
// if(!sessionTheme){
//     localStorage.setItem("light"); 
// } else{
//     localStorage.setItem("light");
// }
//     })

themetoggle.addEventListener("click", () => {
    document.body.classList.toggle("light");
    document.body.classList.toggle("dark");
    if (document.body.classList.contains("light")) {
        themeicon.textContent = "light_mode";
    } else {
        themeicon.textContent = "dark_mode";
    }
});