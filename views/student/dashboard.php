<?php
include_once '../../includes/db.php';
include_once '../../includes/getuser.php';

if (!isset($conn)) {
    die("No valid MySQL connection!");
}

if (!$user || $user['role'] !== 'student') {
    header("Location: ../../auth/login.php");
    exit();
}

$userId = $user['id'];
$today = date('Y-m-d');

$sql = "SELECT * FROM appointments 
        WHERE (student_id = ? OR doctor_id = ?) 
        AND DATE(appointment_time) = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $userId, $userId, $today);
$stmt->execute();
$result = $stmt->get_result();

$now = new DateTime();
$showedReminder = false;

while ($appointment = $result->fetch_assoc()) {
    $datetimeString = $appointment['appointment_time']; // ✅ fixed
    $apptTime = new DateTime($datetimeString);
    $minutesDiff = ($apptTime->getTimestamp() - $now->getTimestamp()) / 3600;
    
    // echo $minutesDiff;
    // exit();

    if ($minutesDiff <= 30 && $minutesDiff > 0) {
        echo "<script>
            alert('⏰ Reminder: You have an appointment at " . $apptTime->format('h:i A') . " today.');
        </script>";
        $showedReminder = true;
        break;
    }
}


$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Dashboard</title>
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
        class="w-[300px] h-[100vh] hidden lg:flex flex-col fixed justify-around bg-[var(--SecBg)] items-center p-[250px] z-[98] ">
        <a href="/" class="">
            <img src="../../assets/logo1.png" id="logo" alt="Healthmate Kabarak" class="h-[100px] object-fit ">
        </a>
        <ul class="flex flex-col gap-[25px] text-2xl">
            <a class="flex items-center justify-start gap-[25px] hover:text-[var(--Highlight)]"
                href="/views/student/dashboard.php"><i class="material-symbols-outlined">home_app_logo</i> Dashboard</a>
            <!-- <a class="flex items-center justify-start gap-[25px] hover:text-[var(--Highlight)]"
                href="/views/doctor/appointments.php"><i class="material-symbols-outlined">notifications</i>
                Notifications</a> -->
            <a class="flex items-center justify-start gap-[25px] hover:text-[var(--Highlight)]"
                href="/views/student/profile.php"><i class="material-symbols-outlined">settings</i> Settings</a>
        </ul>
        <a href="/auth/logout.php" class="flex items-center justify-start gap-[25px]">
            <i class="material-symbols-outlined">logout</i>
            Logout
        </a>
    </div>
    <!--Sidebar End-->

    <!--Topbar Start-->
    <div
        class="flex w-full lg:w-[calc(100vw-300px)] h-[70px] lg:left-[300px] fixed items-center justify-between p-[25px]! bg-[var(--SecBg)] border-b-solid border-b-[2px] border-b-[var(--Highlight)] z-[97]">
        <h1 class="text-3xl font-bold hidden lg:flex ">Dashboard</h1>
        <h1 class="text-3xl font-bold lg:hidden flex "></h1>
        <div class="flex items-center justify-center gap-[25px] ">
            <p id="timeText" class="font-bold">00:00</p>
            <button class="themetoggle" id="themetoggle" onclick="toggleTheme()"><i id="themeicon"
                    class="material-symbols-outlined">brightness_4</i></button>
        </div>
    </div>
    <!--Topbar End-->

    <div
        class="flex flex-col w-full lg:w-[calc(100vw-300px)] min-h-[calc(100vh-100px)] relative top-[100px] lg:left-[300px] p-[30px]! gap-[50px]">

        <div class="flex flex-[2] flex-col lg:flex-row gap-[25px] ">
            <form method="POST" action="../../includes/student/addapp.php"
                class="bg-[var(--SecBg)] flex-1 rounded-md p-[10px]! flex flex-col items-start justify-around gap-[25px]">
                <h2 class="text-2xl font-semibold">Book an Appointment</h2>

                <!-- <?php if (!empty($message))
                    echo "<p class='text-center mt-4 text-red-600 font-bold'>$message</p>"; ?> -->

                <textarea name="reason" placeholder="Reason for appointment" required
                    class="w-full p-[5px]! border rounded-md placeholder:text-[var(--MainText)]"></textarea>
                <input type="date" name="date" required
                    class="w-full p-[5px]! border rounded-md placeholder:text-[var(--MainText)]">
                <input type="time" name="time" required
                    class="w-full p-[5px]! border rounded-md placeholder:text-[var(--MainText)] text-[var(--MainText)]">
                <button name="submit"
                    class="w-full bg-[var(--Highlight)] p-[5px]! rounded-md placeholder:text-[var(--MainText)]">Book
                    Appointment</button>
            </form>

            <!--Calendar Start-->
            <div id="calendar"
                class="flex-1 bg-[var(--SecBg)] shadow rounded flex flex-col items-center justify-start p-[5px]!">
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

        <div id="appHolder" class="no-bar flex flex-col lg:flex-row flex-[2] gap-[25px] overflow-x-scroll" style="scrollbar-width:0px;">

        </div>
    </div>

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

        function showAppointments(todayStr) {
            const appHolder = document.getElementById('appHolder');
            appHolder.innerHTML = ''; // Clear existing content

            fetch(`../../includes/student/fetchappointments.php?date=${todayStr}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        appHolder.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                    } else if (data.length === 0) {
                        appHolder.innerHTML = `<p class="text-gray-500">No appointments found for this day.</p>`;
                    } else {
                        data.forEach(appointment => {
                            // console.log(data);

                            const appointmentItem = document.createElement('div');
                            appointmentItem.className = 'rounded-md bg-[var(--SecBg)] p-[10px]! w-full lg:min-w-4/12 flex flex-col items-start justify-around gap-[15px]';
                            appointmentItem.innerHTML = `
                                                        <div class="flex w-full items-center justify-between ">
                                                            <h1 class="text-[1.75rem] font-extrabold ">${appointment.doctor_name}</h1>
                                                            <p class="p-[5px]! rounded-md capitalize ${appointment.status === 'completed' ? 'bg-green-500' : appointment.status === 'confirmed' ? 'bg-yellow-500' : appointment.status === 'cancelled' ? 'bg-red-500' : 'bg-gray-500'}">${appointment.status}</p>

                                                        </div>
                                                        <div class="flex flex-col w-full h-full items-start justify-around">
                                                            <p class="font-bold text-[1.25rem]">${appointment.reason}</p>
                                                            <p class="font-light">${appointment.appointment_date} • ${appointment.appointment_time}</p>
                                                        </div>
                                                    `;
                            appHolder.appendChild(appointmentItem);
                        });
                    }
                })
                .catch(error => console.error('Error fetching patients:', error));
        }
        showAppointments(todayStr);
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