<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$orders = $pdo->prepare("SELECT o.orderID, o.orderDate, SUM(od.quantity * b.price) as total
                        FROM `Order` o JOIN orderDetails od ON o.orderID = od.orderID
                        JOIN books b ON od.bookID = b.id
                        WHERE o.customerID = ?
                        GROUP BY o.orderID
                        ORDER BY o.orderDate DESC");
$orders->execute([$user_id]);
$orders = $orders->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BookVerse – Order History</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <header>
        <nav>
            <a href="#" class="logo">BookVerse</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section id="orders">
        <h2>Your Orders</h2>
        <?php if (empty($orders)): ?>
            <p>No orders found.</p>
        <?php else: ?>
            <div class="order-list">
                <?php foreach ($orders as $o): ?>
                    <?php
                    $details = $pdo->prepare("SELECT b.title, b.price, od.quantity
                                             FROM orderDetails od
                                             JOIN books b ON od.bookID = b.id
                                             WHERE od.orderID = ?");
                    $details->execute([$o['orderID']]);
                    $details = $details->fetchAll();
                    ?>
                    <div class="order-card">
                        <h3>Order #<?= $o['orderID'] ?> - <?= date('F j, Y', strtotime($o['orderDate'])) ?></h3>
                        <ul>
                            <?php foreach ($details as $d): ?>
                                <li><?= htmlspecialchars($d['title']) ?> (Qty: <?= $d['quantity'] ?>) - $<?= number_format($d['price'] * $d['quantity'], 2) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <p><strong>Total:</strong> $<?= number_format($o['total'], 2) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <footer>
        © 2025 BookVerse. Crafted with ❤ for book lovers everywhere.
    </footer>
</body>
</html>