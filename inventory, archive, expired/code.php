<?php
include('dbconfig.php');

$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

if(isset($_POST['registerbtn']))
{
    $first_name = $_POST['first_name'];
    $mid_name = $_POST['mid_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['confirmpassword'];
    $branch = $_POST['branch'];
    $usertype = $_POST['usertype'];


    if($password === $cpassword)
    {
        $query = "INSERT INTO register (first_name, mid_name, last_name, email, password, branch, usertype) VALUES ('$first_name',' $mid_name', '$last_name', '$email', '$password', '$branch', '$usertype')";
        $query_run = mysqli_query($connection, $query);
    
        if($query_run)
        {
            $_SESSION['success'] = "Admin Profile Added";
            header('Location: register.php');
        }
        else
        {
            $_SESSION['status'] = "Admin Profile NOT Added";
            header('Location: register.php');
        }
    
    }
    else{
        $_SESSION['status'] = "Password and confirm password does not match";
        header('Location: register.php');
    }
}
// ####################################################################
// UPDATE BUTTONS
if(isset($_POST['updatebtn']))
{
    $id = $_POST['edit_id'];
    $first_name = $_POST['edit_firstname'];
    $mid_name = $_POST['edit_mid_name'];
    $last_name = $_POST['edit_lastname']; // Fix the typo here
    $email = $_POST['edit_email'];
    $password = $_POST['edit_password'];
    $branch = $_POST['branch'];
    $usertypeupdate = $_POST['update_usertype'];

    // Use the correct variable names in the query
    $query = "UPDATE register SET first_name='$first_name', mid_name='$mid_name' ,last_name='$last_name', email='$email', password='$password', usertype='$usertypeupdate', branch='$branch' WHERE id='$id' ";

    $query_run = mysqli_query($connection, $query);
    
    if($query_run)
    {
        $_SESSION['success'] = "Your data is updated";
        header('Location: register.php');
    }
    else
    {
        $_SESSION['status'] = "Your data is not updated"; // Fix the typo here
        header('Location: register.php');
    }
}

if (isset($_POST['updatecategorybtn'])) {
    $id = $_POST['edit_id'];
    $category = $_POST['edit_category'];

    $query = "UPDATE category_list SET category_name='$category' WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) 
    {
        $_SESSION['success'] = "Your data is updated";
        header('Location: add_category.php');
    } 
    else 
    {
        $_SESSION['status'] = "Your data is not Updated";
        header('Location: add_category.php');
    }
}

if (isset($_POST['updateunitbtn'])) {
    $id = $_POST['edit_unit_btn'];
    $edit_unit = $_POST['edit_unit'];

    $query = "UPDATE unit_list SET unit_name='$edit_unit' WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) 
    {
        $_SESSION['success'] = "Your data is updated";
        header('Location: add_unit.php');
    } 
    else 
    {
        $_SESSION['status'] = "Your data is not Updated";
        header('Location: add_unit.php');
    }
}

if (isset($_POST['updatediscountbtn'])) {
    $id = $_POST['edit_id'];
    $discount_name = $_POST['edit_discount'];
    $value = $_POST['edit_value'];

    $query = "UPDATE discount_list SET discount_name='$discount_name', value='$value' WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) 
    {
        $_SESSION['success'] = "Your data is updated";
        header('Location: add_discount.php');
    } 
    else 
    {
        $_SESSION['status'] = "Your data is not Updated";
        header('Location: add_discount.php');
    }
}

if (isset($_POST['updateproductbtn'])) {
    $id = $_POST['edit_id']; // Assuming you have an 'edit_id' field in your form
    $prod_name = $_POST['prod_name'];
    $prod_code = $_POST['prod_code'];
    $categories = $_POST['categories']; // Corrected variable name
    $type = $_POST['type'];
    $unit = $_POST['unit'];
    $measurement = $_POST['measurement'];
    $prescription = isset($_POST['prescription']) ? 1 : 0;

    // Corrected query and parameter binding
    $query = "UPDATE product_list SET prod_name='$prod_name', prod_code='$prod_code', categories='$categories', type='$type', unit='$unit', measurement='$measurement', prescription='$prescription' WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        $_SESSION['success'] = "Your data is updated";
        header('Location: product.php');
    } else {
        $_SESSION['status'] = "Your data is not updated";
        header('Location: product.php');
    }
}

