<?php
session_start();
require 'db.php';

/* ---------- DATA FROM DB ---------- */
$books = $pdo->query("SELECT b.*, a.name AS author_name FROM books b JOIN author a ON b.authorID = a.authorID ORDER BY b.title")->fetchAll();
$genres = $pdo->query("SELECT DISTINCT genre FROM books ORDER BY genre")->fetchAll(PDO::FETCH_COLUMN);
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BookVerse – Online Book Store</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Base styles -->
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<!-- ====================== LOADING SPINNER ====================== -->
<div id="loadingSpinner" class="spinner" style="display: none;">
    <div class="spinner-content"></div>
</div>

<!-- ====================== NAVBAR ====================== -->
<header>
    <nav>
        <a href="#" class="logo">BookVerse</a>
        <ul class="nav-links">
            <li><a href="#hero">Home</a></li>
            <li><a href="#catalog">Catalog</a></li>
            <?php if ($user): ?>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
        <button id="openCart" class="cart-btn" aria-label="Open cart" <?php echo !$user ? 'disabled' : ''; ?>>
            🛒 <span id="cartCount" class="cart-count">0</span>
        </button>
    </nav>
</header>

<!-- ====================== HERO ====================== -->
<section id="hero">
    <h1>Discover Your Next Great Read</h1>
    <p>
        Explore thousands of titles across every genre. Whether you're into
        thrilling mysteries, inspiring biographies, or timeless classics,
        we've got you covered.
    </p>
    <a href="#catalog" class="btn-primary">Browse Books</a>
</section>

<!-- ====================== CATALOG ====================== -->
<section id="catalog">
    <h2>Book Catalog</h2>

    <div class="filters">
        <input type="text" id="searchInput" placeholder="Search by title or author..." />
        <select id="genreSelect">
            <option value="all">All Genres</option>
            <?php foreach ($genres as $g): ?>
                <option value="<?= htmlspecialchars($g) ?>"><?= htmlspecialchars($g) ?></option>
            <?php endforeach; ?>
        </select>
        <select id="sortSelect">
            <option value="title-asc">Title (A-Z)</option>
            <option value="title-desc">Title (Z-A)</option>
            <option value="price-asc">Price (Low to High)</option>
            <option value="price-desc">Price (High to Low)</option>
        </select>
    </div>

    <div class="book-grid" id="bookGrid">
        <?php foreach ($books as $b): ?>
        <div class="card"
             data-genre="<?= htmlspecialchars($b['genre']) ?>"
             data-title="<?= strtolower(htmlspecialchars($b['title'])) ?>"
             data-author="<?= strtolower(htmlspecialchars($b['author_name'])) ?>"
             data-price="<?= $b['price'] ?>">
            <img src="<?= htmlspecialchars($b['image']) ?>" alt="<?= htmlspecialchars($b['title']) ?> cover" />
            <div class="card-body">
                <h3 class="card-title"><?= htmlspecialchars($b['title']) ?></h3>
                <p class="card-author">by <?= htmlspecialchars($b['author_name']) ?></p>
                <span class="card-genre"><?= htmlspecialchars($b['genre']) ?></span>
                <div class="card-footer">
                    <span class="card-price">$<?= number_format($b['price'], 2) ?></span>
                    <?php if ($b['quantity'] > 0): ?>
                        <button class="add-to-cart" data-id="<?= $b['id'] ?>">Add to Cart</button>
                    <?php else: ?>
                        <span class="out-of-stock">Out of Stock</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ====================== CART MODAL ====================== -->
<div id="cartModal" class="modal" aria-hidden="true">
    <div class="modal-content" role="dialog" aria-modal="true">
        <span id="closeCart" class="close" aria-label="Close cart">×</span>
        <h2>Your Cart</h2>

        <!-- cart lines -->
        <div id="cartItems"></div>

        <!-- money -->
        <h3 id="cartTotal">Total: $0.00</h3>

        <!-- feedback after success -->
        <p id="orderSuccess" class="order-success">Order placed! 🎉</p>

        <!-- buttons -->
        <div class="modal-actions">
            <button id="clearCart" class="btn-primary">Clear Cart</button>
            <button id="checkoutBtn" class="btn-primary">Checkout</button>
            <button id="placeOrderBtn" class="btn-primary" style="display:none;">Place Order</button>
        </div>
    </div>
</div>

<!-- ====================== RECEIPT MODAL ====================== -->
<div id="receiptModal" class="modal" aria-hidden="true">
    <div class="modal-content" role="dialog" aria-modal="true">
        <span id="closeReceipt" class="close" aria-label="Close receipt">×</span>
        <h2>Order Receipt</h2>
        <div id="receiptItems"></div>
        <h3 id="receiptTotal"></h3>
        <div class="modal-actions">
            <button id="closeReceiptBtn" class="btn-primary">Close</button>
        </div>
    </div>
