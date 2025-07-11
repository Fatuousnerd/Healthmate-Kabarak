var loader = document.getElementById("loader"),
    load = document.getElementById("load"),
    themetoggle = document.getElementById("themetoggle"),
    themeicon = document.getElementById("themeicon"),
    logo = document.getElementById("logo"),
    dateText = document.getElementById("dateText"),
    timeText = document.getElementById("timeText");

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
        if (userPrefersDark == true) {
            localStorage.setItem('theme', 'dark')
        } else {
            localStorage.setItem('theme', 'light')
        }
        logo.src = userPrefersDark ? "../assets/logo1.png" : "/assets/logo2.png";
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

        if (logo) {
            logo.src = newTheme === 'dark' ? "/assets/logo1.png" : "/assets/logo2.png";
        }
    };
});
//Theme Toggle End

//Date Date
const now = new Date()
const options = {
    weekday: 'short',
    month: 'short',
    day: '2-digit',
    year: 'numeric',
}
const TDate = now.toLocaleDateString('en-US', options);


//Writes date anywhere it's called
window.addEventListener('load', () => {
    if (dateText) {
        dateText.textContent = TDate
    }
})

//Writes time anywhere it's called
function updateTime() {
    const nowTime = new Date();
    const hours = String(nowTime.getHours()).padStart(2, '0');
    const mins = String(nowTime.getMinutes()).padStart(2, '0');
    const secs = String(nowTime.getSeconds()).padStart(2, '0');
    const timeNow = `${hours}:${mins}:${secs}`
    if (timeText) {
        timeText.textContent = timeNow
    }
}
updateTime();
setInterval(updateTime, 1000);

// Date End