if (isset($_POST['update_type_btn'])) {
    $id = $_POST['edit_id'];
    $type = $_POST['edit_type'];

    $query = "UPDATE product_type_list SET type_name='$type' WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) 
    {
        $_SESSION['success'] = "Your data is updated";
        header('Location: add_product_type.php');
    } 
    else 
    {
        $_SESSION['status'] = "Your data is not Updated";
        header('Location: add_product_type.php');
    }
}





if (isset($_POST['update_stocks_btn'])) {
    $edit_id = $_POST['edit_id'];
    $sku = $_POST['sku'];
    $descript = $_POST['descript'];
    $expiry_date = $_POST['expiry_date'];
    $new_quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $branch = $_POST['branch'];

    // Retrieve the original quantity and product name
    $query = "SELECT * FROM add_stock_list WHERE id='$edit_id'";
    $query_run = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($query_run);
    $original_quantity = $row['quantity'];
    $product_name = $row['product_stock_name'];

    // Determine the change in quantity
    $quantity_change = $new_quantity - $original_quantity;

    // Retrieve all rows with the same product name
    $query_same_product = "SELECT * FROM add_stock_list WHERE product_stock_name='$product_name'";
    $query_run_same_product = mysqli_query($connection, $query_same_product);

    // Apply the change in quantity to rows with the same product name
    while ($row_same_product = mysqli_fetch_assoc($query_run_same_product)) {
        $current_stock = $row_same_product['stocks_available'];
        $new_stocks = $current_stock + $quantity_change;
        $new_stocks = max(0, $new_stocks); // Make sure stocks don't go negative

        $id_same_product = $row_same_product['id'];
        $updateQuerySameProduct = "UPDATE add_stock_list SET stocks_available='$new_stocks' WHERE id='$id_same_product'";
        mysqli_query($connection, $updateQuerySameProduct);
    }

    // Update the edited row with the new values
    $updateQuery = "UPDATE add_stock_list SET sku='$sku', expiry_date='$expiry_date',descript='$descript',  quantity='$new_quantity', price='$price', branch='$branch' WHERE id='$edit_id'";
    mysqli_query($connection, $updateQuery);

    if ($updateQuery) {
        $_SESSION['success'] = "Stocks Updated";
        header('Location: add_stocks.php');
    } else {
        $_SESSION['status'] = "Stocks NOT Updated";
        header('Location: add_stocks.php');
    }
}






if (isset($_POST['update_buffer_stock_btn'])) {
    $edit_id = $_POST['edit_id'];
    $sku = $_POST['sku'];
    $expiry_date = $_POST['expiry_date'];
    $new_quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $branch = $_POST['branch'];

    // Retrieve the original quantity
    $query = "SELECT * FROM buffer_stock_list WHERE id='$edit_id'";
    $query_run = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($query_run);
    $original_quantity = $row['quantity'];

    // Determine the change in quantity
    $quantity_change = $new_quantity - $original_quantity;

    // Retrieve all rows in the table with the same product name
    $product_name = $row['buffer_stock_name'];
    $query_all_rows = "SELECT * FROM buffer_stock_list WHERE buffer_stock_name='$product_name'";
    $query_run_all_rows = mysqli_query($connection, $query_all_rows);

    // Apply the change in quantity to all rows
    while ($row_all_rows = mysqli_fetch_assoc($query_run_all_rows)) {
        $current_stock = $row_all_rows['buffer_stocks_available'];
        $new_stocks = $current_stock + $quantity_change;
        $new_stocks = max(0, $new_stocks); // Make sure stocks don't go negative

        $id = $row_all_rows['id'];
        $updateQuerySameProduct = "UPDATE buffer_stock_list SET buffer_stocks_available='$new_stocks' WHERE id='$id'";
        mysqli_query($connection, $updateQuerySameProduct);
    }

    // Update the edited row with the new values
    $updateQuery = "UPDATE buffer_stock_list SET sku='$sku', expiry_date='$expiry_date', quantity='$new_quantity', price='$price', branch='$branch' WHERE id='$edit_id'";

    mysqli_query($connection, $updateQuery);

    if ($updateQuery) {
        $_SESSION['success'] = "Buffer Stock Updated";
        header('Location: buffer_stock.php');
    } else {
        $_SESSION['status'] = "Buffer Stock NOT Updated";
        header('Location: buffer_stock.php');
    }
}


// ####################################################################
// UPDATE BUTTONS




