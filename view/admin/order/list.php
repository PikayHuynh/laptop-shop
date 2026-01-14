<?php
    include __DIR__ . '/../layout/header.php';
?>

<?php
    include __DIR__ . '/../layout/sidebar.php';
?>

<?php
    include __DIR__ . '/../layout/main-header.php';
?>

<?php
    require_once __DIR__ . '/../../../config/database.php';
    require_once __DIR__ . '/../../../model/order.php';

    $orderModel = new order($conn);
    $perPage = 10;
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $res = $orderModel->getPaginated($page, $perPage);
    $orders = $res['data'];
    $totalPages = $res['totalPages'];
    $page = $res['page'];
?>

<main class="main users chart-page" id="skip-target">
    <div class="container">
        <div class="admin-users">
            <h2>Order list</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($o['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($o['full_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($o['order_date']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($o['status']); ?>">
                                    <?php echo htmlspecialchars($o['status']); ?>
                                </span>
                            </td>
                            <td>
                            <a href="index.php?controller=order&action=view&id=<?php echo $o['order_id']; ?>" class="btn btn-sm btn-info">View</a>
                                <a href="index.php?controller=order&action=edit&id=<?php echo $o['order_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="index.php?controller=order&action=delete&id=<?php echo $o['order_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No orders found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <nav class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>">&laquo; Prev</a>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <?php if ($p == $page): ?>
                        <strong><?php echo $p; ?></strong>
                    <?php else: ?>
                        <a href="?page=<?php echo $p; ?>"><?php echo $p; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</main>

<?php
    include __DIR__ . '/../layout/main-footer.php';
?>

<?php
    include __DIR__ . '/../layout/footer.php';
?>
