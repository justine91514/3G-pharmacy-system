<!-- product_info.php -->
<?php
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['product_stock_name'] . '</td>';
        echo '<td>' . $row['quantity'] . '</td>';
        echo '<td>' . $row['price'] . '</td>';
        echo '</tr>';
    }
} else {
    echo 'Error executing query: ' . mysqli_error($conn);
}
mysqli_close($conn);
?>
