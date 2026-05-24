<?php
require "db.php";

$query = "
SELECT *
FROM paper_size_inventory
ORDER BY paper_size ASC
";

$result = mysqli_query($conn, $query);

$gsmQuery = "
SELECT *
FROM paper_gsm_inventory
ORDER BY paper_gsm ASC
";

$gsmResult = mysqli_query($conn, $gsmQuery);


$textureQuery = "
SELECT *
FROM paper_texture_inventory
ORDER BY paper_texture ASC
";

$textureResult = mysqli_query($conn, $textureQuery);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image_resources/logo.png">
    <title>Inventory | PrintPro</title>

    <link href="bootstrap.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mina:wght@700&family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f5f7;
            display: flex;
            overflow-x: hidden;
            scrollbar-gutter: stable;
        }

        /* ===== SIDEBAR ===== */
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

        /* OVERLAY */
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

        /* HAMBURGER BUTTON */
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

        /* LOGO */
        .logo {
            font-family: 'Mina', sans-serif;
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 50px;
            padding: 10px 5px;
        }

        /* NAV LINKS */
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
            background: linear-gradient(135deg, #0084ff, #1f9fff);
            color: white;
            font-weight: 600;
            box-shadow: 0 6px 16px rgba(0, 132, 255, .25);
        }

        /* LOGOUT */
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

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 240px;
            width: calc(100% - 240px);
            padding: 40px;
            transition: .3s;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {

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
        }

        /* DITO NAMAN UNG RECENT ORDERS BOX */
        .order-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            border: 1px solid #e0e0e0;
        }

        .order-card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            transition: 0.2s;
        }

        .order-card:hover {
            background: #fcfcfc;
        }

        /* Modal Styles */
        .modal-body-content {
            padding: 20px;
            /* instead of padding-top only */
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
            /* optional: controls spacing */
        }

        .modal-footer {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="overlay" onclick="toggleSidebar()"></div>

    <button class="menu-btn d-md-none" onclick="toggleSidebar()">
        ☰
    </button>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <div class="sidebar">
        <div class="logo mt-5">
            <img src="image_resources/logo.png" width="30" alt="Logo">
            PrintPro
        </div>

        <nav class="nav flex-column">
            <a href="admin_dashboard.php" class="nav-link">
                <span class="material-symbols-outlined">home</span> Dashboard
            </a>
            <a href="admin_clients.php" class="nav-link">
                <span class="material-symbols-outlined">person</span> Clients
            </a>
            <a href="admin_orders.php" class="nav-link">
                <span class="material-symbols-outlined">shopping_cart</span> Orders
            </a>
            <a href="admin_inventory.php" class="nav-link active">
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
            <h2 class="fw-bold">Inventory</h2>
            <div id="currentTime" class="text-muted small fw-bold"></div>
        </div>

        <div class="order-container shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Inventory Items</h5>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInventoryModal">

                    Add Inventory

                </button>
            </div>
            <div class="filter-section">
                <div class="row g-3">
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 border-2"><span
                                    class="material-symbols-outlined text-muted">search</span></span>
                            <input type="text" class="form-control border-start-0 border-2 search-input"
                                placeholder="Search inventory..." id="userSearch">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <select class="form-select border-2 search-input" id="occupationFilter">

                            <option value="">
                                All Inventory
                            </option>

                            <option value="size">Paper Sizes</option>

                            <option value="gsm">GSM</option>

                            <option value="texture">Texture</option>

                        </select>
                    </div>
                </div>
                <hr class="my-4">
                <div id="inventoryCards">
                    <?php while($row = mysqli_fetch_assoc($result)): ?>

                    <div class="order-card d-flex justify-content-between align-items-center" data-type="size">

                        <div class="d-flex gap-3 align-items-center">

                            <img src="image_resources/bondpaper.jpg" width="60" class="rounded">

                            <div>

                                <h6 class="mb-0 fw-bold">

                                    <?= htmlspecialchars($row['paper_size']) ?>

                                </h6>

                                <small class="text-primary">

                                    Stock:
                                    <?= $row['stock_quantity'] ?>

                                </small><br>

                            </div>

                        </div>

                        <div class="text-end">

                            <?php if($row['stock_quantity'] <= 5 && $row['stock_quantity'] > 0): ?>

                            <span class="badge bg-warning text-dark mb-2">
                                Low Stock
                            </span>

                            <?php elseif($row['is_available'] == 1): ?>

                            <span class="badge bg-success mb-2">
                                Available
                            </span>

                            <?php else: ?>

                            <span class="badge bg-danger mb-2">
                                Unavailable
                            </span>

                            <?php endif; ?>

                            <br>

                            <button type="button" class="btn btn-secondary btn-sm mt-2" data-bs-toggle="modal"
                                data-bs-target="#exampleModal" data-id="<?= $row['paper_size_id'] ?>"
                                data-size="<?= htmlspecialchars($row['paper_size']) ?>"
                                data-stock="<?= $row['stock_quantity'] ?>" data-available="<?= $row['is_available'] ?>"
                                onclick="loadInventoryData(this)">

                                Edit Paper Size

                            </button>

                            <a href="delete_paper_size.php?id=<?= $row['paper_size_id'] ?>"
                                class="btn btn-danger btn-sm mt-2" onclick="return confirm('Delete this item?')">

                                Delete

                            </a>

                        </div>

                    </div>

                    <?php endwhile; ?>

                    <?php while($gsm = mysqli_fetch_assoc($gsmResult)): ?>

                    <div class="order-card d-flex justify-content-between align-items-center" data-type="gsm">

                        <div class="d-flex gap-3 align-items-center">

                            <img src="#" width="60" class="rounded">

                            <div>

                                <h6 class="mb-0 fw-bold">

                                    GSM
                                    <?= htmlspecialchars($gsm['paper_gsm']) ?>

                                </h6>

                                <small class="text-primary">

                                    Stock:
                                    <?= $gsm['stock_quantity'] ?>

                                </small><br>

                            </div>

                        </div>

                        <div class="text-end">

                            <?php if($gsm['stock_quantity'] <= 5 && $gsm['stock_quantity'] > 0): ?>

                            <span class="badge bg-warning text-dark mb-2">
                                Low Stock
                            </span>

                            <?php elseif($gsm['is_available'] == 1): ?>

                            <span class="badge bg-success mb-2">
                                Available
                            </span>

                            <?php else: ?>

                            <span class="badge bg-danger mb-2">
                                Unavailable
                            </span>

                            <?php endif; ?>

                            <br>

                            <button type="button" class="btn btn-secondary btn-sm mt-2" data-bs-toggle="modal"
                                data-bs-target="#gsmModal" data-id="<?= $gsm['paper_gsm_id'] ?>"
                                data-gsm="<?= $gsm['paper_gsm'] ?>" data-stock="<?= $gsm['stock_quantity'] ?>"
                                data-available="<?= $gsm['is_available'] ?>" onclick="loadGsmData(this)">

                                Edit GSM

                            </button>

                            <a href="delete_paper_gsm.php?id=<?= $gsm['paper_gsm_id'] ?>"
                                class="btn btn-danger btn-sm mt-2" onclick="return confirm('Delete this item?')">

                                Delete

                            </a>

                        </div>

                    </div>

                    <?php endwhile; ?>
                    <?php while($texture = mysqli_fetch_assoc($textureResult)): ?>

                    <div class="order-card d-flex justify-content-between align-items-center" data-type="texture">

                        <div class="d-flex gap-3 align-items-center">

                            <img src="#" width="60" class="rounded">

                            <div>

                                <h6 class="mb-0 fw-bold">

                                    <?= htmlspecialchars($texture['paper_texture']) ?>

                                </h6>

                                <small class="text-primary">

                                    Stock:
                                    <?= $texture['stock_quantity'] ?>

                                </small><br>

                            </div>

                        </div>

                        <div class="text-end">

                            <?php if($texture['stock_quantity'] <= 5 && $texture['stock_quantity'] > 0): ?>

                            <span class="badge bg-warning text-dark mb-2">
                                Low Stock
                            </span>

                            <?php elseif($texture['is_available'] == 1): ?>

                            <span class="badge bg-success mb-2">
                                Available
                            </span>

                            <?php else: ?>

                            <span class="badge bg-danger mb-2">
                                Unavailable
                            </span>

                            <?php endif; ?>

                            <br>

                            <button type="button" class="btn btn-secondary btn-sm mt-2" data-bs-toggle="modal"
                                data-bs-target="#textureModal" data-id="<?= $texture['paper_texture_id'] ?>"
                                data-texture="<?= htmlspecialchars($texture['paper_texture']) ?>"
                                data-stock="<?= $texture['stock_quantity'] ?>"
                                data-available="<?= $texture['is_available'] ?>" onclick="loadTextureData(this)">

                                Edit Texture

                            </button>

                            <a href="delete_paper_texture.php?id=<?= $texture['paper_texture_id'] ?>"
                                class="btn btn-danger btn-sm mt-2" onclick="return confirm('Delete this item?')">

                                Delete

                            </a>

                        </div>

                    </div>

                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <!--ADD INVENTORY-->
        <div class="modal fade" id="addInventoryModal" tabindex="-1">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Add Inventory Item
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <form action="add_inventory.php" method="POST">

                        <div class="modal-body">

                            <label class="mb-2">
                                Inventory Type
                            </label>

                            <select class="form-select mb-3" name="inventory_type" required>

                                <option value="size">
                                    Paper Size
                                </option>

                                <option value="gsm">
                                    GSM
                                </option>

                                <option value="texture">
                                    Texture
                                </option>

                            </select>

                            <label class="mb-2">
                                Name
                            </label>

                            <input type="text" class="form-control mb-3" name="inventory_name" required>

                            <label class="mb-2">
                                Stock
                            </label>

                            <input type="number" class="form-control" name="stock_quantity" required>

                        </div>

                        <div class="modal-footer">

                            <button type="submit" class="btn btn-primary">

                                Add Item

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
        <!--PAPER_SIZE EDIT STOCK-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">EDIT STOCK</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body-content">
                        <b> Edit Stocks</b>
                        <label class="mt-2">Stock:</label>
                        <input type="hidden" id="paperSizeId">

                        <label class="mt-2">Paper Size:</label>
                        <input type="text" class="form-control" id="paperSizeName" readonly>

                        <label class="mt-2">Stock:</label>
                        <input type="number" class="form-control" id="paperStock">

                        <label class="mt-2">Availability:</label>

                        <select class="form-select" id="paperAvailability">
                            <option value="1">Available</option>
                            <option value="0">Unavailable</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="updateStocks()">

                            Update Stocks

                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--PAPER_GSM EDIT STOCK-->
        <div class="modal fade" id="gsmModal" tabindex="-1" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">

                        <h1 class="modal-title fs-5">
                            EDIT GSM
                        </h1>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body-content">

                        <input type="hidden" id="gsmId">

                        <label>GSM:</label>

                        <input type="text" class="form-control" id="gsmName" readonly>

                        <label>Stock:</label>

                        <input type="number" class="form-control" id="gsmStock">

                        <label>Availability:</label>

                        <select class="form-select" id="gsmAvailability">

                            <option value="1">
                                Available
                            </option>

                            <option value="0">
                                Unavailable
                            </option>

                        </select>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-primary" onclick="updateGsm()">

                            Update GSM

                        </button>

                    </div>

                </div>

            </div>

        </div>
        <!--PAPER_TEXTURE EDIT STOCK-->
        <div class="modal fade" id="textureModal" tabindex="-1" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">

                        <h1 class="modal-title fs-5">
                            EDIT TEXTURE
                        </h1>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body-content">

                        <input type="hidden" id="textureId">

                        <label>Texture:</label>

                        <input type="text" class="form-control" id="textureName" readonly>

                        <label>Stock:</label>

                        <input type="number" class="form-control" id="textureStock">

                        <label>Availability:</label>

                        <select class="form-select" id="textureAvailability">

                            <option value="1">
                                Available
                            </option>

                            <option value="0">
                                Unavailable
                            </option>

                        </select>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-primary" onclick="updateTexture()">

                            Update Texture

                        </button>

                    </div>

                </div>

            </div>

        </div>
    </main>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
            document.querySelector('.overlay').classList.toggle('show');
        }
    </script>
    <script>

        function loadInventoryData(button) {

            document.getElementById("paperSizeId").value =
                button.getAttribute("data-id");

            document.getElementById("paperSizeName").value =
                button.getAttribute("data-size");

            document.getElementById("paperStock").value =
                button.getAttribute("data-stock");

            document.getElementById("paperAvailability").value =
                button.getAttribute("data-available");
        }

    </script>
    <script>

        function updateStocks() {

            const id = document.getElementById("paperSizeId").value;

            const stock =
                document.getElementById("paperStock").value;

            const availability =
                document.getElementById("paperAvailability").value;

            window.location.href =
                "update_paper_size.php?id=" + id +
                "&stock=" + stock +
                "&available=" + availability;
        }

    </script>
    <script>

        function loadGsmData(button) {

            document.getElementById("gsmId").value =
                button.getAttribute("data-id");

            document.getElementById("gsmName").value =
                button.getAttribute("data-gsm");

            document.getElementById("gsmStock").value =
                button.getAttribute("data-stock");

            document.getElementById("gsmAvailability").value =
                button.getAttribute("data-available");
        }

        function updateGsm() {

            const id =
                document.getElementById("gsmId").value;

            const stock =
                document.getElementById("gsmStock").value;

            const availability =
                document.getElementById("gsmAvailability").value;

            window.location.href =
                "update_paper_gsm.php?id=" + id +
                "&stock=" + stock +
                "&available=" + availability;
        }

        function loadTextureData(button) {

            document.getElementById("textureId").value =
                button.getAttribute("data-id");

            document.getElementById("textureName").value =
                button.getAttribute("data-texture");

            document.getElementById("textureStock").value =
                button.getAttribute("data-stock");

            document.getElementById("textureAvailability").value =
                button.getAttribute("data-available");
        }

        function updateTexture() {

            const id =
                document.getElementById("textureId").value;

            const stock =
                document.getElementById("textureStock").value;

            const availability =
                document.getElementById("textureAvailability").value;

            window.location.href =
                "update_paper_texture.php?id=" + id +
                "&stock=" + stock +
                "&available=" + availability;
        }

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
    <script>

        document.addEventListener("DOMContentLoaded", function () {

            const searchInput =
                document.getElementById("userSearch");

            const filterDropdown =
                document.getElementById("occupationFilter");

            const cards =
                document.querySelectorAll(".order-card");

            function filterCards() {

                const searchValue =
                    searchInput.value.toLowerCase().trim();

                const filterValue =
                    filterDropdown.value.toLowerCase().trim();

                cards.forEach(function (card) {

                    const cardText =
                        card.textContent.toLowerCase();

                    const cardType =
                        card.getAttribute("data-type").toLowerCase();

                    const matchesSearch =
                        cardText.includes(searchValue);

                    const matchesFilter =
                        filterValue === "" ||
                        cardType === filterValue;

                    if (matchesSearch && matchesFilter) {

                        card.classList.remove("d-none");

                    } else {

                        card.classList.add("d-none");

                    }

                });

            }

            searchInput.addEventListener("input", filterCards);

            filterDropdown.addEventListener("change", filterCards);

        });

    </script>
</body>

</html>