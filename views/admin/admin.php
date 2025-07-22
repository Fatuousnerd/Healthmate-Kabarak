<?php

include '../../includes/getuser.php';

if (!$user || $user['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}
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
                href="/views/admin/admin.php"><i class="material-symbols-outlined">home_app_logo</i> Dashboard</a>
            <a class="flex items-center justify-start gap-[25px] hover:text-[var(--Highlight)]"
                href="/views/admin/profile.php"><i class="material-symbols-outlined">settings</i> Settings</a>
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
        <h1 class="text-3xl font-bold hidden lg:flex ">Admin Dashboard</h1>
        <h1 class="text-3xl font-bold lg:hidden flex "></h1>
        <div class="flex items-center justify-center gap-[25px] ">
            <p id="timeText" class="font-bold">00:00</p>
            <button class="themetoggle" id="themetoggle" onclick="toggleTheme()"><i id="themeicon"
                    class="material-symbols-outlined">brightness_4</i></button>
        </div>
    </div>
    <!--Topbar End-->

    <div
        class="flex flex-col items-center w-full lg:w-[calc(100vw-300px)] h-[calc(100vh-70px)] relative top-[70px] lg:left-[300px] p-[10px] lg:p-[30px]! gap-[25px] overflow-y-hidden">
        <div class="w-full flex items-center justify-around lg:justify-start lg:gap-[100px]">

            <div
                class="flex items-center justify-center border-solid border-[1.5px] border-[var(--Highlight)] rounded-[5px] ">
                <input type="text" name="" id="searchInput"
                    class="bg-transparent flex-1 p-[5px]! h-full placeholder:text-[var(--MainText)] border-none focus:outline-none focus:border-none"
                    placeholder="Search users...">
                <button
                    class=" text-[var(--MainText)] rounded-[2.5px] p-[5px]! flex items-center justify-center h-full p-[5px]! cursor-pointer"><i
                        class="material-symbols-outlined">search</i></button>
            </div>

            <div class="flex gap-[5px]">
                <i class="material-symbols-outlined">filter_alt</i>
                <select name="" id="roleFilter"
                    class="border-b-solid border-b-[2px] border-b-[var(--Highlight)] outline-none ">
                    <option value=""
                        class=" appearance-none p-[5px]! bg-[var(--SecBg)] text-[var(--MainText)] outline-none border-none "
                        disabled selected>Filter by role</option>
                    <option value="Doctor"
                        class=" appearance-none p-[5px]! bg-[var(--SecBg)] text-[var(--MainText)] outline-none border-none ">
                        Doctor</option>
                    <option value="Student"
                        class=" appearance-none p-[5px]! bg-[var(--SecBg)] text-[var(--MainText)] outline-none border-none ">
                        Student</option>
                    <option value="Admin"
                        class=" appearance-none p-[5px]! bg-[var(--SecBg)] text-[var(--MainText)] outline-none border-none ">
                        Admin</option>
                </select>
            </div>

            <button id="resetBtn" class="hidden items-center justify-center p-[5px]! ">
                <i class="material-symbols-outlined">refresh</i>
            </button>

        </div>

        <div class="hidden lg:flex items-center w-full pl-[25px]!">
            <div class="flex-1">ID</div>
            <div class="flex-[2]">Profile Pic</div>
            <div class="flex-[2]">Name</div>
            <div class="flex-[3]">Email</div>
            <div class="flex-1">Role</div>
            <div class="flex-1">Edit</div>
        </div>

        <div id="holder" class="flex flex-col w-full lg:max-h-[400px] gap-[25px] overflow-y-scroll"
            style="scrollbar-width: none;">

        </div>
    </div>

    <!--Modal Start-->
    <div id="editModal"
        class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden flex items-center justify-center z-[100]">
        <div class="bg-[var(--MainBg)] p-6! rounded-md w-[400px] text-[var(--MainText)]">
            <h2 class="text-xl font-bold mb-4!">Edit User Role</h2>
            <form id="editForm">
                <input type="hidden" name="user_id" id="user_id">
                <label for="role" class="block font-semibold mb-1!">Role:</label>
                <select name="role" id="role" class="w-full border border-gray-300 rounded p-2! mb-4!">
                    <option
                        class=" appearance-none p-[5px]! bg-[var(--SecBg)] text-[var(--MainText)] outline-none border-none "
                        value="student">Student</option>
                    <option
                        class=" appearance-none p-[5px]! bg-[var(--SecBg)] text-[var(--MainText)] outline-none border-none "
                        value="admin">Admin</option>
                    <option
                        class=" appearance-none p-[5px]! bg-[var(--SecBg)] text-[var(--MainText)] outline-none border-none "
                        value="doctor">Doctor</option>
                </select>

                <div class="flex justify-between">
                    <button type="submit" class="bg-green-600 text-white px-4! py-2! rounded">Update</button>
                    <button type="button" onclick="deleteUser()"
                        class="bg-red-600 text-white px-4! py-2! rounded">Delete</button>
                    <button type="button" onclick="closeModal()"
                        class="bg-gray-500 text-white px-4! py-2! rounded">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!--Modal End-->

    <script>
        // Filter Logic
        document.addEventListener("DOMContentLoaded", () => {
            const searchInput = document.getElementById("searchInput");
            const roleFilter = document.querySelector("select");
            const resetBtn = document.getElementById("resetBtn");

            function renderUsers(users) {
                const holder = document.getElementById("holder");
                holder.innerHTML = '';

                users.forEach(user => {
                    const wrapper = document.createElement('div');
                    wrapper.className = "card p-[25px]! lg:pl-[25px]! rounded-md bg-[var(--SecBg)] w-full h-auto lg:h-[100px] flex flex-col lg:flex-row items-start lg:items-center justify-between";
                    wrapper.innerHTML = `
                                        <p class="flex-1 hidden lg:flex ">${user.id}</p>
                                        <div class="flex-[2] flex items-center justify-start">
                                            <img src="../../includes/uploads/${user.profile_pic}" alt=""
                                                class="h-[75px] w-[75px] rounded-full border-solid border-[2px] border-[var(--Highlight)] object-cover">
                                        </div>
                                        <h1 class="flex-[2] text-[1.5rem] font-bold capitalize ">${user.name}</h1>
                                        <p class="flex-[3] ">${user.email}</p>
                                        <p class="flex-1 capitalize ">${user.role}</p>
                                        <div class="flex-1 flex gap-[15px]">
                                            <button  onclick="openEdit(this)" data-id="${user.id}" data-role="${user.role}"
                                                class="bg-[var(--Highlight)] text-[var(--MainText)] rounded p-1! cursor-pointer flex items-center justify-center">
                                                <i class="material-symbols-outlined">edit</i>
                                            </button>
                                            <button onclick="deleteUser()"
                                                class="bg-[var(--Highlight)] text-[var(--MainText)] rounded p-1! cursor-pointer flex items-center justify-center">
                                                <i class="material-symbols-outlined">delete</i>
                                            </button>
                                        </div>
            `;
                    holder.appendChild(wrapper);
                });
            }

            function fetchUsers(callback) {
                fetch('../../includes/admin/fetchusers.php')
                    .then(res => res.json())
                    .then(data => {
                        callback ? callback(data) : renderUsers(data);
                    });
            }

            function applyFilters(data) {
                resetBtn.classList.remove('hidden');
                resetBtn.classList.add('flex');
                const searchVal = searchInput.value.trim().toLowerCase();
                const roleVal = roleFilter.value.trim().toLowerCase();

                const filtered = data.filter(user => {
                    const nameMatch = user.name.toLowerCase().includes(searchVal);
                    const roleMatch = roleVal === '' || user.role.toLowerCase() === roleVal;
                    return nameMatch && roleMatch;
                });

                renderUsers(filtered);
            }

            // Attach events
            searchInput.addEventListener("input", () => fetchUsers(applyFilters));
            roleFilter.addEventListener("change", () => fetchUsers(applyFilters));
            resetBtn.addEventListener("click", () => {
                searchInput.value = '';
                roleFilter.value = '';
                fetchUsers(); // Reload all
                resetBtn.classList.remove('flex');
                resetBtn.classList.add('hidden');
            });

            // Initial fetch
            fetchUsers();
        });

        function openEdit(button) {
            const modal = document.getElementById("editModal");
            const roleSelect = document.getElementById("role");
            const userIdInput = document.getElementById("user_id");

            const userId = button.dataset.id;
            const userRole = button.dataset.role;

            userIdInput.value = userId;
            roleSelect.value = userRole;

            modal.classList.remove("hidden");
        }

        function closeModal() {
            document.getElementById("editModal").classList.add("hidden");
        }

        document.getElementById("editForm").addEventListener("submit", function (e) {
            e.preventDefault();

            const userId = document.getElementById("user_id").value;
            const role = document.getElementById("role").value;

            const formData = new FormData();
            formData.append("action", "update");
            formData.append("user_id", userId);
            formData.append("role", role);

            fetch("../../includes/admin/updateuser.php", {
                method: "POST",
                body: formData
            })
                .then(res => res.text())
                .then(response => {
                    closeModal();
                    fetchUsers(); // refresh list
                    alert("Role updated!");
                });
        });

        function deleteUser() {
            const userId = document.getElementById("user_id").value;

            if (!confirm("Are you sure you want to delete this user?")) return;

            const formData = new FormData();
            formData.append("action", "delete");
            formData.append("user_id", userId);

            fetch("../../includes/admin/updateuser.php", {
                method: "POST",
                body: formData
            })
                .then(res => res.text())
                .then(response => {
                    closeModal();
                    fetchUsers(); // refresh list
                    alert("User deleted!");
                });
        }



    </script>

</body>

</html>