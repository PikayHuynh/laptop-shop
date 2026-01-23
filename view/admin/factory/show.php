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
            <h2 class="h4">Factory Details</h2>
        </div>
        <div class="col-2 text-end">
            <a href="<?php echo BASE_URL; ?>admin/factories" class="btn btn-outline-light m-2">Back to List</a>
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
            <div class="factory-details">
                <dl class="row">
                    <dt class="col-sm-3">ID</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($factory['factory_id']); ?></dd>

                    <dt class="col-sm-3">Name</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($factory['name']); ?></dd>

                    <dt class="col-sm-3">Description</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($factory['description'] ?? 'N/A'); ?></dd>

                    <?php if (!empty($factory['created_at'])): ?>
                        <dt class="col-sm-3">Created At</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($factory['created_at']); ?></dd>
                    <?php endif; ?>
                </dl>

                <a href="<?php echo BASE_URL; ?>admin/factories" class="btn btn-outline-success m-2">Back to List</a>
                <a href="<?php echo BASE_URL; ?>admin/edit-factory?id=<?php echo $factory['factory_id']; ?>" class="btn btn-outline-warning m-2">Edit Factory</a>
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
