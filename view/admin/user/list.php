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
            <h2 class="main-title">Users Management</h2>
        </div>
        <div class="col-2">
            <a href="<?php echo BASE_URL; ?>admin/add-user" class="btn btn-outline-primary m-2">
                <i class="feather icon-plus"></i> Add New User
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
                    <th scope="col">Email</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Role</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['user_id']; ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td>
                                    <span class="<?php echo $user['role_name'] == 'ADMIN' ? 'p-1 mb-1 bg-danger text-white rounded-pill' : "p-1 mb-1 bg-success text-white rounded-pill" ?>">
                                        <?php echo htmlspecialchars($user['role_name']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a class="btn btn-outline-success m-2" href="<?php echo BASE_URL; ?>admin/show-user?id=<?php echo $user['user_id']; ?>">Show</a>
                                    <a class="btn btn-outline-warning m-2" href="<?php echo BASE_URL; ?>admin/edit-user?id=<?php echo $user['user_id']; ?>">Edit</a>
                                    <a class="btn btn-outline-danger m-2" href="<?php echo BASE_URL; ?>admin/delete-user?id=<?php echo $user['user_id'] ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No users found</td>
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