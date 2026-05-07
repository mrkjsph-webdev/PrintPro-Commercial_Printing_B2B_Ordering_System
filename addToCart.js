async function addToCart() {

    /* ---------------- FILE CHECK ---------------- */
    if (!window.currentFileId) {
        alert("Please upload a file first.");
        return;
    }

    /* ---------------- PRODUCT ID ---------------- */
    const product_id = sessionStorage.getItem("product_id");

    console.log("Retrieved product_id:", product_id);

    if (!product_id) {
        alert("Product ID missing. Please go back and select product again.");
        return;
    }

    /* ---------------- CUSTOMIZATION VALUES ---------------- */
    const paperSize = document.getElementById("paperSize").value;
    const gsm = document.getElementById("gsm").value;
    const paperTexture = document.getElementById("paperTexture").value;
    const copies = document.getElementById("qty").value;

    /* ---------------- PRICE ---------------- */
    let price = document.getElementById("price").value;

    price = parseFloat(
        price.replace("₱", "")
             .replace(/,/g, "")
             .trim()
    );

    if (isNaN(price)) {
        alert("Invalid price.");
        return;
    }

    try {

        /* ---------------- CREATE CART ---------------- */
        const cartResponse = await fetch("shopping_cart.php", {
            method: "POST"
        });

        const cartData = await cartResponse.json();

        console.log("CART RESPONSE:", cartData);

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
                price: price

            })
        });

        const customizationData = await customizationResponse.json();

        console.log("CUSTOMIZATION RESPONSE:", customizationData);

        if (customizationData.status !== "success") {
            alert("Customization Error: " + customizationData.message);
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
                unit_price: price,
                file_id: window.currentFileId

            })
        });

        const cartItemData = await cartItemResponse.json();

        console.log("CART ITEM RESPONSE:", cartItemData);

        if (cartItemData.status !== "success") {
            alert("Cart Item Error: " + cartItemData.message);
            return;
        }

        /* ---------------- FINAL SUCCESS FLOW ---------------- */

        const modal = new bootstrap.Modal(
            document.getElementById('exampleModal')
        );

        modal.show();

        console.log("SUCCESS: item added to cart");

        /* Clear ONLY after success */
        sessionStorage.removeItem("product_id");

    } catch (error) {
        console.error("FETCH ERROR:", error);
        alert("Network or server error occurred.");
    }
}