<?php
// define('ROOT_PATH', __DIR__ . '/../');

include '../../includes/getuser.php';



// if (!$user) {
//     header("Location: login.php");
//     exit();
// }
?>
<!-- <pre><?php print_r($user); ?></pre> -->

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

    <!--Sidebar Start-->
    <div class="w-[300px] h-[100vh] flex flex-col fixed justify-around bg-[var(--SecBg)] items-center p-[250px] ">
        <a href="/" class="">
            <img src="../../assets/logo1.png" id="logo" alt="Healthmate Kabarak" class="h-[100px] object-fit ">
        </a>
        <ul class="flex flex-col gap-[25px] text-2xl">
            <a class="flex items-center justify-start gap-[25px] hover:text-[var(--Highlight)]"
                href="/views/doctor/doctor.php"><i class="material-symbols-outlined">home_app_logo</i> Dashboard</a>
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
        <a href="/tests/logout.php" class="flex items-center justify-start gap-[25px]">
            <i class="material-symbols-outlined">logout</i>
            Logout
        </a>
    </div>
    <!--Sidebar End-->

    <!--Topbar Start-->
    <div
        class="flex w-[calc(100vw-300px)] h-[70px] left-[300px] fixed items-center justify-between p-[25px]! bg-[var(--SecBg)] border-b-solid border-b-[2px] border-b-[var(--Highlight)]">
        <h1 class="text-3xl font-bold">Profile</h1>
        <div class="flex items-center justify-center gap-[25px] ">
            <p id="timeText" class="font-bold">00:00</p>
            <a class="hover:text-[var(--Highlight)] flex items-center justify-center" href=""><i
                    class="material-symbols-outlined">notifications</i></a>
            <button class="themetoggle" id="themetoggle" onclick="toggleTheme()"><i id="themeicon"
                    class="material-symbols-outlined">brightness_4</i></button>
        </div>
    </div>
    <!--Topbar End-->

    <!--Main Profile Section Start-->
    <div
        class="flex flex-col w-[calc(100vw-300px)] h-[calc(100vh-100px)] relative top-[100px] left-[300px] p-[50px]! gap-[50px]">
        <div class="flex flex-[4] flex-row items-center justify-around gap-[50px]">
            <div
                class="flex flex-col flex-1 h-full items-center justify-between border-solid border-[var(--SecText)] border-[2px] rounded-lg p-[20px]!">
                <h1 class="text-4xl font-bold capitalize "><?= $user ? htmlspecialchars($user['name']) : 'Guest' ?></h1>
                <img src="../../includes/uploads/<?= $user['profile_pic'] ?>" alt=""
                    class="w-[175px] h-[175px] rounded-full object-fit border-[5px] border-[var(--Highlight)] border-solid">

            </div>
            <div
                class="flex flex-col flex-[2] h-full items-center justify-around border-solid border-[var(--SecText)] border-[2px] rounded-lg p-[15px]! pr-[25px]! pl-[25px]!">
                <div class="flex w-full items-center justify-between">
                    <h1>Profile Details</h1>
                    <button onclick="openEditModal()" class="hover:text-[var(--Highlight)] hover:scale-[1.2] cursor-pointer"><i
                            class="material-symbols-outlined">edit</i></button>
                </div>
                <div class="flex flex-col gap-[20px] w-full">
                    <div class="flex flex-col">
                        <p class="text-gray-400 font-light ">Name</p>
                        <p class="text-2xl font-bold capitalize ">
                            <?= $user ? htmlspecialchars($user['name']) : 'Guest' ?>
                        </p>
                        <hr>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-gray-400 font-light ">Role</p>
                        <p class="text-2xl font-bold capitalize ">
                            <?= $user ? htmlspecialchars($user['role']) : 'Guest' ?>
                            </p>
                        <hr>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-gray-400 font-light capitalize ">Email</p>
                        <p class="text-2xl font-bold "><?= $user ? htmlspecialchars($user['email']) : 'Guest' ?></p>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="flex-1 flex flex-col items-center justify-around border-solid border-[var(--SecText)] border-[2px] rounded-lg p-[5px]!">
            <ul class="flex items-center justify-around w-full text-2xl">
                <li><a href="" class="hover:text-[var(--Highlight)] "><i class="material-symbols-outlined">home</i></a>
                </li>
                <li><a href="" class="hover:text-[var(--Highlight)] "><i
                            class="material-symbols-outlined">person</i></a></li>
                <li><a href="" class="hover:text-[var(--Highlight)] "><i
                            class="material-symbols-outlined">settings</i></a></li>
                <li><a href="" class="hover:text-[var(--Highlight)] "><i class="material-symbols-outlined">help</i></a>
                </li>
                <li><a href="" class="hover:text-[var(--Highlight)] "><i
                            class="material-symbols-outlined">logout</i></a></li>
            </ul>
        </div>
    </div>
    <!--Main Profile Section End-->

    <!--Modal Start-->
    <div class="modal h-screen w-full fixed z-[99] top-0 bg-[rgba(0,0,0,0.5)] backdrop-blur-sm flex items-center justify-center hidden"
        id="modal">
        <div class="modal-content bg-[var(--SecBg)] p-[20px]! rounded-lg shadow-lg w-[500px] h-[400px] relative">
            <button onclick="closeModal()"
                class="close right-[20px] top-[20px] text-[var(--Highlight)] cursor-pointer absolute" id="close"><i
                    class="material-symbols-outlined">close</i></button>
            <h2 class="text-2xl font-bold text-center">Edit Profile</h2>
            <form action="/includes/updateuser.php" method="post" enctype="multipart/form-data" class="flex flex-col h-[85%] item-start justify-between ">
                <div class="flex flex-col gap-[5px]">
                    <label for="profile_pic" class="text-gray-400">Profile Picture</label>
                    <input type="file" id="profile_pic" name="profile_pic" class="p-[5px]! border rounded">
                </div>
                <div class="flex flex-col gap-[5px]">
                    <label for="name" class="text-gray-400">Name</label>
                    <input type="text" id="name" name="name" value="<?= $user ? htmlspecialchars($user['name']) : '' ?>"
                        class="p-[5px]! border rounded">
                </div>
                <div class="flex flex-col gap-[5px]">
                    <label for="email" class="text-gray-400">Email</label>
                    <input type="email" id="email" name="email"
                        value="<?= $user ? htmlspecialchars($user['email']) : '' ?>" class="p-[5px]! border rounded">
                </div>
                <button type="submit" name="update"
                    class="bg-[var(--Highlight)] text-white p-[5px]! rounded cursor-pointer hover:bg-[var(--MainText)] hover:text-[var(--SecBg)]">Save
                    Changes</button>
            </form>
        </div>
    </div>
    <!--Modal End-->

    </style>

    <script>
        function openEditModal() {
            document.getElementById('modal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
</body>

</html>