if(isset($_POST['delete_btn']))
{
    $id = $_POST['delete_id'];

    $query = "DELETE FROM register WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['success'] = "Your data is Deleted";
        header('Location: register.php');
    }
    else
    {
        $_SESSION['status'] = "Your data is Not Deleted";
        header('Location: register.php');
    }
}


//this code is for the login
if(isset($_POST['login_btn']))
{
    $email_login = $_POST['email'];
    $password_login = $_POST['password'];

    $query = "SELECT * FROM register WHERE email='$email_login' AND password='$password_login' ";
    $query_run = mysqli_query($connection, $query);

    if(mysqli_fetch_array($query_run))
    {
        $_SESSION['username'] = $email_login;
        header('Location: index.php');
    }
    else
    {
        $_SESSION['status'] = 'email id  /password  is invalid';
        header('Location: login.php');
    }
}



//this code is for add_category.php
if(isset($_POST['categorybtn']))
{
    $category_name = $_POST['category_name'];
   
    if($category_name)
    {
        $query = "INSERT INTO category_list (category_name) VALUES ('$category_name')";
        $query_run = mysqli_query($connection, $query);
    
        if($query_run)
        {
            $_SESSION['success'] = "Category Added";
            header('Location: add_category.php');
        }
        else
        {
            $_SESSION['status'] = "Category NOT Added";
            header('Location: add_category.php');
        }
    }
}
//this code is for product_type_list.php
if(isset($_POST['typebtn']))
{
    $type_name = $_POST['type_name'];
   
    if($type_name)
    {
        $query = "INSERT INTO product_type_list (type_name) VALUES ('$type_name')";
        $query_run = mysqli_query($connection, $query);
    
        if($query_run)
        {
            $_SESSION['success'] = "Product Type Added";
            header('Location: add_product_type.php');
        }
        else
        {
            $_SESSION['status'] = "Product Type NOT Added";
            header('Location: add_product_type.php');
        }
    }
}
if(isset($_POST['unitbtn']))
{
    $unit_name = $_POST['unit_name'];
   
    if($unit_name)
    {
        $query = "INSERT INTO unit_list (unit_name) VALUES ('$unit_name')";
        $query_run = mysqli_query($connection, $query);
    
        if($query_run)
        {
            $_SESSION['success'] = "Unit Added";
            header('Location: add_unit.php');
        }
        else
        {
            $_SESSION['status'] = "Unit NOT Added";
            header('Location: add_unit.php');
        }
    }
}
//this code is for product_type_list.php
if(isset($_POST['discountbtn']))
{
    $discount_name = $_POST['discount_name'];
    $value = $_POST['value'];
   
    if($discount_name)
    {
        $query = "INSERT INTO discount_list (discount_name, value) VALUES ('$discount_name', '$value')";
        $query_run = mysqli_query($connection, $query);
    
        if($query_run)
        {
            $_SESSION['success'] = "Discount Added";
            header('Location: add_discount.php');
        }
        else
        {
            $_SESSION['status'] = "Discount Added NOT Added";
            header('Location: add_discount.php');
        }
    }
}





// ####################################################################
// ADD BUTTONS
if (isset($_POST['add_prod_btn'])) {
    $product_name = $_POST['prod_name'];
    $product_code = $_POST['prod_code'];
    $categories = $_POST['categories']; // Corrected variable name
    $type = $_POST['type'];
    $unit = $_POST['unit'];
    $stocks_available = $_POST['stocks_available'];
    $measurement = $_POST['measurement'];
    $prescription = isset($_POST['prescription']) ? 1 : 0;
    $discounted = isset($_POST['discounted']) ? 1 : 0;

    // Check if $category_name is not empty
    if ($categories) {
        // Corrected query and parameter binding
        $query = "INSERT INTO product_list (prod_name, prod_code, categories, type, unit, measurement, prescription, discounted) VALUES ('$product_name', '$product_code', '$categories', '$type', '$unit', '$measurement', '$prescription', '$discounted')";
        $query_run = mysqli_query($connection, $query);

        if ($query_run)
        {
            header('Location: product.php'); // Updated redirection
        }
    }
}

