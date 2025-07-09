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
    const storedTheme = localStorage.getItem("theme");

    if (!storedTheme) {
        const userPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
        storedTheme = userPrefersDark ? 'dark' : 'light';
        localStorage.setItem('theme', storedTheme);
        themeicon.textContent = storedTheme == 'dark' ? "light_mode" : "dark_mode";
    }

    document.body.classList.add(storedTheme);

    window.toggleTheme = function () {
        let currentTheme = localStorage.getItem('theme');
        let newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        localStorage.setItem('theme', newTheme);

        document.body.classList.remove(currentTheme);
        document.body.classList.add(newTheme);

        if (themeicon) {
            themeicon.textContent = newTheme === 'dark' ? "dark_mode" : "light_mode";
        }
    };
});
//Theme Toggle End