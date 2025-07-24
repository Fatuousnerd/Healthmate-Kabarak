<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthmate Kabarak</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="assets/index.js" defer></script>
    <link rel="stylesheet" href="assets/index.css">
    <link rel="shortcut icon" href="assets/logo2.jpeg" type="image/x-icon">
</head>

<body class="">

    <!--Loader Start-->
    <div class="loader" id="loader">
        <div class="load" id="load"></div>
    </div>
    <!--Loader End-->

    <div class="flex flex-col items-center justify-center h-screen p-[50px]! gap-[25px]!">
        <h1 class="text-[5rem] font-bold text-center">Welcome to Healthmate Kabarak</h1>
        <p class="text-[2rem] text-center">Your Health, Our Priority - Compassionate Care at Every Step.</p>
        <div class="flex gap-[25px]">
            <a href="/auth/signup.php" class="p-[10px]! px-[20px]! font-bold uppercase text-[1rem] rounded-md bg-transparent border-solid border-[2px] border-[var(--MainText)] hover:bg-[var(--MainText)] hover:text-[var(--MainBg)] duration-300 transition-all ">Sign Up</a>
            <a href="/auth/login.php" class="p-[10px]! px-[20px]! font-bold uppercase text-[1rem] rounded-md bg-transparent border-solid border-[2px] border-[var(--MainText)] hover:bg-[var(--MainText)] hover:text-[var(--MainBg)] duration-300 transition-all ">Login</a>
        </div>
    </div>
</body>

</html>