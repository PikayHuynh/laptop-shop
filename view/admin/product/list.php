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
    require_once __DIR__ . '/../../../model/product.php';

    $productModel = new product($conn);
    $perPage = 10;
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $res = $productModel->getPaginated($page, $perPage);
    $products = $res['data'];
    $totalPages = $res['totalPages'];
    $page = $res['page'];
?>

<main class="main users chart-page" id="skip-target">
    <div class="container">
        <div class="admin-users">
            <div class="flex-between">
                <h2>Product list</h2>
                <a href="index.php?controller=product&action=create" class="btn btn-primary">Add Product</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Factory</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $prod): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($prod['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($prod['name']); ?></td>
                            <td>$<?php echo htmlspecialchars($prod['price']); ?></td>
                            <td><?php echo htmlspecialchars($prod['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($prod['factory_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($prod['category_name'] ?? 'N/A'); ?></td>
                            <td>
                            <a href="index.php?controller=product&action=edit&id=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="index.php?controller=product&action=delete&id=<?php echo $prod['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7">No products found.</td></tr>
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
