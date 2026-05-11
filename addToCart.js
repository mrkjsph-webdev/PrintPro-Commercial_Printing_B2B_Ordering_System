async function addToCart() {

    /* ---------------- FILE CHECK ---------------- */
    if (!window.currentFileId) {
        alert("Please upload a file first.");
        return;
    }

    /* ---------------- PRODUCT ID ---------------- */
    const product_id = sessionStorage.getItem("product_id");

    if (!product_id) {
        alert("Product ID missing. Please select a product again.");
        return;
    }

    /* ---------------- CUSTOMIZATION VALUES ---------------- */
    const paperSize = document.getElementById("paperSize").value;
    const gsm = document.getElementById("gsm").value;
    const paperTexture = document.getElementById("paperTexture").value;
    const copies = document.getElementById("qty").value;

    /* ---------------- PRICE ---------------- */
    let total_price = document.getElementById("total_price").value;

    total_price = parseFloat(
        total_price.replace("₱", "")
                   .replace(/,/g, "")
                   .trim()
    );

    if (isNaN(total_price)) {
        alert("Invalid total price.");
        return;
    }

    try {

        /* ---------------- CREATE / GET ACTIVE CART ---------------- */
        const cartResponse = await fetch("shopping_cart.php", {
            method: "POST"
        });

        const cartData = await cartResponse.json();

        if (cartData.status !== "success") {
            alert("Cart Error: " + cartData.message);
            return;
        }

        /* ---------------- SAVE CUSTOMIZATION ---------------- */
        const customizationResponse = await fetch("customization.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({

                file_id: window.currentFileId,
                paper_size: paperSize,
                gsm: gsm,
                paper_texture: paperTexture,
                copies: copies,
                total_price: total_price

            })
        });

        const customizationData = await customizationResponse.json();

        if (customizationData.status !== "success") {
            alert("Customization Error: " + customizationData.message);
            return;
        }

        /* ---------------- IMPORTANT VALUE ---------------- */
        const customization_id = customizationData.customization_id;

        if (!customization_id) {
            alert("Customization ID missing from server response.");
            return;
        }

        /* ---------------- ADD TO CART ITEMS ---------------- */
        const cartItemResponse = await fetch("shopping_cart_items.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({

                product_id: product_id,
                unit_price: total_price,  
                file_id: window.currentFileId,
                customization_id: customization_id

            })
        });

        const cartItemData = await cartItemResponse.json();

        if (cartItemData.status !== "success") {
            alert("Cart Item Error: " + cartItemData.message);
            return;
        }

        /* ---------------- SUCCESS ---------------- */

        const modal = new bootstrap.Modal(
            document.getElementById('exampleModal')
        );

        modal.show();

        sessionStorage.removeItem("product_id");

        console.log("SUCCESS: item added with customization_id:", customization_id);

    } catch (error) {
        console.error("FETCH ERROR:", error);
        alert("Network or server error occurred.");
    }
}
