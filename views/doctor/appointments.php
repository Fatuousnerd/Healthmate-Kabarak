<!-- <?php
// include '../../includes/fetchappointments.php';
?> -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="../../assets/index.js" defer></script>
    <link rel="stylesheet" href="../../assets/index.css">
    <link rel="shortcut icon" href="../../assets/logo2.jpeg" type="image/x-icon">
</head>

<body class="">

    <!--Loader Start-->
    <div class="loader" id="loader">
        <div class="load" id="load"></div>
    </div>
    <!--Loader End-->

    <!--Mobile Hamburger Menu-->
    <button onclick="toggleSide()" id="side-btn"
        class="fixed flex lg:hidden items-center justify-center bg-[var(--MainText)] text-[var(--SecBg)] p-[20px]! w-[50px] h-[50px] rounded-full mt-[15px]! ml-[15px]! z-[99] "><i
            class="material-symbols-outlined">menu</i></button>
    <!--Mobile Hamburger Menu-->

    <!--Sidebar Start-->
    <div id="sidebar" class="w-[300px] h-[100vh] hidden lg:flex flex-col fixed justify-around bg-[var(--SecBg)] items-center p-[250px] z-[98] ">
        <a href="/" class="">
            <img src="../../assets/logo1.png" id="logo" alt="Healthmate Kabarak" class="h-[100px] object-fit ">
        </a>
        <ul class="flex flex-col gap-[25px] text-2xl">
            <a class="flex items-center justify-start gap-[25px] hover:text-[var(--Highlight)]"
                href="/views/doctor/dashboard.php"><i class="material-symbols-outlined">home_app_logo</i> Dashboard</a>
            <a class="flex items-center justify-start gap-[25px] hover:text-[var(--Highlight)]"
                href="/views/doctor/appointments.php"><i class="material-symbols-outlined">pill</i> Prescriptions</a>
            <a class="flex items-center justify-start gap-[25px] hover:text-[var(--Highlight)]"
                href="/views/doctor/patients.php"><i class="material-symbols-outlined">personal_injury</i> Patients</a>
            <a class="flex items-center justify-start gap-[25px] hover:text-[var(--Highlight)]"
                href="/views/doctor/appointments.php"><i class="material-symbols-outlined">av_timer</i>
                Appointments</a>
            <a class="flex items-center justify-start gap-[25px] hover:text-[var(--Highlight)]"
                href="/views/doctor/profile.php"><i class="material-symbols-outlined">settings</i> Settings</a>
        </ul>
        <a href="/auth/logout.php" class="flex items-center justify-start gap-[25px]">
            <i class="material-symbols-outlined">logout</i>
            Logout
        </a>
    </div>
    <!--Sidebar End-->

    <!--Topbar Start-->
    <div
        class="flex w-full lg:w-[calc(100vw-300px)] h-[70px] lg:left-[300px] fixed items-center justify-between p-[25px]! bg-[var(--SecBg)] border-b-solid border-b-[2px] border-b-[var(--Highlight)]">
        <h1 class="text-3xl font-bold hidden lg:flex ">Appointments</h1>
        <h1 class="text-3xl font-bold lg:hidden flex "></h1>
        <div class="flex items-center justify-center gap-[25px] ">
            <p id="timeText" class="font-bold">00:00</p>
            <a class="hover:text-[var(--Highlight)] flex items-center justify-center" href=""><i
                    class="material-symbols-outlined">notifications</i></a>
            <button class="themetoggle" id="themetoggle" onclick="toggleTheme()"><i id="themeicon"
                    class="material-symbols-outlined">brightness_4</i></button>
        </div>
    </div>
    <!--Topbar End-->

    <!--Calendar Section Start-->
    <div
        class="flex flex-col lg:flex-row gap-[25px] p-[25px]! justify-center items-center w-full lg:w-[calc(100vw-300px)] h-[calc(100vh-70px)] bg-[var(--MainBg)] relative lg:left-[300px] top-[70px]">


        <div id="calendar"
            class="flex-1 h-[500px] p-[25px]! m-[50px] bg-[var(--SecBg)] shadow rounded flex flex-col items-center justify-around">
            <div class="flex w-full items-center justify-between">
                <button id="prevMonth" class="scale-[1.75] font-bold hover:text-[var(--Highlight)] cursor-pointer"><i
                        class="material-symbols-outlined">arrow_circle_left</i></button>
                <h2 id="monthYear" class="text-xl font-semibold"></h2>
                <button id="nextMonth" class="scale-[1.75] font-bold hover:text-[var(--Highlight)] cursor-pointer"><i
                        class="material-symbols-outlined">arrow_circle_right</i></button>
            </div>
            <div class="grid grid-cols-7 gap-[25px] w-full text-center font-bold text-gray-600">
                <div>Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div>Sat</div>
            </div>
            <div id="daysGrid" class="grid grid-cols-7 gap-[25px] w-full mt-2 text-center"></div>
        </div>

        <div id="appointmentPanel"
            class="flex-1 w-full h-[500px] bg-[var(--SecBg)] rounded shadow flex flex-col justify-between p-[25px]! gap-[10px]">
            <h3 class="font-bold mb-2">Appointments on <span id="selectedDate"></span></h3>
            <ul id="appointmentsList" class="w-full h-full overflow-y-auto flex flex-col gap-[10px] items-center">
                <li class="text-center text-xl list-none ">Click on a date to view appointments.</li>
            </ul>
            <div class="flex gap-[10px]">
                <input id="newAppointment" type="text" placeholder="Add new appointment"
                    class="w-full border rounded p-[5px]! placeholder:text-[var(--SecText)]">
                <button onclick="addAppointment()"
                    class="bg-[var(--Highlight)] flex items-center justify-center text-white p-[5px]! rounded cursor-pointer"><i
                        class="material-symbols-outlined">add</i></button>
            </div>
        </div>
    </div>
    <!--Calendar Section End-->

    <style>
        #daysGrid div {
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        #daysGrid div:hover {
            background-color: var(--MainBg);
        }

        .today {
            background: var(--Highlight);
            color: var(--MainText);
        }
    </style>

    <script>
        let current = new Date();
        // let appointments = {}; // store as {'YYYY-MM-DD': ['Patient A', 'Patient B']}

        function renderCalendar(date = new Date()) {
            const year = date.getFullYear();
            const month = date.getMonth();
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            const monthYear = document.getElementById("monthYear");
            monthYear.textContent = `${date.toLocaleString('default', { month: 'long' })} ${year}`;

            const daysGrid = document.getElementById("daysGrid");
            daysGrid.innerHTML = '';

            // Fill blanks before 1st day
            for (let i = 0; i < firstDay; i++) {
                daysGrid.innerHTML += `<div></div>`;
            }

            // const todayStr = new Date().toISOString().split("T")[0];
            function formatDateLocal(d) {
                return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
            }
            const todayStr = formatDateLocal(new Date());

            for (let day = 1; day <= daysInMonth; day++) {
                const d = new Date(year, month, day);
                const dayStr = formatDateLocal(d);
                const isToday = dayStr === todayStr;

                const dayDiv = document.createElement("div");
                dayDiv.textContent = day;
                dayDiv.className = isToday ? 'today' : '';
                dayDiv.onclick = () => showAppointments(dayStr);

                daysGrid.appendChild(dayDiv);
            }
        }

        function showAppointments(dateStr) {
            const panel = document.getElementById("appointmentPanel");
            const displayDate = document.getElementById("selectedDate");
            const list = document.getElementById("appointmentsList");

            panel.classList.add("h-[500px]");
            displayDate.textContent = dateStr;
            list.innerHTML = '';

            fetch(`../../includes/doctor/fetchappointments.php?date=${dateStr}`)
                .then(res => res.json())
                .then(data => {
                    list.innerHTML = '';
                    if (data.error) {
                        list.innerHTML = `<li class="text-center text-xl list-none ">${data.error}</li>`;
                    } else if (data.length === 0) {
                        list.innerHTML = `<li class="text-center text-xl list-none ">No appointments found.</li>`;
                    } else {
                        data.forEach(appointment => {
                            const card = document.createElement("div");
                            card.className = "card w-full h-[70px] overflow-hidden flex flex-col transition-all duration-[.5s] ease-in-out";
                            // card.id = "card";
                            card.innerHTML = `
                                                <div class="p-3! bg-[var(--MainBg)] rounded shadow text-sm flex w-full justify-between items-center">
                                                    <div class="flex flex-col">
                                                        <div class="font-bold text-2xl">${appointment.student}</div>
                                                        <div class="text-[var(--SecText)]">${appointment.reason}</div>
                                                    </div>
                                                    <div class="flex items-center gap-[15px]">
                                                        <button onclick="openEdit(this)" class="bg-[var(--Highlight)] text-[var(--MainText)] rounded p-1! cursor-pointer flex items-center justify-center"><i class="material-symbols-outlined">edit</i></button>
                                                    </div>
                                                </div>
                                                <div class="w-full h-[30px] flex items-center justify-center bg-[rgba(0,0,0,0.5)] backdrop-blur-sm">
                                                    <form action="../../includes/updateappointments.php" method="post" class="flex gap-2">
                                                        <input type="hidden" name="appointment_id" value="${appointment.id}">
                                                        <button name="status" value="approved" class="bg-[var(--MainBg)] p-[5px]! flex items-center justify-center rounded"><i class="material-symbols-outlined">check</i></button>
                                                        <button name="status" value="completed" class="bg-[var(--MainBg)] p-[5px]! flex items-center justify-center rounded"><i class="material-symbols-outlined">done_all</i></button>
                                                        <button name="status" value="cancelled" class="bg-[var(--MainBg)] p-[5px]! flex items-center justify-center rounded"><i class="material-symbols-outlined">cancel</i></button>
                                                    </form>
                                                </div>
                                            `;

                            list.appendChild(card);
                        });
                    }
                })
                .catch(() => {
                    list.innerHTML = `<li class="text-center text-xl list-none ">Error fetching appointments.</li>`;
                });


            // Save selected date to window scope
            window.selectedDate = dateStr;
        }

        function addAppointment() {
            const input = document.getElementById("newAppointment");
            const text = input.value.trim();
            if (!text || !window.selectedDate) return;

            if (!appointments[window.selectedDate]) appointments[window.selectedDate] = [];
            appointments[window.selectedDate].push(text);

            input.value = '';
            showAppointments(window.selectedDate);
        }

        document.getElementById("prevMonth").onclick = () => {
            current.setMonth(current.getMonth() - 1);
            renderCalendar(current);
        };

        document.getElementById("nextMonth").onclick = () => {
            current.setMonth(current.getMonth() + 1);
            renderCalendar(current);
        };

        // Init
        renderCalendar(current);

       function openEdit(button){
           const card = button.closest(".card");
           card.classList.toggle("h-[120px]");
       }
    </script>


</body>

</html>