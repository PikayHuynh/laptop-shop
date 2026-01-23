<?php
require_once __DIR__ . '/../../../config/config.php';
include __DIR__ . '/../layout/header.php';
?>

<?php
include __DIR__ . '/../layout/sidebar.php';
?>

<?php
include __DIR__ . '/../layout/main-header.php';
?>

<div class="bg-secondary rounded h-100 p-4">
    <div class="row my-4">
        <div class="col-10">
            <h2 class="main-title">Roles Management</h2>
        </div>
        <div class="col-2">
            <a href="<?php echo BASE_URL; ?>admin/add-role" class="btn btn-outline-primary m-2">
                <i class="feather icon-plus"></i> Add New Role
            </a>
        </div>
        

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <table class="table table-hover text-center align-middle">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $role): ?>
                            <tr>
                                <td><?php echo $role['role_id']; ?></td>
                                <td><?php echo htmlspecialchars($role['name']); ?></td>
                                <td>
                                    <a class="btn btn-outline-success m-2" href="<?php echo BASE_URL; ?>admin/show-role?id=<?php echo $role['role_id']; ?>">Show</a>
                                    <a class="btn btn-outline-warning m-2" href="<?php echo BASE_URL; ?>admin/edit-role?id=<?php echo $role['role_id']; ?>">Edit</a>
                                    <a class="btn btn-outline-danger m-2" href="<?php echo BASE_URL; ?>admin/delete-role?id=<?php echo $role['role_id'] ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No roles found</td>
                        </tr>
                    <?php endif; ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
include __DIR__ . '/../layout/main-footer.php';
?>

<?php
include __DIR__ . '/../layout/footer.php';
?>