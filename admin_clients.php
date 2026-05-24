<?php
session_start();
require "db.php";

/* GET ALL USERS */
$query = "
SELECT
    user_id,
    first_name,
    last_name,
    email,
    occupation
FROM users
ORDER BY first_name ASC
";

$result = mysqli_query($conn, $query);

function e($str) {
    return htmlspecialchars($str ?? '');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image_resources/logo.png">
    <title>Users | PrintPro Admin</title>

    <link href="bootstrap.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mina:wght@700&family=Poppins:wght@400;600&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f5f7;
            display: flex;
            overflow-x: hidden;
            scrollbar-gutter: stable;

            overflow-y: scroll;
            scrollbar-width: none;


            body::-webkit-scrollbar {
                display: none;
            }
        }


        .sidebar {
            width: 240px;
            height: 100vh;
            background: #25286b;
            color: white;
            position: fixed;
            padding: 22px;
            display: flex;
            flex-direction: column;
            top: 0;
            left: 0;
            transition: .3s ease;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, .12);
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .35);
            opacity: 0;
            visibility: hidden;
            transition: .3s;
            z-index: 900;
        }

        .overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .menu-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background: #2b2d77;
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 20px;
        }

        .logo {
            font-family: 'Mina', sans-serif;
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 50px;
            padding: 10px 5px;
        }


        .nav-link {
            color: rgba(255, 255, 255, .82);
            margin: 6px 0;
            padding: 14px 16px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            transition: .25s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, .08);
            color: white;
            transform: translateX(3px);
        }

        .nav-link.active {
            background: linear-gradient(135deg,
                    #0084ff,
                    #1f9fff);
            color: white;
            font-weight: 600;
            box-shadow: 0 6px 16px rgba(0, 132, 255, .25);
        }

        .logout-btn {
            background: #d9363e;
            color: white;
            padding: 12px;
            border-radius: 14px;
            font-weight: 600;
            transition: .25s;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: #eb1616;
            color: white;
        }

        .main-content {
            margin-left: 240px;
            width: calc(100% - 240px);
            padding: 40px;
            transition: .3s;
        }

        /* SEARCH & FILTER AREA */
        .filter-section {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .search-input {
            border: 2px solid #eee;
            border-radius: 12px;
            padding: 12px 20px;
            transition: 0.3s;
        }

        .search-input:focus {
            border-color: #0084ff;
            box-shadow: none;
        }

        /* PARA SA USER CARDS */
        .user-card {
            background: white;
            border: 2px solid #0084ff;
            border-radius: 18px;
            padding: 25px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            transition: .3s;
            transition:
                opacity .2s ease;
        }

        .user-card:hover {
            transform: translateY(-2px);
            background: #f8fbff;
        }

        .avatar-box {
            width: 70px;
            height: 70px;
            background: #eef2ff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2c2e7a;
        }

        .btn-view {
            background: #0084ff;
            color: white;
            border-radius: 10px;
            padding: 8px 25px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .btn-view:hover {
            background: #0069cc;
            color: white;
        }

        @media (max-width:768px) {

            .sidebar {
                left: -240px;
                width: 240px;
                box-shadow: 3px 0 15px rgba(0, 0, 0, .25);
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
                padding-top: 90px;
            }

            .menu-btn {
                top: 15px;
                left: 15px;
            }

            .page-header {
                margin-left: 50px;
            }

            .user-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-view {
                width: 100%;
            }
        }

        .hidden-card {
            opacity: 0;
            transform: scale(.98);
            max-height: 0;
            overflow: hidden;
            margin: 0;
            padding-top: 0;
            padding-bottom: 0;
            border-width: 0;
            pointer-events: none;
        }

        .user-card {
            transition:
                .25s ease;
        }
    </style>
</head>

<body>
    <div class="overlay" onclick="toggleSidebar()"></div>
    <button class="menu-btn d-md-none" onclick="toggleSidebar()">☰</button>
    <div class="sidebar">
        <div class="logo mt-5">
            <img src="image_resources/logo.png" width="30" alt="Logo"> PrintPro
        </div>

        <nav class="nav flex-column">
            <a href="admin_dashboard.php" class="nav-link">
                <span class="material-symbols-outlined">home</span> Dashboard
            </a>
            <a href="admin_clients.php" class="nav-link active">
                <span class="material-symbols-outlined">person</span> Clients
            </a>
            <a href="admin_orders.php" class="nav-link">
                <span class="material-symbols-outlined">shopping_cart</span> Orders
            </a>
            <a href="admin_inventory.php" class="nav-link">
                <span class="material-symbols-outlined">inventory_2</span> Inventory
            </a>
            <a href="admin_analytics.php" class="nav-link">
                <span class="material-symbols-outlined">analytics</span> Report and Analytics
            </a>
        </nav>

        <div class="mt-auto">
            <a href="login.html" class="logout-btn">
                <span class="material-symbols-outlined">logout</span>
                Logout
            </a>
        </div>
    </div>

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Clients</h2>
            <div id="currentTime" class="text-muted small fw-bold"></div>
        </div>

        <div class="filter-section">
            <div class="row g-3">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 border-2"><span
                                class="material-symbols-outlined text-muted">search</span></span>
                        <input type="text" class="form-control border-start-0 border-2 search-input"
                            placeholder="Search by name, ID or email..." id="userSearch">
                    </div>
                </div>
                <div class="col-md-5">
                    <select class="form-select border-2 search-input" id="occupationFilter">
                        <option value="">All Occupations</option>
                        <option value="Business Owner">Business Owner</option>
                        <option value="Freelancer">Freelancer</option>
                        <option value="Student">Student</option>
                        <option value="Part-Timer">Part-Timer</option>
                        <option value="Unemployed">Unemployed</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
            </div>
        </div>
        <?php while($user = mysqli_fetch_assoc($result)): ?>

        <div class="user-card" data-name="<?= e($user['first_name'] . ' ' . $user['last_name']) ?>"
            data-email="<?= e($user['email']) ?>" data-job="<?= e($user['occupation']) ?>">

            <div class="d-flex gap-4 align-items-center">

                <div class="avatar-box">
                    <span class="material-symbols-outlined fs-1">
                        person
                    </span>
                </div>

                <div>

                    <h5 class="mb-0 fw-bold">
                        <?= e($user['first_name'] . ' ' . $user['last_name']) ?>
                    </h5>

                    <p class="mb-0 text-muted small">
                        <?= e($user['occupation']) ?>
                    </p>

                    <p class="mb-0 text-primary small">
                        <?= e($user['email']) ?>
                    </p>

                </div>

            </div>

            <button class="btn-view"
                onclick="window.location.href='admin_view_clients.php?user_id=<?= $user['user_id'] ?>'">

                View Profile

            </button>

        </div>

        <?php endwhile; ?>
    </main>
    <script>
        function toggleSidebar() {

            document.querySelector('.sidebar')
                .classList.toggle('show');

            document.querySelector('.overlay')
                .classList.toggle('show');
        }
    </script>


    <script>
        const userSearch = document.getElementById('userSearch');
        const occupationFilter = document.getElementById('occupationFilter');

        const cards = document.querySelectorAll('.user-card');

        const filterUsers = () => {
            const searchValue = userSearch.value.toLowerCase().trim();
            const filterValue = occupationFilter.value.toLowerCase().trim();

            const searchTerms = searchValue.split(" ").filter(term => term);

            cards.forEach(card => {
                const name = card.dataset.name.toLowerCase();
                const email = card.dataset.email.toLowerCase();
                const job = card.dataset.job.toLowerCase();

                const matchesSearch = searchTerms.length === 0 ||
                    searchTerms.every(term =>
                        name.includes(term) ||
                        email.includes(term) ||
                        job.includes(term)
                    );

                const matchesFilter = !filterValue || job === filterValue;

                if (matchesSearch && matchesFilter) {
                    card.classList.remove('hidden-card');
                } else {
                    card.classList.add('hidden-card');
                }
            });
        };

        // 👇 THIS is what you were missing
        userSearch.addEventListener('input', filterUsers);
        occupationFilter.addEventListener('change', filterUsers);

        // run once on load
        filterUsers();
    </script>

    <script>
        function updateTime() {
            const now = new Date();

            const time = now.toLocaleTimeString('en-PH', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });

            const date = now.toLocaleDateString('en-PH', {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });

            document.getElementById("currentTime").textContent = `${time} ${date}`;
        }

        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>

</html>