if (isset($_POST['add_stock_btn'])) {
    $sku = $_POST['sku'];
    $product_name = $_POST['product_stock_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $branch = $_POST['branch'];
    $batch = $_POST['batch_no'];
    $expiry_date = $_POST['expiry_date'];
    $date_added = date('Y-m-d H:i:s'); // Current date and time

    // Execute query to add stock
    $add_stock_query = "INSERT INTO add_stock_list (sku, product_stock_name, quantity, price, branch, batch_no, expiry_date, date_added)
                        VALUES ('$sku', '$product_name','$quantity', '$price', '$branch', '$batch', '$expiry_date', '$date_added')";
    $add_stock_result = mysqli_query($connection, $add_stock_query);

    if ($add_stock_result) {
        // Update Stocks Available in product_list table
        $update_stocks_query = "UPDATE product_list 
                                SET stocks_available = stocks_available + '$quantity' 
                                WHERE prod_name = '$product_name'";
        mysqli_query($connection, $update_stocks_query);

    
        header('Location: add_stocks.php');
    } else {
        
        header('Location: add_stocks.php');
    }
}






/*if (isset($_POST['add_stock_btn'])) {
    $product_stock_name = $_POST['product_stock_name'];
    $expiry_date = $_POST['expiry_date'];
    $quantity = $_POST['quantity'];
    
    $price = $_POST['price'];

    // Check if entry with the same product name already exists
    $check_query = "SELECT * FROM add_stock_list WHERE product_stock_name='$product_stock_name'";
    $check_query_run = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_query_run) > 0) {
        // Entry exists, check expiry date
        $existing_row = mysqli_fetch_assoc($check_query_run);
        $existing_expiry_date = $existing_row['expiry_date'];

        if ($existing_expiry_date == $expiry_date) {
            // Expiry dates match, update only the "Stocks Available" column
            $new_quantity = $existing_row['quantity'] + $quantity;

            // Update only the "Stocks Available" column in the add_stock_list table
            $update_query = "UPDATE add_stock_list SET quantity=$new_quantity WHERE product_stock_name='$product_stock_name' AND expiry_date='$expiry_date'";
            mysqli_query($connection, $update_query);
        } else {
            // Expiry dates are different, insert a new entry
            $insert_query = "INSERT INTO add_stock_list (product_stock_name, expiry_date, quantity, price) VALUES ('$product_stock_name', '$expiry_date', $quantity, $price)";
            mysqli_query($connection, $insert_query);
        }
    } else {
        // Entry doesn't exist, insert a new entry
        $insert_query = "INSERT INTO add_stock_list (product_stock_name, expiry_date, quantity, price) VALUES ('$product_stock_name', '$expiry_date', $quantity, $price)";
        mysqli_query($connection, $insert_query);
    }

    // Redirect to the add_stocks.php page
    header("Location: add_stocks.php");
}*/



if (isset($_POST['add_buffer_stock_btn'])) {
    $buffer_stock_name = $_POST['buffer_stock_name'];
    $expiry_date = $_POST['expiry_date'];
    $quantity = $_POST['quantity'];
    $descript = $_POST['descript'];
    $price = $_POST['price'];
    $branch = $_POST['branch'];
    $sku = $_POST['sku'];

    // Retrieve the current stocks_available value
    $query = "SELECT * FROM buffer_stock_list WHERE buffer_stock_name='$buffer_stock_name'";
    $query_run = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($query_run);
    $currentStocks = $row['buffer_stocks_available'];

    // Calculate the new stocks_available value
    $newStocks = $quantity + $currentStocks ; 

    // Update the database with the new value
    $updateQuery = "UPDATE buffer_stock_list SET buffer_stocks_available=$newStocks WHERE buffer_stock_name='$buffer_stock_name'";
    mysqli_query($connection, $updateQuery);

    // Continue with the rest of your code to insert the new stock information into the database
    $query = "INSERT INTO buffer_stock_list (sku, buffer_stock_name, descript, expiry_date, quantity, buffer_stocks_available, price, branch) VALUES ('$sku', '$buffer_stock_name', '$descript', '$expiry_date', '$quantity', '$newStocks','$price','$branch')";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        $_SESSION['success'] = "Product Added";
        header('Location: buffer_stock.php');
    } else {
        $_SESSION['status'] = "Product NOT Added";
        header('Location: buffer_stock.php');
    }
}



// ADD BUTTONS
// ####################################################################


