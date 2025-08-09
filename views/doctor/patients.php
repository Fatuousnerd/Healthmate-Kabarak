<?php

include '../../includes/getuser.php';

if (!$user || $user['role'] !== 'doctor') {
    header("Location: ../../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients</title>
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
                href="/views/doctor/dashboard.php"><i class="material-symbols-outlined">home_app_logo</i>
                Dashboard</a>
            <a class="flex items-center justify-start gap-[25px] hover:text-[var(--Highlight)]"
                href="/views/doctor/patients.php"><i class="material-symbols-outlined">personal_injury</i> Patients</a>
            <a class="flex items-center justify-start gap-[25px] hover:text-[var(--Highlight)]"
                href="/views/doctor/history.php"><i class="material-symbols-outlined">history</i>
                History</a>
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
        <h1 class="text-3xl font-bold hidden lg:flex ">Patients</h1>
        <h1 class="text-3xl font-bold lg:hidden flex "></h1>
        <div class="flex items-center justify-center gap-[25px] ">
            <p id="timeText" class="font-bold">00:00</p>
            <button class="themetoggle" id="themetoggle" onclick="toggleTheme()"><i id="themeicon"
                    class="material-symbols-outlined">brightness_4</i></button>
        </div>
    </div>
    <!--Topbar End-->

    <!--Main Section Start-->
    <div class="flex flex-col lg:flex-row gap-[5px] lg:w-[calc(100vw-300px)] h-[calc(100vh-70px)] lg:left-[300px] top-[70px] p-[15px]! relative">
        <div
            class="flex flex-col justify-around flex-1 bg-[var(--SecBg)] px-[25px]! py-[15px]! gap-[15px] overflow-y-hidden">
            <h1 class="text-2xl font-bold">Patient List</h1>

            <div class="flex items-center justify-between gap-[15px]">
                <div
                    class="flex items-center justify-center border-solid border-[1.5px] border-[var(--Highlight)] rounded-[5px] ">
                    <input type="text" name="" id="searchInput"
                        class="bg-transparent flex-1 p-[5px]! h-full placeholder:text-[var(--MainText)] border-none focus:outline-none focus:border-none"
                        placeholder="Search patients...">
                    <button
                        class=" text-[var(--MainText)] rounded-[2.5px] p-[5px]! flex items-center justify-center h-full p-[5px]! cursor-pointer"><i
                            class="material-symbols-outlined">search</i></button>
                </div>
                <!-- <button
                    class="rounded-[5px] p-[5px]! flex items-center justify-center h-full p-[5px]! cursor-pointer bg-[var(--Highlight)]"><i
                        class="material-symbols-outlined">add</i></button> -->
            </div>

            <div id="patientList" class="flex flex-col h-full gap-[5px]! overflow-y-scroll"
                style="scrollbar-width: none;">

            </div>
        </div>

        <div id="patientDetail" class="flex flex-col flex-[2] bg-[var(--SecBg)]">
            <p class="text-gray-400 text-center">Select a patient to view their details</p>
        </div>
    </div>
    <!--Main Section End-->

    <script>
        let today = new Date();

        function formatDateLocal(d) {
            return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
        }
        const todayStr = formatDateLocal(new Date());

        function showPatients(todayStr) {
            const patientList = document.getElementById('patientList');
            patientList.innerHTML = ''; // Clear existing content

            fetch(`../../includes/doctor/fetchpatients.php?date=${todayStr}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        patientList.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                    } else if (data.length === 0) {
                        patientList.innerHTML = `<p class="text-gray-500">No patients found for today.</p>`;
                    } else {
                        data.forEach(patient => {
                            const patientItem = document.createElement('button');
                            patientItem.className = 'flex items-center justify-around p-[10px]! bg-[var(--MainBg)] rounded-md cursor-pointer hover:border-[var(--Highlight)] border-transparent border-solid border-[2px] transition-all hover:duration-300';
                            patientItem.innerHTML = `
                                                    <!--Patient Holder Start-->
                                                        <img src="../../includes/uploads/${patient.profile}" alt="${patient.student} Profile Picture"
                                                            class="w-[75px] h-[75px] rounded-full object-fit border-[2px] border-[var(--Highlight)] border-solid">
                                                        <div class="flex flex-col justify-between items-start ml-[10px]">
                                                            <h1 class="text-2xl font-semibold">${patient.student}</h1>
                                                            <!--<p class="text-sm text-[var(--SecText)]">Age: ${patient.age}</p>
                                                            <p class="text-sm text-[var(--SecText)]">Sex: ${patient.sex}</p>-->
                                                        </div>
                                                    <!--Patient Holder End-->
                            `;
                            patientItem.onclick = () => loadPatients(patient);
                            patientList.appendChild(patientItem);
                        });
                    }
                })
                .catch(error => console.error('Error fetching patients:', error));
        }

        showPatients(todayStr);

        function loadPatients(patient) {
            const detail = document.getElementById('patientDetail');
            detail.innerHTML = `
        <div class="flex items-center justify-center gap-[35px] p-[25px]!">
            <img src="../../includes/uploads/${patient.profile || 'default.png'}" alt="${patient.student}'s Profile Picture"
                class="w-[100px] h-[100px] rounded-full object-fit border-[2px] border-[var(--Highlight)] border-solid">
            <div class="flex flex-1 flex-col gap-[15px] w-full">
                <div class="flex flex-col">
                    <p class="text-gray-400 font-light ">Name</p>
                    <p class="text-xl font-bold ">${patient.student}</p>
                    <hr>
                </div>
                <div class="flex flex-col">
                    <p class="text-gray-400 font-light ">Email</p>
                    <p class="text-xl font-bold ">${patient.email ?? '--'}</p>
                    <hr>
                </div>
                <!--<div class="flex flex-col">
                    <p class="text-gray-400 font-light ">Phone No.</p>
                    <p class="text-xl font-bold ">${patient.phone ?? '--'}</p>
                    <hr>
                </div>
                <div class="flex flex-col">
                    <p class="text-gray-400 font-light ">Address</p>
                    <p class="text-xl font-bold ">${patient.address ?? '--'}</p>
                    <hr>
                </div>-->
            </div>
        </div>
        <div class="flex flex-[3] flex-col gap-[15px] p-[10px]!">
            <textarea placeholder="Add a note..." rows="5" id="noteTextarea"
                class="w-full h-full p-[10px]! border-solid border-[2px] border-[var(--Highlight)] rounded-md bg-[var(--SecBg)] outline-none placeholder:text-gray-500">${patient.note ?? ''}</textarea>
            <div class="flex w-full items-center justify-around gap-[15px]">
                <button onclick="saveNote(${patient.student_id}, document.querySelector('#noteTextarea').value)"
                    class="bg-[var(--Highlight)] p-[10px]! flex-[3] flex items-center justify-center gap-[25px] rounded-md"><i
                        class="material-symbols-outlined">add</i> Add Note</button>
                <!--<button
                    class="bg-[var(--Highlight)] p-[10px]! flex-1 flex items-center justify-center gap-[25px] rounded-md"><i
                        class="material-symbols-outlined">delete</i></button>-->
            </div>
        </div>
    `;
        }

        function saveNote(studentId) {
            const noteText = document.getElementById('noteTextarea').value;

            if (!noteText.trim()) {
                alert("Note cannot be empty.");
                return;
            }

            fetch('../../includes/doctor/updatenote.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    student_id: studentId,
                    note: noteText
                })
            })
                .then(res => res.text())
                .then(response => {
                    // console.log("Note updated:", response);
                    alert("Note updated successfully!");
                    showPatients(todayStr);
                    loadPatients(patient)
                })
                .catch(err => console.error("Failed to update note:", err));
        }

        document.getElementById('searchInput').addEventListener('keyup', function () {
            const query = this.value.toLowerCase();
            const patients = document.querySelectorAll('#patientList button');

            patients.forEach(patient => {
                const name = patient.querySelector('h1')?.textContent.toLowerCase();
                if (name && name.includes(query)) {
                    patient.style.display = 'flex';  // or 'block'
                } else {
                    patient.style.display = 'none';
                }
            });
        });


    </script>

</body>

</html>