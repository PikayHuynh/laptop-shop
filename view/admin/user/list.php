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
        <div class="row">
            <div class="col-10">
                <h2 class="main-title">Users Management</h2>
            </div>
            <div class="col-2">
                <a href="<?php echo $baseUrl; ?>/admin/add-user" class="primary-default-btn">
                    <i class="feather icon-plus"></i> Add New User
                </a>
            </div>
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

        <div class="users-table table-wrapper">
            <table class="posts-table">
            <thead>
                <tr class="users-table-info">
                <th>
                    <label class="users-table__checkbox ms-20">
                    <input type="checkbox" class="check-all">Thumbnail
                    </label>
                </th>
                <th>ID</th>
                <th>Email</th>
                <th>Full Name</th>
                <th>Role</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <label class="users-table__checkbox">
                                    <input type="checkbox" class="check">
                                    <div class="categories-table-img">
                                        <picture><source srcset="<?php echo asset_url('admin/template/img/categories/01.webp'); ?>" type="image/webp"><img src="<?php echo asset_url('admin/template/img/categories/01.jpg'); ?>" alt="category"></picture>
                                    </div>
                                    </label>
                                </td>
                                <td><?php echo $user['user_id']; ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        <?php echo htmlspecialchars($user['role_name']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="p-relative">
                                    <button class="dropdown-btn transparent-btn" type="button" title="More info">
                                        <div class="sr-only">More info</div>
                                        <i data-feather="more-horizontal" aria-hidden="true"></i>
                                    </button>
                                    <ul class="users-item-dropdown dropdown">
                                        <li><a href="<?php echo $baseUrl; ?>/admin/edit-user?id=<?php echo $user['user_id']; ?>">Edit</a></li>
                                        <li><a href="<?php echo $baseUrl; ?>/admin/delete-user?id=<?php echo $user['user_id'] ?>">Delete</a></li>
                                    </ul>
                                    </span>
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
</main>

<?php
include __DIR__ . '/../layout/main-footer.php';
?>

<?php
include __DIR__ . '/../layout/footer.php';
?>