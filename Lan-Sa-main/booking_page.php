<?php if (isset($_SESSION['debug_post_data'])): ?>
    <div class="alert alert-info">
        <strong>Debugging Info:</strong><br>
        <pre><?php print_r($_SESSION['debug_post_data']); ?></pre>
    </div>
    <?php unset($_SESSION['debug_post_data']); // Clear after displaying ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?php echo $_SESSION['error']; ?>
        <?php unset($_SESSION['error']); // Clear after displaying ?>
    </div>
<?php endif; ?>