// RESTORE BUTTONS
// ####################################################################
if (isset($_POST['restore_btn'])) {
    $restore_id = $_POST['restore_id'];

    // Get information about the record to be restored
    $get_info_query = "SELECT * FROM archive_list WHERE id = $restore_id";
    $get_info_result = mysqli_query($connection, $get_info_query);

    if ($get_info_result && mysqli_num_rows($get_info_result) > 0) {
        $row = mysqli_fetch_assoc($get_info_result);

        // Check if 'product_name' key exists in $row
        $product_name = isset($row['product_name']) ? $row['product_name'] : '';

        // Restore the data to the add_stock_list table
        $restore_query = "INSERT INTO add_stock_list (sku, product_stock_name, descript, expiry_date, quantity, stocks_available, price, branch)
        VALUES ('{$row['sku']}', '$product_name', '{$row['descript']}', '{$row['expiry_date']}', {$row['quantity']}, {$row['quantity']}, {$row['price']}, '{$row['branch']}')";
        mysqli_query($connection, $restore_query);

        // Update the stocks_available in add_stock_list
        $update_stocks_query = "UPDATE add_stock_list SET stocks_available = stocks_available + {$row['quantity']} WHERE product_stock_name = '$product_name'";
        mysqli_query($connection, $update_stocks_query);

        // Delete the data from the archive_list table
        $delete_query = "DELETE FROM archive_list WHERE id = $restore_id";
        mysqli_query($connection, $delete_query);

        $_SESSION['success'] = "Data restored successfully!";
        header('Location: archive.php');
    } else {
        $_SESSION['error'] = "Record not found!";
        header('Location: archive.php');
    }
}


// ####################################################################
// RESTORE BUTTONS








// ####################################################################
// DELETE BUTTONS
if(isset($_POST['delete_prod_btn']))
{
    $id = $_POST['delete_id'];

    $query = "DELETE FROM product_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['success'] = "Your data is Deleted";
        header('Location: product.php');
    }
    else
    {
        $_SESSION['status'] = "Your data is Not Deleted";
        header('Location: product.php');
    }
}


if(isset($_POST['delete_category_btn']))
{
    $id = $_POST['delete_id'];

    $query = "DELETE FROM category_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['success'] = "Your data is Deleted";
        header('Location: add_category.php');
    }
    else
    {
        $_SESSION['status'] = "Your data is Not Deleted";
        header('Location: add_category.php');
    }
}

if(isset($_POST['delete_discount_btn']))
{
    $id = $_POST['delete_id'];

    $query = "DELETE FROM discount_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['success'] = "Your data is Deleted";
        header('Location: add_discount.php');
    }
    else
    {
        $_SESSION['status'] = "Your data is Not Deleted";
        header('Location: add_discount.php');
    }
}


if(isset($_POST['delete_product_type']))
{
    $id = $_POST['delete_id'];

    $query = "DELETE FROM product_type_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['success'] = "Your data is Deleted";
        header('Location: add_product_type.php');
    }
    else
    {
        $_SESSION['status'] = "Your data is Not Deleted";
        header('Location: add_product_type.php');
    }
}

if(isset($_POST['permanent_delete_btn']))
{
    $id = $_POST['delete_id'];

    $query = "DELETE FROM archive_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['success'] = "Your data is Deleted";
        header('Location: archive.php');
    }
    else
    {
        $_SESSION['status'] = "Your data is Not Deleted";
        header('Location: archive.php');
    }
}


if(isset($_POST['permanent_delete_expired_btn']))
{
    $id = $_POST['delete_id'];

    $query = "DELETE FROM expired_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['success'] = "Your data is Deleted";
        header('Location: expired_products.php');
    }
    else
    {
        $_SESSION['status'] = "Your data is Not Deleted";
        header('Location: expired_products.php');
    }
}





if (isset($_POST['move_to_archive_btn'])) {
    $move_id = $_POST['move_id'];

    // Get information about the record to be moved
    $get_info_query = "SELECT * FROM add_stock_list WHERE id = $move_id";
    $get_info_result = mysqli_query($connection, $get_info_query);

    if ($get_info_result && mysqli_num_rows($get_info_result) > 0) {
        $row = mysqli_fetch_assoc($get_info_result);

        // Move the data to the archive_list table
        $move_query = "INSERT INTO archive_list (sku, product_name, descript, quantity, stocks_available, price, branch, expiry_date)
               VALUES ('{$row['sku']}', '{$row['product_stock_name']}', '{$row['descript']}', {$row['quantity']}, {$row['stocks_available']}, {$row['price']}, '{$row['branch']}', '{$row['expiry_date']}')";

        mysqli_query($connection, $move_query);

        // Update the stocks_available in add_stock_list
        $update_stocks_query = "UPDATE add_stock_list SET stocks_available = stocks_available - {$row['quantity']} WHERE product_stock_name = '{$row['product_stock_name']}'";
        mysqli_query($connection, $update_stocks_query);

        // Delete the data from the add_stock_list table
        $delete_query = "DELETE FROM add_stock_list WHERE id = $move_id";
        mysqli_query($connection, $delete_query);

        $_SESSION['success'] = "Data moved to archive successfully!";
        header('Location: add_stocks.php');
    } else {
        $_SESSION['error'] = "Record not found!";
        header('Location: add_stocks.php');
    }
}


