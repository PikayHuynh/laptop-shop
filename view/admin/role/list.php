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
    require_once __DIR__ . '/../../../model/role.php';

    $roleModel = new role($conn);
    $perPage = 10;
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $res = $roleModel->getPaginated($page, $perPage);
    $roles = $res['data'];
    $totalPages = $res['totalPages'];
    $page = $res['page'];
?>

<main class="main users chart-page" id="skip-target">
    <div class="container">
        <div class="admin-users">
            <div class="flex-between">
                <h2>Role list</h2>
                <a href="index.php?controller=role&action=create" class="btn btn-primary">Add Role</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($roles)): ?>
                    <?php foreach ($roles as $r): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($r['role_id']); ?></td>
                            <td><?php echo htmlspecialchars($r['name']); ?></td>
                            <td>
                            <a href="index.php?controller=role&action=edit&id=<?php echo $r['role_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="index.php?controller=role&action=delete&id=<?php echo $r['role_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">No roles found.</td></tr>
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