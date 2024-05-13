<?php
$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

// Query to get the expiring soon count and details for add_stock_list
$expiring_soon_query = "SELECT COUNT(*) as expiring_soon_count, GROUP_CONCAT(product_stock_name) as expiring_soon_products FROM add_stock_list WHERE expiry_date BETWEEN CURDATE() AND CURDATE() + INTERVAL 7 DAY";
$expiring_soon_result = mysqli_query($connection, $expiring_soon_query);
$expiring_soon_data = mysqli_fetch_assoc($expiring_soon_result);
$expiring_soon_count = $expiring_soon_data['expiring_soon_count'];
$expiring_soon_products = $expiring_soon_data['expiring_soon_products'];

// Query to get the expiring soon count and details for buffer_stock_list
$expiring_soon_buffer_query = "SELECT COUNT(*) as expiring_soon_buffer_count, GROUP_CONCAT(buffer_stock_name) as expiring_soon_buffer_products FROM buffer_stock_list WHERE expiry_date BETWEEN CURDATE() AND CURDATE() + INTERVAL 7 DAY";
$expiring_soon_buffer_result = mysqli_query($connection, $expiring_soon_buffer_query);
$expiring_soon_buffer_data = mysqli_fetch_assoc($expiring_soon_buffer_result);
$expiring_soon_buffer_count = $expiring_soon_buffer_data['expiring_soon_buffer_count'];
$expiring_soon_buffer_products = $expiring_soon_buffer_data['expiring_soon_buffer_products'];


// Query to get the count of expired products
$expired_count_query = "SELECT COUNT(*) as expired_count FROM expired_list";
$expired_count_result = mysqli_query($connection, $expired_count_query);
$expired_count_data = mysqli_fetch_assoc($expired_count_result);
$expired_count = $expired_count_data['expired_count'];
?>

<script>
// Update the notification badge count in the header
document.addEventListener('DOMContentLoaded', function () {
    const expiringSoonCount = <?php echo $expiring_soon_count + $expiring_soon_buffer_count; ?>;
    const expiredCount = <?php echo $expired_count; ?>;
    const badgeCounter = document.querySelector('.badge-counter');

    if (badgeCounter) {
        const totalCount = expiringSoonCount + expiredCount;
        badgeCounter.innerHTML = totalCount > 0 ? totalCount : '';
    }

    const expiringSoonLink = document.getElementById('expiringSoonLink');
    const expiredLink = document.getElementById('expiredLink');

    if (expiringSoonLink) {
        expiringSoonLink.addEventListener('click', function () {
            // Redirect to add_stocks.php when "Expiring Soon in Stocks" is clicked
            window.location.href = 'add_stocks.php';
        });
    }

    if (expiringSoonBufferLink) {
        expiringSoonBufferLink.addEventListener('click', function () {
            // Redirect to buffer_stocks.php when "Expiring Soon in Buffer" is clicked
            window.location.href = 'buffer_stocks.php';
        });
    }

    if (expiredLink) {
        expiredLink.addEventListener('click', function () {
            // Redirect to expired_products.php when "Expired Products" is clicked
            window.location.href = 'expired_products.php';
        });
    }
});
</script>
