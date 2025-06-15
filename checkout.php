<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to checkout.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$items = $input['items'] ?? [];

if (empty($items)) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Validate stock
    foreach ($items as $item) {
        $stmt = $pdo->prepare("SELECT quantity FROM books WHERE id = ?");
        $stmt->execute([$item['id']]);
        $book = $stmt->fetch();
        if (!$book || $book['quantity'] < $item['qty']) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Insufficient stock for one or more items.']);
            exit;
        }
    }

    // Create order
    $stmt = $pdo->prepare("INSERT INTO `Order` (customerID, orderDate) VALUES (?, NOW())");
    $stmt->execute([$_SESSION['user']['id']]);
    $order_id = $pdo->lastInsertId();

    // Add order details and update stock
    foreach ($items as $item) {
        $stmt = $pdo->prepare("INSERT INTO orderDetails (orderID, bookID, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$order_id, $item['id'], $item['qty']]);

        $stmt = $pdo->prepare("UPDATE books SET quantity = quantity - ? WHERE id = ?");
        $stmt->execute([$item['qty'], $item['id']]);
    }

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Checkout failed: ' . $e->getMessage()]);
}
?>