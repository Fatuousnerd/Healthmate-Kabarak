<?php

include_once '../../includes/getuser.php';
include_once '../../includes/doctor/tallyapp.php';
include_once '../../includes/doctor/tallypatients.php';

if (!$user || $_SESSION['user']['role'] !== 'doctor') {
    header("Location: ../../auth/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
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
    <div id="sidebar"
        class="w-[300px] h-[100vh] fixed hidden lg:flex flex-col fixed justify-around bg-[var(--SecBg)] items-center p-[250px] z-[98] duration-300 transition-all">
        <a href="/" class="">
            <img src="../../assets/logo1.png" id="logo" alt="Healthmate Kabarak" class="h-[100px] object-fit ">
        </a>
        <ul class="flex flex-col gap-[25px] text-2xl">
            <a class="flex items-center justify-start gap-[25px] hover:text-[var(--Highlight)]"
                href="/views/doctor/dashboard.php"><i class="material-symbols-outlined">home_app_logo</i>
                Dashboard</a>
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

    <!--Main Section Start-->
    <div
        class="w-full lg:w-[calc(100vw-300px)] h-[150vh] lg:h-screen lg:left-[300px] relative flex flex-col justify-around gap-[25px] p-[25px]!">
        <div class="flex flex-col lg:flex-row lg:flex-1 items-center justify-between gap-[25px] w-full">

            <!--Container Start-->
            <div
                class="flex flex-col w-full lg:w-1/3 items-start justify-around h-[200px] lg:h-full border-solid border-[2px] border-[var(--SecBg)] rounded-md p-[25px]! py-[10px]!">

                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center justify-center gap-[5px]!">
                        <i class="material-symbols-outlined">av_timer</i>
                        <p class="text-2xl font-bold">Appointments</p>
                    </div>
                    <button class="flex items-center justify-center"><i
                            class="material-symbols-outlined">more_horiz</i></button>
                </div>

                <h1 class="text-5xl font-extrabold"><?php echo $totalAppointments; ?></h1>
            </div>
            <!--Container End-->

            <!--Container Start-->
            <div
                class="flex flex-col w-full lg:w-2/3 items-start justify-around h-[200px] lg:h-full border-solid border-[2px] border-[var(--SecBg)] rounded-md p-[25px]! py-[10px]!">

                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center justify-center gap-[5px]!">
                        <i class="material-symbols-outlined">personal_injury</i>
                        <p class="text-2xl font-bold">Patients</p>
                    </div>
                    <button class="flex items-center justify-center"><i
                            class="material-symbols-outlined">more_horiz</i></button>
                </div>

                <h1 class="text-5xl font-extrabold"><?php echo $totalPatients; ?></h1>
            </div>
            <!--Container End-->

        </div>

        <div class="flex flex-col-reverse lg:flex-row lg:flex-[2] items-center gap-[25px] w-full lg:max-h-[300px]">
            <!--Container Start-->
            <div
                class="flex flex-col w-full lg:w-2/3 items-start h-[300px] lg:h-full border-solid border-[2px] border-[var(--SecBg)] rounded-md p-[10px]! overflow-y-hidden ">

                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center justify-center gap-[5px]!">
                        <i class="material-symbols-outlined">personal_injury</i>
                        <p class="text-2xl font-bold">Patients</p>
                    </div>
                    <button class="flex items-center justify-center"><i
                            class="material-symbols-outlined">more_horiz</i></button>
                </div>

                <div id="patientsList"
                    class="flex flex-col w-full overflow-y-auto lg:h-[calc(100%-50px)] gap-[10px] p-[10px]!"
                    style="scrollbar-width: none;">

                </div>
            </div>
            <!--Container End-->
            <!--Container Start-->
            <div
                class="flex flex-col w-full lg:w-1/3 items-start justify-around h-[300px] lg:h-full border-solid border-[2px] border-[var(--SecBg)] rounded-md p-[10px]!">

                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center justify-center gap-[5px]!">
                        <i class="material-symbols-outlined">calendar_today</i>
                        <p class="text-2xl font-bold">Calendar</p>
                    </div>
                    <button class="flex items-center justify-center"><i
                            class="material-symbols-outlined">more_horiz</i></button>
                </div>

                <!--Calendar Start-->
                <div id="calendar"
                    class="w-full bg-[var(--SecBg)] shadow rounded flex flex-col items-center justify-start p-[5px]!">
                    <div class="flex w-full items-center justify-between">
                        <button id="prevMonth" class="font-bold hover:text-[var(--Highlight)] cursor-pointer"><i
                                class="material-symbols-outlined">arrow_circle_left</i></button>
                        <h2 id="monthYear" class="font-semibold"></h2>
                        <button id="nextMonth" class="font-bold hover:text-[var(--Highlight)] cursor-pointer"><i
                                class="material-symbols-outlined">arrow_circle_right</i></button>
                    </div>
                    <div class="grid grid-cols-7  w-full text-center font-bold text-gray-600">
                        <div>Sun</div>
                        <div>Mon</div>
                        <div>Tue</div>
                        <div>Wed</div>
                        <div>Thu</div>
                        <div>Fri</div>
                        <div>Sat</div>
                    </div>
                    <div id="daysGrid" class="grid grid-cols-7 gap-[15px] w-full max-h-11/12 text-center"></div>
                </div>
                <!--Calendar End-->
            </div>
            <!--Container End-->
        </div>

        <div class="flex lg:flex-1 items-center gap-[25px] w-full">
            <!--Container Start-->
            <div
                class="hidden lg:flex flex-col w-2/3 items-start justify-around h-full border-solid border-[2px] border-[var(--SecBg)] rounded-md p-[25px]! py-[10px]!">

            </div>
            <!--Container End-->
            <!--Container Start-->
            <div
                class="flex flex-col w-full lg:w-1/3 items-start justify-around h-full border-solid border-[2px] border-[var(--SecBg)] rounded-md p-[25px]! py-[10px]!">

                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center justify-center gap-[5px]!">
                        <i class="material-symbols-outlined">person</i>
                        <p class="text-2xl font-bold">Profile</p>
                    </div>
                    <button class="flex items-center justify-center"><i
                            class="material-symbols-outlined">more_horiz</i></button>
                </div>

                <div class="flex items-center justify-start gap-[25px] w-full">
                    <img src="../../includes/uploads/<?= $user['profile_pic'] ?>" alt=""
                        class="w-[50px] h-[50px] rounded-full border-[2px] border-[var(--Highlight)]">
                    <h1 class="text-[1.5rem] font-extrabold"><?= $user ? htmlspecialchars($user['name']) : 'Guest' ?>
                    </h1>
                </div>
            </div>
            <!--Container End-->
        </div>
    </div>
    <!--Main Section End-->

    <script>
        let current = new Date();
        let appointments = {}; // store as {'YYYY-MM-DD': ['Patient A', 'Patient B']}

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

        let today = new Date();

        function formatDateLocal(d) {
            return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
        }
        const todayStr = formatDateLocal(new Date());

        function showPatients(todayStr) {
            const patientsList = document.getElementById('patientsList');
            patientsList.innerHTML = ''; // Clear existing content

            fetch(`../../includes/doctor/fetchpatients.php?date=${todayStr}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        patientsList.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                    } else if (data.length === 0) {
                        patientsList.innerHTML = `<p class="text-gray-500">No patients found for today.</p>`;
                    } else {
                        data.forEach(patient => {
                            // console.log(data);

                            const patientItem = document.createElement('div');
                            patientItem.className = 'flex bg-[var(--SecBg)] items-center justify-between w-full p-[10px]! rounded-md';
                            patientItem.innerHTML = `
                                                    <img src="../../includes/uploads/${patient.profile}" alt="${patient.student} Profile Picture" class="w-[50px] h-[50px] rounded-full">
                                                    <h1>${patient.student}</h1>
                                                    <p>${patient.time}</p>
                                                    `;
                            patientsList.appendChild(patientItem);
                        });
                    }
                })
                .catch(error => console.error('Error fetching patients:', error));
        }
        showPatients(todayStr);
    </script>

    <style>
        #daysGrid div {
            /* padding: 10px; */
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
</body>

</html>