if (isset($_POST['move_buffer_to_archive_btn'])) {
    $move_id = $_POST['move_id'];

    // Get information about the record to be moved
    $get_info_query = "SELECT * FROM buffer_stock_list WHERE id = $move_id";
    $get_info_result = mysqli_query($connection, $get_info_query);

    if ($get_info_result && mysqli_num_rows($get_info_result) > 0) {
        $row = mysqli_fetch_assoc($get_info_result);

        // Move the data to the archive_list table
        $move_query = "INSERT INTO archive_list (product_name, expiry_date, quantity, stocks_available, price, branch)
               VALUES ('{$row['buffer_stock_name']}', '{$row['expiry_date']}', {$row['quantity']}, {$row['buffer_stocks_available']}, {$row['price']}, '{$row['branch']}')";
        mysqli_query($connection, $move_query);


        // Update the stocks_available in buffer_stock_list
        $update_stocks_query = "UPDATE buffer_stock_list SET buffer_stocks_available = buffer_stocks_available - {$row['quantity']} WHERE buffer_stock_name = '{$row['buffer_stock_name']}'";
        mysqli_query($connection, $update_stocks_query);

        // Delete the data from the add_stock_list table
        $delete_query = "DELETE FROM buffer_stock_list WHERE id = $move_id";
        mysqli_query($connection, $delete_query);

        $_SESSION['success'] = "Data moved to archive successfully!";
        header('Location: buffer_stock.php');
    } else {
        $_SESSION['error'] = "Record not found!";
        header('Location: buffer_stock.php');
    }
}



// DELETE BUTTONS
// ####################################################################


// MOVE BUTTONS
// ####################################################################
// Move Stock Button
if (isset($_POST['move_stock_btn'])) {
    $move_id = $_POST['move_id'];

    // Retrieve the row to be moved
    $query_row_to_move = "SELECT * FROM add_stock_list WHERE id='$move_id'";
    $query_run_row_to_move = mysqli_query($connection, $query_row_to_move);
    $row_to_move = mysqli_fetch_assoc($query_run_row_to_move);

    // Retrieve the product name and quantity of the row to be moved
    $product_name_to_move = $row_to_move['product_stock_name'];
    $quantity_to_move = $row_to_move['quantity'];

    // Retrieve all rows with the same product name in add_stock_list
    $query_same_product_add_stock = "SELECT * FROM add_stock_list WHERE product_stock_name='$product_name_to_move'";
    $query_run_same_product_add_stock = mysqli_query($connection, $query_same_product_add_stock);
    $new_stocks = 0;
    // Apply the decrease in quantity to rows with the same product name in add_stock_list
    while ($row_same_product_add_stock = mysqli_fetch_assoc($query_run_same_product_add_stock)) {
        $current_stock = $row_same_product_add_stock['stocks_available'];
        $new_stocks = $current_stock - $quantity_to_move;
        $new_stocks = max(0, $new_stocks); // Make sure stocks don't go negative

        $id_same_product_add_stock = $row_same_product_add_stock['id'];
        $updateQuerySameProductAddStock = "UPDATE add_stock_list SET stocks_available='$new_stocks' WHERE id='$id_same_product_add_stock'";
        mysqli_query($connection, $updateQuerySameProductAddStock);
    }

    // Move the row from add_stock_list to buffer_stock_list
    $insertQueryBufferStock = "INSERT INTO buffer_stock_list (buffer_stock_name, expiry_date, quantity, buffer_stocks_available, price)
                                VALUES ('$product_name_to_move', '{$row_to_move['expiry_date']}', '$quantity_to_move', '$new_stocks', '{$row_to_move['price']}')";

    // Note: We use $new_stocks here to set buffer_stocks_available

    $deleteQueryAddStock = "DELETE FROM add_stock_list WHERE id='$move_id'";

    mysqli_query($connection, $insertQueryBufferStock);
    mysqli_query($connection, $deleteQueryAddStock);

    if ($insertQueryBufferStock && $deleteQueryAddStock) {
        $_SESSION['success'] = "Stock Moved to Buffer Stock";
        header('Location: add_stocks.php');
    } else {
        $_SESSION['status'] = "Stock NOT Moved";
        header('Location: add_stocks.php');
    }
}





