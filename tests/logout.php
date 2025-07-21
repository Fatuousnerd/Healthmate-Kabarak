<?php
// session_start();

// // Destroy all session data
// $_SESSION = [];
// session_unset();
// session_destroy();

// // Optional: delete cookies if used
// if (isset($_COOKIE['PHPSESSID'])) {
//     setcookie('PHPSESSID', '', time() - 3600, '/');
// }

// // Redirect to login page (or home)
// header("Location: /tests/login.php");
// exit();
?>

<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Confirm Logout</title>
    <link rel="stylesheet" href="../assets/index.css" />
    <script src="../assets/index.js" defer></script>
    <link rel="shortcut icon" href="../../assets/logo2.jpeg" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>

    <!--Loader Start-->
    <div class="loader" id="loader">
        <div class="load" id="load"></div>
    </div>
    <!--Loader End-->

    <div class="flex w-full h-screen items-center justify-center ">
        <div
            class="flex flex-col items-center justify-around p-[25px]! bg-[var(--SecBg)] rounded-lg w-[500px] h-[400px] ">
            <i class="material-symbols-outlined flex items-center justify-center text-[50rem]">logout</i>
            <h1 class="text-[2rem] font-bold text-center">Oh no! You're leaving... </br> Are you sure you want to
                logout?</h1>
            <a href="dashboard.php" class="p-[10px]! bg-[var(--Highlight)] text-[var(--MainText)] rounded-lg w-8/12 text-center ">Naah, just kidding</a>
            <form action="logout_handler.php" method="post" class="w-full flex items-center justify-center">
                <button class="p-[10px]! bg-[var(--SecBg)] text-[var(--Highlight)] outline-none border-solid border-[2px] border-[var(--Highlight)] rounded-lg w-8/12 ">Yes, log me out</button>
            </form>
        </div>
    </div>

</body>

</html>