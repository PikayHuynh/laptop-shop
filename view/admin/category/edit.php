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
            <h2 class="h4">Edit Category</h2>
        </div>
        <div class="col-2 text-end">
            <a href="<?php echo BASE_URL; ?>admin/categories" class="btn btn-outline-light m-2">Back to List</a>
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
            <form method="POST" action="<?php echo BASE_URL; ?>admin/update-category" class="form-category">
                <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="name" class="form-control" id="name" name="name" 
                        value="<?php echo htmlspecialchars($category['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <input type="description" class="form-control" id="description" name="description" 
                        value="<?php echo htmlspecialchars($category['description']); ?>" required>
                </div>

                <button type="submit" class="btn btn-outline-warning m-2">Update category</button>
                <a href="<?php echo BASE_URL; ?>admin/categories" class="btn btn-outline-success m-2">Cancel</a>
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
