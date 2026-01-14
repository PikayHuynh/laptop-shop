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
            <h2>Edit Factory</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" class="form">
                <div class="form-group">
                    <label for="name">Factory Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($factory['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($factory['description'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="index.php?controller=factory&action=index" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php
    include __DIR__ . '/../layout/main-footer.php';
?>

<?php
    include __DIR__ . '/../layout/footer.php';
?>
