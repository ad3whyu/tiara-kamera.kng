<?php

if (isset($_SESSION['alert']) && isset($_SESSION['message'])) {
    $alert_type = $_SESSION['alert'];
    $alert_message = $_SESSION['message'];

    echo '<div class="alert alert-' . htmlspecialchars($alert_type) . ' alert-dismissible fade show" role="alert">
        <strong>' . htmlspecialchars($alert_message) . '</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';

    unset($_SESSION['alert']);
    unset($_SESSION['message']);
}
?>
