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
    <div class="row mb-4">
        <div class="col-10">
            <h2 class="h4">User Details</h2>
        </div>
        <div class="col-2 text-end">
            <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline-light m-2">Back to List</a>
        </div>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <div class="row">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="user-details">
                <dl class="row">
                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($user['email']); ?></dd>

                    <dt class="col-sm-3">Full Name</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($user['full_name']); ?></dd>

                    <dt class="col-sm-3">Role</dt>
                    <dd class="col-sm-9">
                        <?php
                            $roleName = '';
                            foreach ($roles as $role) {
                                if ($role['role_id'] == $user['role_id']) { $roleName = $role['name']; break; }
                            }
                            echo htmlspecialchars($roleName);
                        ?>
                    </dd>

                    <?php if (!empty($user['created_at'])): ?>
                        <dt class="col-sm-3">Created At</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($user['created_at']); ?></dd>
                    <?php endif; ?>

                    <?php if (isset($user['status'])): ?>
                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($user['status']); ?></dd>
                    <?php endif; ?>
                </dl>

                <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline-success m-2">Back to List</a>
                <a href="<?php echo BASE_URL; ?>admin/edit-user/<?php echo $user['user_id']; ?>" class="btn btn-outline-warning m-2">Edit User</a>
            </div>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/../layout/main-footer.php';
?>

<?php
include __DIR__ . '/../layout/footer.php';
?>
