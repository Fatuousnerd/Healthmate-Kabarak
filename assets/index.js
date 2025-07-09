var loader = document.getElementById("loader"),
    themetoggle = document.getElementById("themetoggle"),
    themeicon = document.getElementById("themeicon"),
    load = document.getElementById("load");

    //End of Variable Declaration

//Loading Animation Begin
window.addEventListener("load", () => {
    loader.style.display = "none";
    load.style.display = "none";
});
//Loading Animation End

//Theme Toggle Begin
window.addEventListener('load', () => {
    const userPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
    const storedTheme = localStorage.getItem("theme");

    if (!storedTheme) {
        if (userPrefersDark == true) {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }
    } else {
        document.body.className = storedTheme;
    }

    window.toggleTheme = function () {
        if (storedTheme == 'dark') {
            localStorage.setItem('theme', 'light');
            document.body.classList.toggle("light");
            document.body.classList.toggle("dark");
            document.alert("Was Dark")
        } else if (storedTheme == 'light') {
            localStorage.setItem('theme', 'dark');
            document.body.classList.toggle("dark");
            document.body.classList.toggle("light");
            document.alert("Was Light")
        };
    };
});
//Theme Toggle End