// Move Buffer Stock Button
// Move Buffer Stock Button
// Move Buffer Stock Button
if (isset($_POST['move_buffer_stock_btn'])) {
    $move_id = $_POST['move_id'];

    // Retrieve the row to be moved
    $query_row_to_move = "SELECT * FROM buffer_stock_list WHERE id='$move_id'";
    $query_run_row_to_move = mysqli_query($connection, $query_row_to_move);
    $row_to_move = mysqli_fetch_assoc($query_run_row_to_move);

    // Retrieve the product name and quantity of the row to be moved
    $product_name_to_move = $row_to_move['buffer_stock_name'];
    $quantity_to_move = $row_to_move['quantity'];

    // Retrieve all rows with the same product name in buffer_stock_list
    $query_same_product_buffer_stock = "SELECT * FROM buffer_stock_list WHERE buffer_stock_name='$product_name_to_move'";
    $query_run_same_product_buffer_stock = mysqli_query($connection, $query_same_product_buffer_stock);
    $new_stocks = 0;
    // Apply the decrease in quantity to rows with the same product name in buffer_stock_list
    while ($row_same_product_buffer_stock = mysqli_fetch_assoc($query_run_same_product_buffer_stock)) {
        $current_stock = $row_same_product_buffer_stock['buffer_stocks_available'];
        $new_stocks = $current_stock - $quantity_to_move;
        $new_stocks = max(0, $new_stocks); // Make sure stocks don't go negative

        $id_same_product_buffer_stock = $row_same_product_buffer_stock['id'];
        $updateQuerySameProductBufferStock = "UPDATE buffer_stock_list SET buffer_stocks_available='$new_stocks' WHERE id='$id_same_product_buffer_stock'";
        mysqli_query($connection, $updateQuerySameProductBufferStock);
    }

    // Retrieve the current stocks_available value in add_stock_list
    $query_stocks_available = "SELECT * FROM add_stock_list WHERE product_stock_name='$product_name_to_move'";
    $query_run_stocks_available = mysqli_query($connection, $query_stocks_available);
    $row_stocks_available = mysqli_fetch_assoc($query_run_stocks_available);
    $current_stocks_available = $row_stocks_available['stocks_available'];

    // Calculate the new stocks_available value in add_stock_list
    $new_stocks_available = $current_stocks_available + $quantity_to_move;

    // Update the add_stock_list table with the new stocks_available value
    $updateQueryAddStock = "UPDATE add_stock_list SET stocks_available='$new_stocks_available' WHERE product_stock_name='$product_name_to_move'";
    mysqli_query($connection, $updateQueryAddStock);

    // Move the row from buffer_stock_list to add_stock_list
    $insertQueryAddStock = "INSERT INTO add_stock_list (product_stock_name, expiry_date, quantity, stocks_available, price, branch)
    VALUES ('$product_name_to_move', '{$row_to_move['expiry_date']}', '$quantity_to_move', '$new_stocks_available', '{$row_to_move['price']}', '{$row_to_move['branch']}')";

    $deleteQueryBufferStock = "DELETE FROM buffer_stock_list WHERE id='$move_id'";

    mysqli_query($connection, $insertQueryAddStock);
    mysqli_query($connection, $deleteQueryBufferStock);

    if ($insertQueryAddStock && $deleteQueryBufferStock) {
        $_SESSION['success'] = "Buffer Stock Moved to Main Stock";
        header('Location: buffer_stock.php');
    } else {
        $_SESSION['status'] = "Buffer Stock NOT Moved";
        header('Location: buffer_stock.php');
    }
}

// MOVE BUTTONS
// ####################################################################








date_default_timezone_set('Asia/Manila');

// Get the current date and time in the Philippines timezone
$current_time = new DateTime('now', new DateTimeZone('Asia/Manila'));

