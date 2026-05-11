<?php
session_start();
if (isset($_SESSION['user_id'])) {
} 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - PrintPro</title>
    <link href="bootstrap.css" rel="stylesheet">
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Mina:wght@400;700&family=Poppins:wght@300;400;600;800&display=swap"
        rel="stylesheet">
   
    <style>

        @media screen and (max-width: 600px) {

            /* Responsive Style for Navigation Bar */
            nav.navigation ul,
            li,
            li.list {
                float: none;
            }

        }

        body {
            margin: 0;
            padding: 0;
        }

        /* Navigation Bar */

        nav.navigation {
            position: fixed;
            top: 0;
            left: 0;
            height: 55px;
            width: 100%;
            /* span full width */
            background-color: #2B307E;
        }

        body {
            padding-top: 55px;
            /* equal to nav height so content isn’t hidden */
        }

        nav ul {
            /* Styles for the navigation bar and its items */
            font-weight: 700;
            font-style: normal;
            list-style-type: none;
            margin-top: 0;
            padding: 15px 10px;
        }

        nav ul li a {
            /* Styles for the navigation links */
            color: white;
            text-decoration: none;
            text-align: center;
            padding: 14px 16px;
        }

        nav ul li a:hover {
            /* Hover effect for the navigation links */
            background-color: #3A3B7B;
        }

        nav ul li.list {
            /* Text Alignment and Layout of the Navigation Links. */
            float: right;
        }

        ul li {
            /* Text Styles and Layout of Logo */
            font-family: 'Mina', sans-serif;
            color: white;
            float: left;
        }

        /* Dropdown Menu Styles */
        .dropdown-item {
            color: #EB0808;
            font-weight: 700;
        }

        .dropdown-item:hover {
            color: #EB0808;
            font-weight: 700;
        }

        .dropdown-item:focus {
            background-color: transparent;
            color: inherit;
            outline: none;
        }

        .profile-card {
            background-color: #f0f0f0;
            border-radius: 12px;
            border: 1px solid #ccc;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .avatar {
            width: 110px;
            height: 110px;
            border-radius: 12px;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .input-field {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 4px 12px;
            width: 95%;
            transition: border-color 0.2s;
        }

        .action-box {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body>
    <!-- Navigation content of the cart layout -->
    <header>
        <nav class="navigation">
            <ul class="d-flex align-items-center w-100">
                <!-- Logo -->
                <li class="me-2">
                    <img src="image_resources/logo.png" alt="Logo" style="max-width: 24px; max-height: 24px;">
                </li>

                <!-- Company Logo -->
                <li class="me-auto">PrintPro</li>

                <!-- Home icon -->
                <li class="list">
                    <a href="cart.html"><img src="image_resources/shopping_cart.png" alt="Cart"></a>
                </li>
                <li class="dropdown list">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="image_resources/menu-btn.png" alt="Menu">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="login.html">
                                <img src="image_resources/logout-btn.png" alt="Logout" height="16">
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    <br>
    <main>
        <div class="container mt-3">
            <a href="./client_dashboard.php" class="text-dark text-decoration-none d-flex align-items-center gap-1 fw-bold">
                <img src="image_resources/arrow_back.png" alt="Back" height="16">
                Back to Home
            </a>
        </div>
        <div class="px-4 px-md-5 mt-4">
            <h2 class="text-primary fw-bold" style="color: #0088FF !important; font-size: 1.75rem;">My Profile</h2>
        </div>

        <!-- PROFILE INFO - Avatar + Basic Info -->
        <div class="px-4 px-md-5 mt-4 profile-header d-flex gap-4 align-items-center flex-wrap">

            <!-- Avatar -->
            <div class="avatar">
                <img src="image_resources/user_account.png" alt="Avatar" class="text-secondary">
            </div>

            <!-- Info -->
            <div class="profile-info">
                <h3 class="fs-4 fw-bold" style="color: #3A3B7B;"></h3>
                <p class="mt-2 mb-1 text-secondary" style="color: #0E0E0E;">
                    Occupation: <span class="fw-bold " style="color: #3A3B7B;"></span>
                </p>
                <p class="text-secondary" style="color: #0E0E0E;">
                    Email Address:
                    <span class="fw-bold" style="color: #3A3B7B;"></span>
                </p>
            </div>
        </div>

        <!-- EDITABLE FORM -->
        <div class="px-4 px-md-5 mt-4 pb-0">
            <div class="profile-card p-3 p-md-4" style="border: 1px solid #0E0E0E; position: relative;">
                <img src="image_resources/edit-btn.png" alt="Edit" onclick="window.location.href='my_profile_editable.php'" ;
                    style="position: absolute; top: 15px; right: 15px; color: #0E0E0E; cursor: pointer; font-size: 1.2rem;">

                <!-- Form Row 1: First Name, Middle Initial, Last Name -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="fw-bold" style="color: #0E0E0E;">First Name</label>
                        <input id="first_name" class="input-field" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold" style="color: #0E0E0E;">Middle Initial</label>
                        <input id="middle_initial" class="input-field" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold" style="color: #0E0E0E;">Last Name</label>
                        <input id="last_name" class="input-field" disabled>
                    </div>
                </div>

                <!-- Form Row 2: Contact Number, Email, Occupation -->
                <div class="row g-3 mb-5">
                    <div class="col-md-4">
                        <label class="fw-bold" style="color: #0E0E0E;">Contact Number</label>
                        <input class="input-field" id="contact_number" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold" style="color: #0E0E0E;">Email</label>
                        <input class="input-field" id="email"disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold" style="color: #0E0E0E;">Occupation</label>
                        <select class="input-field" id="occupation" disabled>
                             <option value="Business Owner">Business Owner</option>
                            <option value="Freelancer">Freelancer</option>
                            <option value="Employee">Employee</option>
                            <option value="Part-Timer">Part-Timer</option>
                            <option value="Student">Student</option>
                            <option value="Teacher">Teacher</option>
                            <option value="Unemployed">Unemployed</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="bootstrap.bundle.js"></script>
    <!-- Function to fetch and display user profile data -->
    <script>
    fetch('get_user_profile.php')
    .then(response => response.json())
    .then(res => {

        console.log(res);

        if (res.status === "success") {

            const user = res.data;

            // Profile header
            document.querySelector('.profile-info h3').textContent =
                `${user.first_name} ${user.middle_initial} ${user.last_name}`;

            // Profile spans
            const spans = document.querySelectorAll('.profile-info span');

            spans[0].textContent = user.occupation;
            spans[1].textContent = user.email;

            // Form fields
            document.getElementById('first_name').value =
                user.first_name;

            document.getElementById('middle_initial').value =
                user.middle_initial;

            document.getElementById('last_name').value =
                user.last_name;

            document.getElementById('contact_number').value =
                user.contact_number;

            document.getElementById('email').value =
                user.email;

            document.getElementById('occupation').value =
                user.occupation;

        } else {

            alert(res.message);

        }

    })
    </script>
</body>

</html>