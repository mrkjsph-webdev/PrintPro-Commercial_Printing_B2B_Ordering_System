<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image_resources/logo.png">
    <title>Update Profile - PrintPro</title>
    <link href="bootstrap.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Mina:wght@400;700&display=swap');

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

        .input-field:focus {
            border-color: #2B307E;
        }

        .save-btn {
            background-color: #15803d;
            color: white;
            padding: 8px 30px;
            border-radius: 7px;
            border: none;
            transition: background-color 0.2s;
        }

        .save-btn:hover {
            background-color: #166534;
        }

        .back-btn {
            cursor: pointer;
            transition: color 0.2s;
        }

        .back-btn:hover {
            color: #2B307E;
        }

        label {
            font-size: 0.875rem;
            margin-bottom: 4px;
            font-weight: 500;
            color: #374151;
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
    <script src="bootstrap.bundle.js"></script>
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
                <!-- User icon -->
                <li class="list">
                    <a href="cart.html"><img src="image_resources/shopping_cart.png" alt="Cart"></a>
                </li>

                <!-- Menu dropdown -->
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
            <a href="my_profile.html"
                class="text-dark text-decoration-none d-flex align-items-center gap-1 fw-bold">
                <span class="material-symbols-outlined fs-5">arrow_back_ios</span>
                Back to My Profile
            </a>
        </div>
        <!-- TITLE -->
        <div class="px-4 px-md-5 mt-4">
            <h2 class="text-primary fw-bold" style="color: #0088FF !important; font-size: 1.75rem;">Update Profile</h2>
        </div>

        <!-- PROFILE INFO - Avatar + Basic Info -->
        <div class="px-4 px-md-5 mt-4 profile-header d-flex gap-4 align-items-center flex-wrap">

            <!-- Avatar -->
            <div class="avatar">
                <i class="fa-solid fa-user fs-1 text-secondary"></i>
            </div>

            <!-- Info -->
            <div class="profile-info">
                <h3 class="fs-4 fw-bold" style="color: #3A3B7B;"></h3>
                <p class="mt-2 mb-1 text-secondary" style="color: #0E0E0E;">
                    Occupation: <span class=" fw-bold " style="color: #3A3B7B;"></span>
                </p>
                <p class="text-secondary" style="color: #0E0E0E;">
                    Email Address:
                    <span class="fw-bold" style="color: #3A3B7B;"></span>
                </p>
            </div>
        </div>

        <!-- EDITABLE FORM -->
        <div class="px-4 px-md-5 mt-4 pb-0">
            <div class="profile-card p-3 p-md-4" style="border: 1px solid #0E0E0E;">

                <!-- Form Row 1: First Name, Middle Initial, Last Name -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="fw-bold" style="color: #0E0E0E;">First Name</label>
                        <input id="first_name" class="input-field">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold" style="color: #0E0E0E;">Middle Initial</label>
                        <input id="middle_initial" class="input-field">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold" style="color: #0E0E0E;">Last Name</label>
                        <input id="last_name" class="input-field">
                    </div>
                </div>

                <!-- Form Row 2: Contact Number, Email, Occupation -->
                <div class="row g-3 mb-5">
                    <div class="col-md-4">
                        <label class="fw-bold" style="color: #0E0E0E;">Contact Number</label>
                        <input class="input-field" id="contact_number">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold" style="color: #0E0E0E;">Email</label>
                        <input class="input-field" id="email">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold" style="color: #0E0E0E;">Occupation</label>
                        <select class="input-field" id="occupation">
                            <option selected disabled>Select Occupation</option>
                            <option>Business Owner</option>
                            <option>Freelancer</option>
                            <option>Employee</option>
                            <option>Part-Timer</option>
                            <option>Student</option>
                            <option>Teacher</option>
                            <option>Unemployed</option>
                            <option>Other</option>
                        </select>
                    </div>
                </div>
                <!-- Save Button -->
                <div class="d-flex justify-content-end" style="margin-top: 10px;">
                    <button class="save-btn" onclick="updateProfile()">SAVE CHANGES</button>
                </div>
            </div>
        </div>

        <!-- Save Changes Modal -->
        <div class="modal fade" id="saveChanges" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                    </div>

                    <div class="modal-body text-center">
                        <p></p>
                    </div>

                    <div class="modal-footer align-items-center justify-content-center">
                        <button type="button" class="btn btn-success" id="modalButton">
                            OK
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </main>
    <!-- Function to fetch and display user profile data -->
    <script>
        let originalProfileData = {};
    </script>
    <script>
        fetch('get_user_profile.php')
            .then(response => response.json())
            .then(res => {
                if (res.status === "success") {
                    const user = res.data;

                    // Full name
                    document.querySelector('.profile-info h3').textContent =
                        `${user.first_name} ${user.middle_initial} ${user.last_name}`;

                    // Occupation display
                    document.querySelector('.profile-info span').textContent = user.occupation;

                    // Email display
                    document.querySelectorAll('.profile-info span')[1].textContent = user.email;

                    // Form fields
                    const inputs = document.querySelectorAll('.input-field');

                    inputs[0].value = user.first_name;
                    inputs[1].value = user.middle_initial;
                    inputs[2].value = user.last_name;
                    inputs[3].value = user.contact_number;
                    inputs[4].value = user.email;

                    const select = inputs[5];
                    const userOcc = (user.occupation || "").trim();

                    originalProfileData = {
                        first_name: user.first_name || "",
                        middle_initial: user.middle_initial || "",
                        last_name: user.last_name || "",
                        contact_number: user.contact_number || "",
                        email: user.email || "",
                        occupation: user.occupation || ""
                    };

                    let found = false;

                    for (let option of select.options) {
                        if (option.value.trim().toLowerCase() === userOcc.toLowerCase()) {
                            option.selected = true;
                            found = true;
                            break;
                        }
                    }

                    // If not found, add it dynamically
                    if (!found && userOcc !== "") {
                        const newOption = new Option(userOcc, userOcc, true, true);
                        select.add(newOption);
                    }

                } else {
                    alert(res.message);
                }
            })
            .catch(err => console.error(err));
    </script>
    <!-- Function to update user profile -->
    <script>
        function updateProfile() {

            const currentData = {
                first_name: document.getElementById("first_name").value.trim(),
                middle_initial: document.getElementById("middle_initial").value.trim(),
                last_name: document.getElementById("last_name").value.trim(),
                contact_number: document.getElementById("contact_number").value.trim(),
                email: document.getElementById("email").value.trim(),
                occupation: document.getElementById("occupation").value
            };

            /* CHECK IF NOTHING CHANGED */
            const noChanges =
                currentData.first_name === originalProfileData.first_name &&
                currentData.middle_initial === originalProfileData.middle_initial &&
                currentData.last_name === originalProfileData.last_name &&
                currentData.contact_number === originalProfileData.contact_number &&
                currentData.email === originalProfileData.email &&
                currentData.occupation === originalProfileData.occupation;

            if (noChanges) {

                showSaveChangesModal(
                    "No Changes Detected",
                    "You have not made any changes to your profile."
                );

                return;
            }

            const data = new FormData();

            data.append("first_name", currentData.first_name);
            data.append("middle_initial", currentData.middle_initial);
            data.append("last_name", currentData.last_name);
            data.append("contact_number", currentData.contact_number);
            data.append("email", currentData.email);
            data.append("occupation", currentData.occupation);

            fetch('update_user_profile.php', {
                method: 'POST',
                body: data
            })
                .then(res => res.json())
                .then(res => {

                    if (res.status === "success") {

                        originalProfileData = { ...currentData };

                        showSaveChangesModal(
                            "Profile Updated",
                            "Your profile has been successfully updated.",
                            true
                        );

                    } else {

                        showSaveChangesModal(
                            "Update Failed",
                            res.message
                        );

                    }

                })
                .catch(err => {
                    console.error(err);

                    showSaveChangesModal(
                        "Error",
                        "Something went wrong while updating your profile."
                    );
                });
        }
    </script>
    <script>
        function showSaveChangesModal(title, message, redirect = false) {

            const modalEl = document.getElementById("saveChanges");

            modalEl.querySelector(".modal-title").textContent = title;
            modalEl.querySelector(".modal-body p").textContent = message;

            const button = document.getElementById("modalButton");

            if (redirect) {

                button.textContent = "Back To Profile";

                button.onclick = function () {
                    window.location.href = "my_profile.html";
                };

            } else {

                button.textContent = "OK";

                button.onclick = function () {

                    const modalInstance =
                        bootstrap.Modal.getInstance(modalEl);

                    modalInstance.hide();
                };
            }

            const modal = new bootstrap.Modal(modalEl);

            modal.show();
        }
    </script>
</body>

</html>