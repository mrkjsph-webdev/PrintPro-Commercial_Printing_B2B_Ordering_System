<?php
require "db.php";

/* PAPER SIZES */
$sizeQuery = "
SELECT *
FROM paper_size_inventory
WHERE is_available = 1
AND stock_quantity > 0
ORDER BY paper_size ASC
";

$sizeResult = mysqli_query($conn, $sizeQuery);

/* GSM */
$gsmQuery = "
SELECT *
FROM paper_gsm_inventory
WHERE is_available = 1
AND stock_quantity > 0
ORDER BY paper_gsm ASC
";

$gsmResult = mysqli_query($conn, $gsmQuery);

/* TEXTURES */
$textureQuery = "
SELECT *
FROM paper_texture_inventory
WHERE is_available = 1
AND stock_quantity > 0
ORDER BY paper_texture ASC
";

$textureResult = mysqli_query($conn, $textureQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="image_resources/logo.png">
  <title>Product Customization</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
  <link
    href="https://fonts.googleapis.com/css2?family=Mina:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap"
    rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f5f7;
      padding-top: 55px;
    }

    html,
    body {
      overflow-x: hidden;
    }

    html {
      overflow-y: scroll;
    }

    body.modal-open {
      padding-right: 0 !important;
    }

    /* NAVBAR */
    nav.navigation {
      position: fixed;
      top: 0;
      left: 0;
      height: 55px;
      width: 100%;
      background-color: #2B307E;
      z-index: 1000;
      /* important */
    }


    .card-btn {
      background-color: #3A3B7B;
      transition: transform 0.3s, box-shadow 0.3s;
      cursor: pointer;
      border-radius: 0.75rem;
      width: 100%;
      /* full width inside column */
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

    @media (max-width: 768px) {
      .topbar .flex {
        padding: 0 1rem;
      }
    }

    .dropdown-item {
      color: #EB0808;
      font-weight: 700;
    }

    .dropdown-item:hover {
      color: #EB0808;
      font-weight: 700;
    }

    .card-panel {
      border-radius: 1.5rem;
      border: 1px solid rgba(15, 23, 42, 0.08);
      background-color: #fff;
    }

    .back-link {
      font-weight: 600;
      color: #0f172a;
    }

    .main-preview {
      border: 3px solid #2563eb;
      border-radius: 1.5rem;
      width: 100%;
      /* responsive */
      max-width: 640px;
      /* keeps desktop size */
      height: 600px;
      background-color: #ffffff;
      padding: 1rem;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      /* prevents image overflow */
    }

    .main-preview img {
      max-width: 100%;
      /* FIX */
      max-height: 100%;
      /* keeps inside container */
      object-fit: contain;
      /* better than fill */
      border-radius: 1rem;
    }

    .thumb-card {
      border: 1px solid rgba(15, 23, 42, 0.08);
      border-radius: 1rem;
      padding: 0.5rem;
      background-color: #fff;
    }

    .thumb-card img {
      border-radius: 0.8rem;
    }

    .section-title {
      font-weight: 700;
      color: #1e3a8a;
    }

    .field-label {
      font-size: 0.85rem;
      font-weight: 700;
      color: #0f172a;
    }

    .spec-field .form-control,
    .spec-field .form-select {
      border: 1px solid #cbd5e1;
    }

    .price-box .form-control {
      border: 1px solid #cbd5e1;
    }

    .info-box {
      border: 1px solid rgba(15, 23, 42, 0.12);
      border-radius: 1rem;
      padding: 1rem;
      background-color: #fff;
    }

    .info-box strong {
      display: block;
      margin-bottom: 0.5rem;
    }

    .btn-orange {
      background-color: #FF7800;
      border: none;
      color: white;
      font-weight: 700;
      text-transform: uppercase;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      box-shadow: 0 4px 0px #CC6000;
    }

    .btn-orange:hover {
      background: linear-gradient(45deg, #FF7800, #FFB347);
      transform: scale(1.05) translateY(-3px);
      color: white;
      box-shadow: 0 10px 20px rgba(255, 120, 0, 0.4);
    }

    p.ThankYou {
      color: #161490;
      font-weight: 400;
      text-align: center;
      padding-left: 20px;
      padding-right: 20px;
    }

    a {
      color: #0088FF;
      font-weight: 700;
      text-decoration: none;
    }


    /* Modal Styles */
    .modal-body-content {
      padding-top: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
    }

    .modal-footer {
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    .d-flex.justify-content-center.gap-2 {
      margin-top: 10px;
      /* smaller gap before Place Order */
    }

    /* Upload Files */
    .upload-box {
      cursor: pointer;
      position: relative;
    }

    .upload-box:hover {
      background-color: #f1f5ff;
    }

    #previewImage,
    #previewImage2 {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
      border-radius: 1rem;
    }
  </style>
</head>

<body>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <header>
    <nav class="navigation">
      <ul class="d-flex align-items-center w-100">
        <!-- Logo -->
        <li class="me-2">
          <img src="image_resources/logo.png" alt="Logo" style="max-width: 24px; max-height: 24px;">
        </li>

        <!-- Company Logo -->
        <li class="me-auto">PrintPro</li>

        <!-- Cart icon -->
        <li class="list">
          <a href="cart.php"><img src="image_resources/shopping_cart.png" alt="Cart"></a>
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

  <main class="container-fluid py-5">
    <div class="row justify-content-center mb-4">
      <div class="col-lg-10 text-center">
        <h2 class="text-primary fw-bold" style="color: #0088FF !important; font-size: 1.75rem;">Product Customization
        </h2>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-md-6">
        <div class="card-panel shadow-sm p-4">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="client_dashboard.php" class="back-link text-decoration-none">← Back</a>
          </div>

          <div class="d-flex justify-content-center">
            <div class="main-preview upload-box" id="uploadBox">
              <input type="file" id="fileInput" accept=".png,.jpg,.jpeg" hidden>

              <div id="uploadContent" class="text-center">
                <img src="image_resources/upload_image.png">
                <p class="text-muted mb-1">Click to upload image</p>
                <small class="text-muted">PNG, JPG supported</small>
              </div>

              <img id="previewImage" class="d-none" alt="Product Preview">
            </div>
          </div>

          <!-- PLACEHOLDER GOES HERE -->
          <div id="imageWarning" class="mt-2"></div>

          <!-- Back to Back Printing - Second Image Upload (Hidden by default) -->
          <div id="backToBackSection" class="mt-4 d-none">
            <h6 class="section-title mb-3 text-center">Back Side Image</h6>
            <div class="d-flex justify-content-center">
              <div class="main-preview upload-box" id="uploadBox2">
                <input type="file" id="fileInput2" accept=".png,.jpg,.jpeg" hidden>
                <div id="uploadContent2" class="text-center">
                  <img src="image_resources/upload_image.png">
                  <p class="text-muted mb-1">Click to upload back side image</p>
                  <small class="text-muted">PNG, JPG supported</small>
                </div>
                <img id="previewImage2" class="d-none" alt="Back Side Preview">
              </div>
            </div>
            <div id="imageWarning2" class="mt-2"></div>
          </div>

        </div>
      </div>
      <div class="col-md-6">
        <div class="card-panel shadow-sm p-4 h-100 d-flex flex-column justify-content-between">
          <div>
            <h4 class="section-title mb-3 text-center">Product Specifications</h4>
            <div class="spec-field mb-3">
              <label class="field-label" for="paperSize">Paper Size</label>
              <select id="paperSize" class="form-select">

                <option selected disabled>Select Size</option>

                <?php while($size = mysqli_fetch_assoc($sizeResult)): ?>

                <option value="<?= $size['paper_size'] ?>">

                  <?= htmlspecialchars($size['paper_size']) ?>

                </option>

                <?php endwhile; ?>

              </select>
            </div>
            <div class="spec-field mb-3">
              <label class="field-label" for="gsm">GSM</label>
              <select id="gsm" class="form-select">

                <option selected disabled>
                  Select GSM
                </option>

                <?php while($gsm = mysqli_fetch_assoc($gsmResult)): ?>

                <option value="<?= $gsm['paper_gsm'] ?>">

                  <?= htmlspecialchars($gsm['paper_gsm']) ?>

                </option>

                <?php endwhile; ?>

              </select>
            </div>
            <div class="spec-field mb-3">
              <label class="field-label" for="paperTexture">Paper Texture</label>
              <select id="paperTexture" class="form-select">

                <option selected disabled>
                  Select Texture
                </option>

                <?php while($texture = mysqli_fetch_assoc($textureResult)): ?>

                <option value="<?= $texture['paper_texture'] ?>">

                  <?= htmlspecialchars($texture['paper_texture']) ?>

                </option>

                <?php endwhile; ?>

              </select>
            </div>
            <div class="spec-field mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="backToBackPrinting"
                  onchange="toggleBackToBackUpload()">
                <label class="form-check-label field-label" for="backToBackPrinting">
                  Back to Back Printing
                </label>
              </div>
            </div>
            <label class="field-label" for="paperTexture">Copies:</label>
            <div class="input-group" style="width: 150px;">
              <button class="btn btn-outline-secondary" type="button" onclick="decrease()">−</button>
              <input type="text" class="form-control text-center" id="qty" value="1">
              <button class="btn btn-outline-secondary" type="button" onclick="increase()">+</button>
            </div>
            <br>
            <div class="price-box mb-4">
              <label class="field-label" for="total_price">Total Price</label>
              <input id="total_price" class="form-control" type="text" value="₱0" readonly />
            </div>
            <div class="info-box mb-4">
              <strong>Pickup Delivery Only</strong>
              <p class="text-muted mb-0">Orders must pickup at the same day when order is completed.</p>
            </div>
          </div>
          <div class="d-grid mt-3">
            <button type="button" class="btn btn-orange" onclick="addToCart()">
              Add to Cart
            </button>
          </div>
        </div>
      </div>
    </div>
    <!--MODAL NOTIFICATION-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Product Added to Cart Notification</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body-content">
            <img src="image_resources/order-placed-checked.png" alt="Success" class="img-fluid mb-3"
              style="max-width: 100px;">
            <b> Product Added to the Cart!</b>
            <p class="ThankYou"> Your customized product will proceed to your <a href="cart.php">Cart</a>.
              You can customize more products before placing your orders!</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="window.location.href='cart.php'">View My
              Cart</button>
            <button type="button" class="btn btn-orange" data-bs-dismiss="modal"
              onclick="window.location.href='client_dashboard.php'">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Validation Modal -->
    <div class="modal fade" id="validationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Notification</h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body" id="validationMessage">
                Message goes here.
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-primary"
                        data-bs-dismiss="modal">
                    OK
                </button>
            </div>

        </div>
    </div>
</div>
  </main>
  <!-- SCRIPTS -->
  <script>
    const uploadBox = document.getElementById("uploadBox");
    const fileInput = document.getElementById("fileInput");
    const previewImage = document.getElementById("previewImage");
    const uploadContent = document.getElementById("uploadContent");

    uploadBox.addEventListener("click", () => {
      fileInput.click();
    });

    fileInput.addEventListener("change", function () {

      const file = this.files[0];

      if (!file) return;

      const allowed = ["image/png", "image/jpeg"];

      if (!allowed.includes(file.type)) {
        showValidationModal("Only PNG and JPG allowed.");
        this.value = "";
        document.getElementById("imageWarning").innerHTML = "";
        return;
      }

      const reader = new FileReader();

      reader.onload = function (e) {

        const img = new Image();

        img.onload = function () {

          const megapixels = (img.width * img.height) / 1000000;
          const dimensions = `${img.width} × ${img.height}px`;
          const fileSizeMB = file.size / (1024 * 1024);

          let qualityMessage = "";

          if (fileSizeMB < 0.5) {

            qualityMessage = `<div class="alert alert-danger py-2 mt-2">
              Low Quality
              <br>
              Image Resolution: ${dimensions}
              <br>
              Megapixels: (${megapixels.toFixed(2)} MP)
              <br>
              File Size: ${fileSizeMB.toFixed(2)} MB
              <br>
              This image appears highly compressed and may lose print quality.
              </div>
            `;

          } else if (fileSizeMB < 1) {

            qualityMessage = `<div class="alert alert-warning py-2 mt-2">
               Poor Quality
               <br>
               Image Resolution: ${dimensions}
               <br>
               Megapixels: (${megapixels.toFixed(2)} MP)
               <br>
               File Size: ${fileSizeMB.toFixed(2)} MB
               <br>
               This image may appear blurry when printed.
               </div>
            `;

          } else if (fileSizeMB < 2) {

            qualityMessage = `<div class="alert alert-warning py-2 mt-2">
              Fair Quality
              <br>
              Image Resolution: ${dimensions}
              <br>
              Megapixels: (${megapixels.toFixed(2)} MP)
              <br>
              File Size: ${fileSizeMB.toFixed(2)} MB
              <br>
              Suitable for small prints.
              </div>
            `;

          } else {

            qualityMessage = `<div class="alert alert-success py-2 mt-2">
              Excellent Quality 
              <br>
              Image Resolution: ${dimensions}
              <br>
              Megapixels: (${megapixels.toFixed(2)} MP)
              <br>
              File Size: ${fileSizeMB.toFixed(2)} MB
              <br>
              Recommended for printing.
              </div>
            `;

          }

          document.getElementById("imageWarning").innerHTML = qualityMessage;
        };

        img.src = e.target.result;

        previewImage.src = e.target.result;
        previewImage.classList.remove("d-none");
        uploadContent.style.display = "none";
      };

      reader.readAsDataURL(file);
    });
  </script>
  <!-- Back to Back Printing Toggle and File Upload -->
  <script>
    function toggleBackToBackUpload() {
      const checkbox = document.getElementById("backToBackPrinting");
      const section = document.getElementById("backToBackSection");

      if (checkbox.checked) {
        section.classList.remove("d-none");
      } else {
        section.classList.add("d-none");
        // Reset second upload when unchecked
        document.getElementById("fileInput2").value = "";
        document.getElementById("previewImage2").classList.add("d-none");
        document.getElementById("uploadContent2").style.display = "block";
        document.getElementById("imageWarning2").innerHTML = "";

      }
    }

    // Back to Back file upload handler
    const uploadBox2 = document.getElementById("uploadBox2");
    const fileInput2 = document.getElementById("fileInput2");
    const previewImage2 = document.getElementById("previewImage2");
    const uploadContent2 = document.getElementById("uploadContent2");

    uploadBox2.addEventListener("click", () => {
      fileInput2.click();
    });

    fileInput2.addEventListener("change", function () {

      const file = this.files[0];

      if (!file) return;

      const allowed = ["image/png", "image/jpeg"];

      if (!allowed.includes(file.type)) {
        showValidationModal("Only PNG and JPG allowed.");
        this.value = "";
        document.getElementById("imageWarning2").innerHTML = "";
        return;
      }

      const reader = new FileReader();

      reader.onload = function (e) {

        const img = new Image();

        img.onload = function () {

          const megapixels = (img.width * img.height) / 1000000;
          const dimensions = `${img.width} × ${img.height}px`;
          const fileSizeMB = file.size / (1024 * 1024);

          let qualityMessage = "";

          // Check file size first
          if (fileSizeMB < 0.5) {

            qualityMessage = `<div class="alert alert-danger py-2 mt-2">
              Low Quality
              <br>
              Image Resolution: ${dimensions}
              <br>
              Megapixels: (${megapixels.toFixed(2)} MP)
              <br>
              File Size: ${fileSizeMB.toFixed(2)} MB
              <br>
              This image appears highly compressed and may lose print quality.
              </div>
            `;

          } else if (fileSizeMB < 1) {

            qualityMessage = `<div class="alert alert-danger py-2 mt-2">
               Poor Quality
               <br>
               Image Resolution: ${dimensions}
               <br>
               Megapixels: (${megapixels.toFixed(2)} MP)
               <br>
               File Size: ${fileSizeMB.toFixed(2)} MB
               <br>
               This image may appear blurry when printed.
               </div>
            `;

          } else if (fileSizeMB < 2) {

            qualityMessage = `<div class="alert alert-warning py-2 mt-2">
              Fair Quality
              <br>
              Image Resolution: ${dimensions}
              <br>
              Megapixels: (${megapixels.toFixed(2)} MP)
              <br>
              File Size: ${fileSizeMB.toFixed(2)} MB
              <br>
              Suitable for small prints.
              </div>
            `;

          } else {

            qualityMessage = `<div class="alert alert-success py-2 mt-2">
              Excellent Quality
              <br>
              Image Resolution: ${dimensions}
              <br>
              Megapixels: (${megapixels.toFixed(2)} MP)
              <br>
              File Size: ${fileSizeMB.toFixed(2)} MB
              <br>
              Recommended for printing.
              </div>
            `;

          }

          document.getElementById("imageWarning2").innerHTML = qualityMessage;
        };

        img.src = e.target.result;

        previewImage2.src = e.target.result;
        previewImage2.classList.remove("d-none");
        uploadContent2.style.display = "none";
      };

      reader.readAsDataURL(file);
    });
  </script>
  <!-- Calculate Price -->
  <script>
    function calculatePrice() {
      const size = document.getElementById("paperSize").value;
      const gsmValue = document.getElementById("gsm").value;
      const texture = document.getElementById("paperTexture").value;
      const qtyValue = document.getElementById("qty").value;

      let gsm = parseInt(gsmValue);
      let qty = parseInt(qtyValue);

      if (isNaN(gsm)) gsm = 0;
      if (isNaN(qty) || qty < 1) qty = 1;

      let basePrice = 0;

      const sizePrices = {
        "A0 (33.11 x 46.81 in)": 120,
        "A1 (29.7 x 42 in)": 100,
        "A2 (21 x 29.7 in)": 80,
        "A3 (14.8 x 21 in)": 60,
        "A4 (8.27 x 11.69 in)": 20,
        "A5 (5.83 x 8.27 in)": 15,
        "A6 (4.13 x 5.83 in)": 10,
        "A7 (2.83 x 4.13 in)": 8,
        "A8 (2.05 x 2.83 in)": 6,
        "A9 (1.42 x 2.05 in)": 5,
        "A10 (1.02 x 1.42 in)": 5,
        "B0 (39.37 x 55.91 in)": 130,
        "B1 (27.95 x 39.37 in)": 110,
        "B2 (19.69 x 27.95 in)": 90,
        "B3 (13.78 x 19.69 in)": 70,
        "B4 (9.84 x 13.78 in)": 50,
        "B5 (6.89 x 9.84 in)": 25,
        "B6 (4.92 x 6.89 in)": 18,
        "B7 (3.46 x 4.92 in)": 12,
        "B8 (2.44 x 3.46 in)": 10,
        "B9 (1.77 x 2.44 in)": 8,
        "B10 (1.22 x 1.77 in)": 6,
        "Tabloid (11 x 17 in)": 90,
        "Letter (8.5 x 11 in)": 25,
        "Legal (8.5 x 14 in)": 30
      };
      basePrice = sizePrices[size] || 0;

      // GSM calculation
      let gsmExtra = 0;
      if (gsm > 0) {
        gsmExtra = (gsm - 70) * 0.5;
        if (gsmExtra < 0) gsmExtra = 0;
      }

      // Texture pricing
      let textureExtra = 0;
      switch (texture) {
        case "Glossy": textureExtra = 10; break;
        case "Matte Finish": textureExtra = 8; break;
        case "Semi-gloss": textureExtra = 6; break;
        case "Cast-coated": textureExtra = 12; break;
        default: textureExtra = 0;
      }

      let total = (basePrice + gsmExtra + textureExtra) * qty;

      // FINAL SAFE OUTPUT
      document.getElementById("total_price").value =
        "₱" + (isNaN(total) ? "0.00" : total.toFixed(2));
    }
  </script>
  <!-- Auto Update Price -->
  <script>
    document.querySelectorAll("select, #qty").forEach(el => {
      el.addEventListener("change", calculatePrice);
      el.addEventListener("input", calculatePrice);
    });

    function increase() {
      let qty = document.getElementById("qty");
      qty.value = parseInt(qty.value || 1) + 1;
      calculatePrice();
    }

    function decrease() {
      let qty = document.getElementById("qty");
      if (qty.value > 1) qty.value--;
      calculatePrice();
    }

    // initial calculation
    calculatePrice();
  </script>
<script>
function showValidationModal(message) {
    document.getElementById("validationMessage").textContent = message;

    const modal = new bootstrap.Modal(
        document.getElementById("validationModal")
    );

    modal.show();
}
</script>
  <!-- Add to Cart -->
  <script src="addToCart.js"></script>
</body>

</html>