// Format the current time
$date = $current_time->format('Y-m-d'); 
$time = $current_time->format('h:i:s A'); 
$am_pm = $current_time->format('A');
$ref_no = $_POST['ref_no'];
$sub_total = $_POST['sub_total'];
// Function to generate transaction number
function generateTransactionNo($date, $count) {
    return $date . str_pad($count, 3, '0', STR_PAD_LEFT); // Format: YYMMDDXXX
}

if (isset($_POST['mode_of_payment']) && isset($_POST['charge_btn'])) {
    // Get the mode of payment
    $mode_of_payment = $_POST['mode_of_payment'];
    // Get the total amount
    $total_amount = $_POST['total'];
    

    // Retrieve the list of items from the hidden input field
    $list_of_items = $_POST['list_of_items'];

    // Pagtanggap ng mga impormasyon mula sa AJAX request
    $scannedProducts = $_POST['scannedProducts'];
    $productList = json_decode($_POST['productList']); // I-decode muna ang JSON string

    // Loop through the productList and concatenate it
    $listOfItems = "";
    foreach ($productList as $productName) {
        $listOfItems .= $productName . ", "; // Dapat ito ay nakaayos depende sa iyong requirements
    }
    // Ito ay upang alisin ang huling ", " sa dulo ng list
    $listOfItems = rtrim($listOfItems, ", ");

    // Get the count of existing transactions
    $transaction_count_query = "SELECT COUNT(*) AS transaction_count FROM transaction_list WHERE date = '$date'";
    $transaction_count_result = mysqli_query($connection, $transaction_count_query);
    $row = mysqli_fetch_assoc($transaction_count_result);
    $count = $row['transaction_count'] + 1;

    // Generate transaction number
    $transaction_no = generateTransactionNo(date('ymd'), $count);

    // Insert the transaction details into the transaction_list table
    $insert_query = "INSERT INTO transaction_list (transaction_no, date, time, am_pm, mode_of_payment, total_amount, sub_total, list_of_items, ref_no) 
                     VALUES ('$transaction_no', '$date', '$time', '$am_pm', '$mode_of_payment', '$total_amount', '$sub_total,', '$listOfItems', '$ref_no')";

    // Execute the query
    $result = mysqli_query($connection, $insert_query);

    if ($result) {
        $_SESSION['success'] = "Transaction recorded successfully";
        // Clear the scanned products session
        unset($_SESSION['scannedProducts']);
        header('Location: pos.php');
        exit();
    } else {
        $_SESSION['status'] = "Failed to record transaction";
        header('Location: pos.php');
        exit();
    }
}

if (isset($_POST['charge_btn'])) {
    // Handle charging logic here
    $total = $_POST['total'];

    // Retrieve the list of items from the hidden input field
    $list_of_items = $_POST['list_of_items'];

    if($total)
    {
        // Pagtanggap ng mga impormasyon mula sa AJAX request
        $scannedProducts = $_POST['scannedProducts'];
        $productList = json_decode($_POST['productList']); // I-decode muna ang JSON string

        // Loop through the productList and concatenate it
        $listOfItems = "";
        foreach ($productList as $productName) {
            $listOfItems .= $productName . ", "; // Dapat ito ay nakaayos depende sa iyong requirements
        }
        // Ito ay upang alisin ang huling ", " sa dulo ng list
        $listOfItems = rtrim($listOfItems, ", ");

        // Get the count of existing transactions
        $transaction_count_query = "SELECT COUNT(*) AS transaction_count FROM transaction_list WHERE date = '$date'";
        $transaction_count_result = mysqli_query($connection, $transaction_count_query);
        $row = mysqli_fetch_assoc($transaction_count_result);
        $count = $row['transaction_count'] + 1;

        // Generate transaction number
        $transaction_no = generateTransactionNo(date('ymd'), $count);

        // Format the total amount with two decimal places
        $formatted_total_amount = sprintf("%.2f", $total);

        // Insert the transaction details into the transaction_list table
        $query = "INSERT INTO transaction_list (transaction_no, total, list_of_items) VALUES ('$transaction_no', '$formatted_total_amount', '$listOfItems')";
        $query_run = mysqli_query($connection, $query);
    
        if($query_run)
        {
            $_SESSION['success'] = "Category Added";
            header('Location: pos.php');
        }
        else
        {
            $_SESSION['status'] = "Category NOT Added";
            header('Location: pos.php');
        }
    }
}












if(isset($_POST['logout_btn']))
{
    session_destroy();
    unset($_SESSION['username']);
}











?>
