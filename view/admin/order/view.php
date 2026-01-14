<?php
    include __DIR__ . '/../layout/header.php';
?>

<?php
    include __DIR__ . '/../layout/sidebar.php';
?>

<?php
    include __DIR__ . '/../layout/main-header.php';
?>

<main class="main users chart-page" id="skip-target">
    <div class="container">
        <div class="admin-users">
            <h2>View Order #<?php echo htmlspecialchars($order['order_id']); ?></h2>
            
            <div class="order-info">
                <p><strong>User:</strong> <?php echo htmlspecialchars($order['full_name'] ?? 'N/A'); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                <p><strong>Status:</strong> <span class="badge badge-<?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span></p>
            </div>

            <h3>Order Items</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($items)): ?>
                    <?php $total = 0; ?>
                    <?php foreach ($items as $item): ?>
                        <?php $itemTotal = $item['price'] * $item['quantity']; $total += $itemTotal; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td>$<?php echo htmlspecialchars($item['price']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>$<?php echo number_format($itemTotal, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Grand Total:</strong></td>
                        <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                    </tr>
                <?php else: ?>
                    <tr><td colspan="4">No items in order.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <a href="index.php?controller=order&action=index" class="btn btn-secondary">Back</a>
        </div>
    </div>
</main>

<?php
    include __DIR__ . '/../layout/main-footer.php';
?>

<?php
    include __DIR__ . '/../layout/footer.php';
?>
