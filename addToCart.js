console.log("addToCart.js loaded");

async function uploadBothImages() {
    console.log("uploadBothImages called");
    const fileInput1 = document.getElementById("fileInput");
    const fileInput2 = document.getElementById("fileInput2");
    const checkbox = document.getElementById("backToBackPrinting");

    console.log("fileInput1:", fileInput1?.files?.length);
    console.log("fileInput2:", fileInput2?.files?.length);
    console.log("checkbox.checked:", checkbox?.checked);

    if (!fileInput1?.files?.[0]) {
        showValidationModal("Please upload a front image.");
        return false;
    }

    const frontFile = fileInput1.files[0];
    const backFile = (checkbox?.checked) ? fileInput2?.files?.[0] : null;

    if (checkbox?.checked && !backFile) {
        showValidationModal("Back to Back printing is checked. Please upload a back image.");
        return false;
    }

    const formData = new FormData();
    formData.append("frontImage", frontFile);
    if (backFile) {
        formData.append("backImage", backFile);
    }

    try {
        const response = await fetch("file_upload.php", {
            method: "POST",
            body: formData
        });
        const text = await response.text();
        console.log("RAW RESPONSE:", text);

        const result = JSON.parse(text);

        if (result.status === "success") {
            window.uploadedFileId = result.file_id;
            window.frontImagePath = result.frontImage;
            window.backImagePath = result.backImage || null;
            console.log("Upload successful, file_id:", window.uploadedFileId);
            return true;
        } else {
            showValidationModal("Upload Error: " + result.message);
            return false;
        }
    } catch (err) {
        console.error("Fetch error:", err);
        showValidationModal("Upload failed: " + err.message);
        return false;
    }
}

async function addToCart() {
    console.log("addToCart called");

    const uploadSuccess = await uploadBothImages();
    console.log("Upload success:", uploadSuccess);

    if (!uploadSuccess) {
        console.log("Upload failed, returning");
        return;
    }

    if (!window.uploadedFileId) {
        showValidationModal("Please upload a file first.");
        return;
    }

    const product_id = sessionStorage.getItem("product_id");
    if (!product_id) {
        showValidationModal("Product ID missing. Please select a product again.");
        return;
    }

    const paperSize = document.getElementById("paperSize").value;
    const gsm = document.getElementById("gsm").value;
    const paperTexture = document.getElementById("paperTexture").value;
    const copies = document.getElementById("qty").value;

    let total_price = document.getElementById("total_price").value;
    total_price = parseFloat(
        total_price.replace("₱", "")
            .replace(/,/g, "")
            .trim()
    );

    if (isNaN(total_price)) {
        showValidationModal("Invalid total price.");
        return;
    }

    try {
        const cartResponse = await fetch("shopping_cart.php", {
            method: "POST"
        });
        const cartData = await cartResponse.json();

        if (cartData.status !== "success") {
            showValidationModal("Cart Error: " + cartData.message);
            return;
        }

        const customizationResponse = await fetch("customization.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                file_id: window.uploadedFileId,
                paper_size: paperSize,
                gsm: gsm,
                paper_texture: paperTexture,
                copies: copies,
                total_price: total_price
            })
        });

        const customizationData = await customizationResponse.json();

        if (customizationData.status !== "success") {
            showValidationModal("Customization Error: " + customizationData.message);
            return;
        }

        const customization_id = customizationData.customization_id;

        if (!customization_id) {
            showValidationModal("Customization ID missing from server response.");
            return;
        }

        const cartItemResponse = await fetch("shopping_cart_items.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                product_id: product_id,
                unit_price: total_price,
                file_id: window.uploadedFileId,
                customization_id: customization_id
            })
        });

        const cartItemData = await cartItemResponse.json();

        if (cartItemData.status !== "success") {
            showValidationModal("Cart Item Error: " + cartItemData.message);
            return;
        }

        const modal = new bootstrap.Modal(
            document.getElementById('exampleModal')
        );
        modal.show();

        sessionStorage.removeItem("product_id");
        console.log("SUCCESS: item added with customization_id:", customization_id);

    } catch (error) {
        console.error("FETCH ERROR:", error);
        showValidationModal("Network or server error occurred.");
    }
}