</div>

<!-- ====================== FOOTER ====================== -->
<footer>
    © 2025 BookVerse. Crafted with ❤ for book lovers everywhere.
</footer>

<!-- ====================== JAVASCRIPT ====================== -->
<script>
(function () {
    "use strict";

    /* ---------- STATE ---------- */
    let cart = JSON.parse(localStorage.getItem("cart") || "[]");
    const cartCount = document.getElementById("cartCount");
    const bookGrid = document.getElementById("bookGrid");
    const searchInput = document.getElementById("searchInput");
    const genreSelect = document.getElementById("genreSelect");
    const sortSelect = document.getElementById("sortSelect");
    const cartModal = document.getElementById("cartModal");
    const cartItems = document.getElementById("cartItems");
    const cartTotal = document.getElementById("cartTotal");
    const clearBtn = document.getElementById("clearCart");
    const checkoutBtn = document.getElementById("checkoutBtn");
    const placeBtn = document.getElementById("placeOrderBtn");
    const successMsg = document.getElementById("orderSuccess");
    const loadingSpinner = document.getElementById("loadingSpinner");
    const receiptModal = document.getElementById("receiptModal");
    const receiptItems = document.getElementById("receiptItems");
    const receiptTotal = document.getElementById("receiptTotal");
    const closeReceiptBtn = document.getElementById("closeReceiptBtn");

    init();

    /* ---------- INIT ---------- */
    function init() {
        renderBooks();
        updateCartUI();
        attachListeners();
        <?php if (!$user): ?>
            cart = [];
            updateCartUI();
        <?php endif; ?>
    }

    /* ---------- RENDERING ---------- */
    function renderBooks() {
        const q = searchInput.value.toLowerCase().trim();
        const genre = genreSelect.value;
        const sort = sortSelect.value;
        const cards = Array.from(bookGrid.querySelectorAll(".card"));
        let visible = 0;

        // Clear "No books found" message if present
        if (bookGrid.querySelector("p") && bookGrid.querySelector("p").textContent.includes("No books found")) {
            bookGrid.querySelector("p").remove();
        }

        // Filter cards
        cards.forEach(c => {
            const title = c.dataset.title || "";
            const author = c.dataset.author || "";
            const cardGenre = c.dataset.genre || "";
            const matchQ = title.includes(q) || author.includes(q);
            const matchG = genre === "all" || cardGenre === genre;

            if (matchQ && matchG) {
                c.style.display = "";
                visible++;
            } else {
                c.style.display = "none";
            }
        });

        // Sort cards
        cards.sort((a, b) => {
            const aTitle = a.dataset.title;
            const bTitle = b.dataset.title;
            const aPrice = parseFloat(a.dataset.price);
            const bPrice = parseFloat(b.dataset.price);

            if (sort === "title-asc") return aTitle.localeCompare(bTitle);
            if (sort === "title-desc") return bTitle.localeCompare(aTitle);
            if (sort === "price-asc") return aPrice - bPrice;
            if (sort === "price-desc") return bPrice - aPrice;
            return 0;
        });

        cards.forEach(c => bookGrid.appendChild(c));

        if (visible === 0) {
            const noBooksMsg = document.createElement("p");
            noBooksMsg.style.gridColumn = "1 / -1";
            noBooksMsg.style.textAlign = "center";
            noBooksMsg.textContent = "No books found.";
            bookGrid.appendChild(noBooksMsg);
        }
    }

    function updateCartUI() {
        const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
        cartCount.textContent = totalQty;

        if (!cartItems) return;

        if (cart.length === 0) {
            cartItems.innerHTML = '<p style="text-align:center;margin-top:1rem;">Your cart is empty.</p>';
            cartTotal.textContent = "Total: $0.00";
            successMsg.style.display = "none";
            checkoutBtn.style.display = "none";
            clearBtn.style.display = "none";
            localStorage.setItem("cart", JSON.stringify(cart));
            return;
        }

        let total = 0;
        const html = cart.map(item => {
            const card = bookGrid.querySelector(`.card [data-id="${item.id}"]`)?.closest(".card");
            if (!card) return "";

            const title = card.querySelector(".card-title").textContent;
            const price = parseFloat(card.querySelector(".card-price").textContent.slice(1));
            const imgSrc = card.querySelector("img").src;
            total += price * item.qty;

            return `
            <div class="cart-item">
                <img src="${imgSrc}" alt="${title} cover">
                <div class="cart-info">
                    <h4>${title}</h4>
                    <p>$${price.toFixed(2)}</p>
                </div>
                <div class="qty-controls">
                    <button onclick="changeQty(${item.id}, -1)">−</button>
                    <span>${item.qty}</span>
                    <button onclick="changeQty(${item.id}, 1)">+</button>
                </div>
            </div>`;
        }).join("");

        cartItems.innerHTML = html;
        cartTotal.textContent = `Total: $${total.toFixed(2)}`;
        checkoutBtn.style.display = "inline-block";
        clearBtn.style.display = "inline-block";
        successMsg.style.display = "none";
        localStorage.setItem("cart", JSON.stringify(cart));
    }

    function showReceipt(data) {
        let total = 0;
        const html = data.items.map(item => {
            const card = bookGrid.querySelector(`.card [data-id="${item.id}"]`)?.closest(".card");
            if (!card) return "";

            const title = card.querySelector(".card-title").textContent;
            const price = parseFloat(card.querySelector(".card-price").textContent.slice(1));
            total += price * item.qty;

            return `
            <div class="cart-item">
                <img src="${card.querySelector("img").src}" alt="${title} cover">
                <div class="cart-info">
                    <h4>${title}</h4>
                    <p>$${price.toFixed(2)} x ${item.qty}</p>
                </div>
            </div>`;
        }).join("");

        receiptItems.innerHTML = html;
        receiptTotal.textContent = `Total: $${total.toFixed(2)}`;
        receiptModal.classList.add("active");
        receiptModal.setAttribute("aria-hidden", "false");
    }

    /* ---------- EVENTS ---------- */
    function attachListeners() {
        searchInput.addEventListener("input", renderBooks);
        genreSelect.addEventListener("change", renderBooks);
        sortSelect.addEventListener("change", renderBooks);

        document.getElementById("openCart").addEventListener("click", () => {
            cartModal.classList.add("active");
            cartModal.setAttribute("aria-hidden", "false");
        });

        document.getElementById("closeCart").addEventListener("click", () => {
            cartModal.classList.remove("active");
            cartModal.setAttribute("aria-hidden", "true");
        });

        cartModal.addEventListener("click", e => {
            if (e.target === cartModal) {
                cartModal.classList.remove("active");
                cartModal.setAttribute("aria-hidden", "true");
            }
        });

        document.querySelectorAll(".add-to-cart").forEach(button => {
            button.addEventListener("click", () => {
                const id = parseInt(button.dataset.id);
                addToCart(id);
            });
        });

        clearBtn.addEventListener("click", () => {
            cart = [];
            updateCartUI();
        });

        checkoutBtn.addEventListener("click", () => {
            checkoutBtn.style.display = "none";
            placeBtn.style.display = "inline-block";
        });

        placeBtn.addEventListener("click", async () => {
            if (cart.length === 0) return;

            placeBtn.disabled = true;
            placeBtn.textContent = "Placing…";
            loadingSpinner.style.display = "flex";

            try {
                const res = await fetch("checkout.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ items: cart })
                });
                const data = await res.json();

                if (data.success) {
                    successMsg.style.display = "block";
                    showReceipt({ items: cart });
                    cart = [];
                    updateCartUI();
                    renderBooks();
                    checkoutBtn.style.display = "inline-block";
                    placeBtn.style.display = "none";
                } else {
                    alert(data.message || "Checkout failed.");
                    checkoutBtn.style.display = "inline-block";
                    placeBtn.style.display = "none";
                }
            } catch (e) {
                alert("Unable to place the order. Please try again.");
                checkoutBtn.style.display = "inline-block";
                placeBtn.style.display = "none";
            } finally {
                placeBtn.disabled = false;
                placeBtn.textContent = "Place Order";
                loadingSpinner.style.display = "none";
            }
        });

        closeReceiptBtn.addEventListener("click", () => {
            receiptModal.classList.remove("active");
            receiptModal.setAttribute("aria-hidden", "true");
        });

        receiptModal.addEventListener("click", e => {
            if (e.target === receiptModal) {
                receiptModal.classList.remove("active");
                receiptModal.setAttribute("aria-hidden", "true");
            }
        });
    }

    /* ---------- PUBLIC CART OPS ---------- */
    window.addToCart = id => {
        const found = cart.find(i => i.id === id);
        if (found) {
            found.qty++;
        } else {
            cart.push({ id, qty: 1 });
        }
        updateCartUI();
    };

    window.changeQty = (id, d) => {
        const item = cart.find(i => i.id === id);
        if (!item) return;
        item.qty += d;
        if (item.qty <= 0) {
            cart = cart.filter(i => i.id !== id);
        }
        updateCartUI();
    };
})();
</script>
</body>
</html>