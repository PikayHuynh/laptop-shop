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
            <div class="flex-between">
                <h2>Category list</h2>
                <a href="index.php?controller=category&action=create" class="btn btn-primary">Add Category</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cat['category_id']); ?></td>
                            <td><?php echo htmlspecialchars($cat['name']); ?></td>
                            <td><?php echo htmlspecialchars($cat['description'] ?? ''); ?></td>
                            <td>
                                <a href="index.php?controller=category&action=edit&id=<?php echo $cat['category_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="index.php?controller=category&action=delete&id=<?php echo $cat['category_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No categories found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <nav class="pagination">
                <?php if ($page > 1): ?>
                    <a href="index.php?controller=category&action=index&page=<?php echo $page - 1; ?>">&laquo; Prev</a>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <?php if ($p == $page): ?>
                        <strong><?php echo $p; ?></strong>
                    <?php else: ?>
                        <a href="index.php?controller=category&action=index&page=<?php echo $p; ?>"><?php echo $p; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="index.php?controller=category&action=index&page=<?php echo $page + 1; ?>">Next &raquo;</a>
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
