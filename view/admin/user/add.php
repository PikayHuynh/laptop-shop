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
            <h2 class="h4">Add New User</h2>
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
            <form method="POST" action="<?php echo BASE_URL; ?>admin/create-user">
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                </div>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                    <select class="form-select" id="role_id" name="role_id" required>
                        <option value="">Select a role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role['role_id']; ?>">
                                <?php echo htmlspecialchars($role['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-outline-primary m-2" >Create User</button>
                <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline-success m-2">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/../layout/main-footer.php';
?>

<?php
include __DIR__ . '/../layout/footer.php';
?>
