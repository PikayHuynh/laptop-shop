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

<main class="main users chart-page" id="skip-target">
    <div class="container">
        <div class="row mb-4">
            <div class="col-10">
                <h2 class="h4">Edit User</h2>
            </div>
            <div class="col-2 text-end">
                <a href="<?php echo $baseUrl; ?>/admin/users" class="secondary-default-btn">Back to List</a>
            </div>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="<?php echo $baseUrl; ?>/admin/update-user" class="form-user">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">

                            <div class="mb-input">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" 
                                    value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>

                            <div class="mb-input">
                                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="full_name" name="full_name" 
                                    value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>

                            <div class="mb-input">
                                <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" id="role_id" name="role_id" required>
                                    <option value="">Select a role</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?php echo $role['role_id']; ?>" 
                                                <?php echo $role['role_id'] == $user['role_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($role['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="upgrade-btn">Update User</button>
                                </div>
                                <div class="col-6">
                                    <a href="<?php echo $baseUrl; ?>/admin/users" class="tag-btn">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include __DIR__ . '/../layout/main-footer.php';
?>

<?php
include __DIR__ . '/../layout/footer.php';
?>
