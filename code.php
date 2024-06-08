<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('dbconfig.php');
include('activity_logs.php');
date_default_timezone_set('Asia/Manila');
$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
ini_set('display_errors', 1);
error_reporting(E_ALL);

if(isset($_POST['registerbtn'])) {
    $first_name = $_POST['first_name'];
    $mid_name = $_POST['mid_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $branch = $_POST['branch'];
    $usertype = $_POST['usertype'];

    // Check if the email already exists
    $email_check_query = "SELECT * FROM register WHERE email=?";
    $stmt = mysqli_prepare($connection, $email_check_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) { // If user exists
        echo "<script>alert('Email already exists'); window.location.href = 'register.php';</script>";
        exit(); // Stop further execution
    } else {
        // Insert the new user record
        $query = "INSERT INTO register (first_name, mid_name, last_name, email, password, branch, usertype) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssss", $first_name, $mid_name, $last_name, $email, $password, $branch, $usertype);
        $query_run = mysqli_stmt_execute($stmt);

        if($query_run) {
            logActivity("Added a User Account");
            header('Location: register.php');
            exit(); // Stop further execution
        } else {
            echo "<script>alert('Registration failed'); window.location.href = 'register.php';</script>";
            exit(); // Stop further execution
        }
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
    $usertype = $_POST['usertype'];

    // Use the correct variable names in the query
    $query = "UPDATE register SET first_name='$first_name', mid_name='$mid_name' ,last_name='$last_name', email='$email', password='$password',  usertype='$usertype', branch='$branch' WHERE id='$id' ";

    $query_run = mysqli_query($connection, $query);
    
    if($query_run)
    {
        logActivity("Updated a User Account`");
        header('Location: register.php');
    }
}

if (isset($_POST['updatecategorybtn'])) {
    $id = $_POST['edit_id'];
    $category = $_POST['edit_category'];

    // Check if the category already exists
    $check_query = "SELECT * FROM category_list WHERE category_name='$category'";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Category already exists, display alert
        echo "<script>alert('Category already exists!');</script>";
        echo "<script>window.location.href = 'add_category.php';</script>";
    } else {
        // Category doesn't exist, proceed with updating
        $query = "UPDATE category_list SET category_name='$category' WHERE id='$id'";
        $query_run = mysqli_query($connection, $query);

        if ($query_run) {
            // Log activity and redirect
            logActivity("Updated a Category");
            echo "<script>window.location.href = 'add_category.php';</script>";
        } else {
            // Handle update failure
            echo "Error updating category: " . mysqli_error($connection);
            echo "<script>window.location.href = 'add_category.php';</script>";
        }
    }
}


if (isset($_POST['updateunitbtn'])) {
    $id = $_POST['edit_id'];
    $edit_unit = $_POST['edit_unit'];

    // Check if the unit already exists
    $check_query = "SELECT * FROM unit_list WHERE unit_name='$edit_unit'";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Unit already exists, display alert
        echo "<script>alert('Unit already exists!');</script>";
        echo "<script>window.location.href = 'add_unit.php';</script>";
        exit(); // Stop further execution
    } else {
        // Unit doesn't exist, proceed with updating
        $query = "UPDATE unit_list SET unit_name='$edit_unit' WHERE id='$id'";
        $query_run = mysqli_query($connection, $query);

        if ($query_run) {
            // Log activity and redirect
            logActivity("Updated a Unit");
            echo "<script>window.location.href = 'add_unit.php';</script>";
        } else {
            // Handle update failure
            echo "Error updating unit: " . mysqli_error($connection);
            echo "<script>window.location.href = 'add_unit.php';</script>";
        }
    }
}


if (isset($_POST['updatediscountbtn'])) {
    $id = $_POST['edit_id'];
    $discount_name = $_POST['edit_discount'];
    $value = $_POST['edit_value'];

    // Check if the discount name already exists
    $check_query = "SELECT * FROM discount_list WHERE discount_name='$discount_name'";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Discount name already exists, display alert
        echo "<script>alert('Discount name already exists!');</script>";
        echo "<script>window.location.href = 'add_discount.php';</script>";
        exit(); // Stop further execution
    } else {
        // Discount name doesn't exist, proceed with updating
        $query = "UPDATE discount_list SET discount_name='$discount_name', value='$value' WHERE id='$id'";
        $query_run = mysqli_query($connection, $query);

        if ($query_run) {
            // Log activity and redirect
            logActivity("Updated a Discount");
            echo "<script>window.location.href = 'add_discount.php';</script>";
        } else {
            // Handle update failure
            echo "Error updating discount: " . mysqli_error($connection);
            echo "<script>window.location.href = 'add_discunt.php';</script>";
        }
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
    $description = $_POST['description'];
    $prescription = isset($_POST['prescription']) ? 1 : 0;
    $discounted = isset($_POST['discounted']) ? 1 : 0;

    // Corrected query and parameter binding
    $query = "UPDATE product_list SET prod_name='$prod_name', prod_code='$prod_code', categories='$categories', type='$type', description='$description', measurement='$measurement', prescription='$prescription', discounted='$discounted' WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) 
    {
        logActivity("Updated a Product");

        header('Location: product.php');
    }
}

if(isset($_POST['updatesupplierbtn']))
{
    $id = $_POST['edit_id'];
    $supplier_name = $_POST['supplier_name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $agreement = $_POST['agreement'];

    if($supplier_name)
    {
        $query = "UPDATE supplier_list SET supplier_name='$supplier_name', contact='$contact', address='$address', agreement='$agreement' WHERE id='$id'";
        $query_run = mysqli_query($connection, $query);
    
        if($query_run)
        {
            logActivity("Updated a Supplier");
            header('Location: add_supplier.php');
        }
    }
}

if (isset($_POST['update_type_btn'])) {
    $id = $_POST['edit_id'];
    $type = $_POST['edit_type'];

    $query = "UPDATE product_type_list SET type_name='$type' WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) 
    {
        logActivity("Edited a Product Type");
        header('Location: add_product_type.php');
    }
}


if(isset($_POST['supplierbtn']))
{
    $supplier_name = $_POST['supplier_name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $agreement = $_POST['agreement'];

    // Check if $supplier_name is not empty
    if(!empty($supplier_name))
    {
        // Check if the supplier already exists in the database
        $check_query = "SELECT * FROM supplier_list WHERE supplier_name = '$supplier_name'";
        $check_result = mysqli_query($connection, $check_query);

        if(mysqli_num_rows($check_result) == 0) // If supplier does not exist
        {
            // Insert the new supplier
            $query = "INSERT INTO supplier_list (supplier_name, contact, address, agreement) VALUES ('$supplier_name', '$contact', '$address', '$agreement')";
            $query_run = mysqli_query($connection, $query);
        
            if($query_run)
            {
                logActivity("Added a Supplier");
                echo "<script>alert('Supplier added successfully');</script>";
                echo "<script>window.location.href = 'add_supplier.php';</script>";
            }
            else
            {
                echo "<script>alert('Failed to add supplier. Please try again.');</script>";
                echo "<script>window.location.href = 'add_supplier.php';</script>";
            }
        }
        else
        {
            echo "<script>alert('Supplier already exists. Please choose a different name.');</script>";
            
        }
    }
    else
    {
        echo "<script>alert('Supplier name cannot be empty.');</script>";
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
    $supp_name = $_POST['supp_name'];

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
    $updateQuery = "UPDATE add_stock_list SET sku='$sku', expiry_date='$expiry_date',descript='$descript',  quantity='$new_quantity', supp_name='$supp_name', price='$price', branch='$branch' WHERE id='$edit_id'";
    mysqli_query($connection, $updateQuery);

    if ($updateQuery) 
    {
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

    if ($updateQuery) 
    {
        header('Location: buffer_stock.php');
    }
}


// ####################################################################
// UPDATE BUTTONS


if (isset($_POST['delete_out_of_stock_btn'])) {
    $id_to_delete = $_POST['delete_id'];
    echo "ID to delete: " . $id_to_delete; // Add this line for debugging
    $delete_query = "DELETE FROM out_of_stock_list WHERE id = '$id_to_delete'";
    echo "Delete query: " . $delete_query; // Add this line for debugging
    $delete_result = mysqli_query($connection, $delete_query);

    if ($delete_result) {
        logActivity("Deleted An Expired Product");
        header('Location: out_of_stock.php'); // Redirect back to the page with the table
        exit();
    } else {
        header('Location: out_of_stock.php'); // Redirect back to the page with the table
        exit();
    }
}


if (isset($_POST['delete_category_btn'])) {
    $id_to_delete = $_POST['delete_id'];
    echo "ID to delete: " . $id_to_delete; // Add this line for debugging
    $delete_query = "DELETE FROM expired_list WHERE id = '$id_to_delete'";
    echo "Delete query: " . $delete_query; // Add this line for debugging
    $delete_result = mysqli_query($connection, $delete_query);

    if ($delete_result) {
        logActivity("Deleted An Expired Product");
        header('Location: expired_products.php'); // Redirect back to the page with the table
        exit();
    } else {
        header('Location: expired_products.php'); // Redirect back to the page with the table
        exit();
    }
}
if(isset($_POST['delete_btn']))
{
    $id = $_POST['delete_id'];

    $query = "DELETE FROM register WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        logActivity("Deleted a User Account");
        header('Location: register.php');
    }
}


//this code is for the login
if(isset($_POST['login_btn']))
{
    $email_login = $_POST['email'];
    $password_login = $_POST['password'];
    $time_in = $_POST['time_in'];

    $query = "SELECT * FROM register WHERE email='$email_login' AND password='$password_login' ";
    $query = "INSERT INTO user_log (time_in) VALUES ('$time_in')";
    $query_run = mysqli_query($connection, $query);

    if(mysqli_fetch_array($query_run))
    {
        $_SESSION['username'] = $email_login;
        header('Location: index.php');
    }
    else
    {
        $_SESSION['status'] = 'email id  /password  is invalid';
        header('Location: index.php');
    }
}



//this code is for add_category.php
if(isset($_POST['categorybtn']))
{
    $category_name = $_POST['category_name'];

    // Check if the category name is not empty
    if(!empty($category_name))
    {
        // Check if the category already exists in the database
        $check_query = "SELECT * FROM category_list WHERE category_name = '$category_name'";
        $check_result = mysqli_query($connection, $check_query);

        if(mysqli_num_rows($check_result) == 0) // If category does not exist
        {
            // Insert the new category
            $query = "INSERT INTO category_list (category_name) VALUES ('$category_name')";
            $query_run = mysqli_query($connection, $query);

            if($query_run)
            {
                logActivity("Added a Category");
                echo "<script>alert('Category added successfully');</script>";
                echo "<script>window.location.href = 'add_category.php';</script>";
            }
            else
            {
                echo "<script>alert('Failed to add category. Please try again.');</script>";
                echo "<script>window.location.href = 'add_category.php';</script>";
            }
        }
        else
        {
            echo "<script>alert('Category already exists.');</script>";
            echo "<script>window.location.href = 'add_category.php';</script>";
        }
    }
    else
    {
        echo "<script>alert('Category name cannot be empty.');</script>";
        echo "<script>window.location.href = 'add_category.php';</script>";
    }
}

//this code is for add_category.php
if(isset($_POST['branchbtn']))
{
    $branch = $_POST['branch'];

    // Check if the category name is not empty
    if(!empty($branch))
    {
        // Check if the category already exists in the database
        $check_query = "SELECT * FROM add_branch_list WHERE branch = '$branch'";
        $check_result = mysqli_query($connection, $check_query);

        if(mysqli_num_rows($check_result) == 0) // If category does not exist
        {
            // Insert the new category
            $query = "INSERT INTO add_branch_list (branch) VALUES ('$branch')";
            $query_run = mysqli_query($connection, $query);

            if($query_run)
            {
                logActivity("Added a Branch");
                echo "<script>alert('Branch added successfully');</script>";
                echo "<script>window.location.href = 'add_branch.php';</script>";
            }
            else
            {
                echo "<script>alert('Failed to add Branch. Please try again.');</script>";
                echo "<script>window.location.href = 'add_branch.php';</script>";
            }
        }
        else
        {
            echo "<script>alert('Branch already exists.');</script>";
            echo "<script>window.location.href = 'add_branch.php';</script>";
        }
    }
    else
    {
        echo "<script>alert('Branch name cannot be empty.');</script>";
        echo "<script>window.location.href = 'add_category.php';</script>";
    }
}


if(isset($_POST['typebtn']))
{
    $type_name = $_POST['type_name'];

    // Check if the type name is not empty
    if(!empty($type_name))
    {
        // Check if the type already exists in the database
        $check_query = "SELECT * FROM product_type_list WHERE type_name = '$type_name'";
        $check_result = mysqli_query($connection, $check_query);

        if(mysqli_num_rows($check_result) == 0) // If type does not exist
        {
            // Insert the new type
            $query = "INSERT INTO product_type_list (type_name) VALUES ('$type_name')";
            $query_run = mysqli_query($connection, $query);

            if($query_run)
            {
                logActivity("Added a Product Type");
                echo "<script>alert('Type added successfully');</script>";
                echo "<script>window.location.href = 'add_product_type.php';</script>";
            }
            else
            {
                echo "<script>alert('Failed to add Type. Please try again.');</script>";
                echo "<script>window.location.href = 'add_product_type.php';</script>";
            }
        }
        else
        {
            echo "<script>alert('Type already exists.');</script>";
            echo "<script>window.location.href = 'add_product_type.php';</script>";
        }
    }
    else
    {
        echo "<script>alert('Type name cannot be empty.');</script>";
        echo "<script>window.location.href = 'add_product_type.php';</script>";
    }
}




if(isset($_POST['unitbtn']))
{
    $unit_name = $_POST['unit_name'];

    // Check if the unit name is not empty
    if(!empty($unit_name))
    {
        // Check if the unit already exists in the database
        $check_query = "SELECT * FROM unit_list WHERE unit_name = '$unit_name'";
        $check_result = mysqli_query($connection, $check_query);

        if(mysqli_num_rows($check_result) == 0) // If unit does not exist
        {
            // Insert the new unit
            $query = "INSERT INTO unit_list (unit_name) VALUES ('$unit_name')";
            $query_run = mysqli_query($connection, $query);

            if($query_run)
            {
                logActivity("Added a Unit Type");
                echo "<script>alert('Unit added successfully');</script>";
                echo "<script>window.location.href = 'add_unit.php';</script>";
            }
            else
            {
                echo "<script>alert('Failed to add Unit. Please try again.');</script>";
                echo "<script>window.location.href = 'add_unit.php';</script>";
            }
        }
        else
        {
            echo "<script>alert('Unit already exists.');</script>";
            echo "<script>window.location.href = 'add_unit.php';</script>";
        }
    }
    else
    {
        echo "<script>alert('Unit name cannot be empty.');</script>";
        echo "<script>window.location.href = 'add_unit.php';</script>";
    }
}


//this code is for product_type_list.php
if(isset($_POST['discountbtn']))
{
    $discount_name = $_POST['discount_name'];
    $value = $_POST['value'];

    // Check if the discount name is not empty
    if(!empty($discount_name))
    {
        // Check if the discount already exists in the database
        $check_query = "SELECT * FROM discount_list WHERE discount_name = '$discount_name'";
        $check_result = mysqli_query($connection, $check_query);

        if(mysqli_num_rows($check_result) == 0) // If discount does not exist
        {
            // Insert the new discount
            $query = "INSERT INTO discount_list (discount_name, value) VALUES ('$discount_name', '$value')";
            $query_run = mysqli_query($connection, $query);

            if($query_run)
            {
                logActivity("Added New Discount");
                echo "<script>alert('Discount added successfully');</script>";
                echo "<script>window.location.href = 'add_discount.php';</script>";
            }
            else
            {
                echo "<script>alert('Failed to add discount. Please try again.');</script>";
                echo "<script>window.location.href = 'add_discount.php';</script>";
            }
        }
        else
        {
            echo "<script>alert('Discount already exists.');</script>";
            echo "<script>window.location.href = 'add_discount.php';</script>";
        }
    }
    else
    {
        echo "<script>alert('Discount name cannot be empty.');</script>";
        echo "<script>window.location.href = 'add_discount.php';</script>";
    }
}






// ####################################################################
// ADD BUTTONS
if (isset($_POST['add_prod_btn'])) {
    // Retrieve form data
    $product_name = $_POST['prod_name'];
    $description = $_POST['description'];
    $product_code = $_POST['prod_code'];
    $categories = $_POST['categories'];
    $type = $_POST['type'];
    $unit = $_POST['unit'];
    $measurement = $_POST['measurement'];
    $prescription = isset($_POST['prescription']) ? 1 : 0;
    $discounted = isset($_POST['discounted']) ? 1 : 0;

    // Check if the product name already exists in the database
    $check_query = "SELECT * FROM product_list WHERE prod_name = '$product_name'";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) == 0) {
        // Product does not exist, proceed with insertion
        $query = "INSERT INTO product_list (prod_name, description, prod_code, categories, type, unit, measurement, prescription, discounted) VALUES ('$product_name', '$description', '$product_code', '$categories', '$type', '$unit', '$measurement', '$prescription', '$discounted')";
        $query_run = mysqli_query($connection, $query);

        if ($query_run) {
            // Insertion successful
            logActivity("Added New Product");
            echo "<script>alert('New product added successfully');</script>";
            echo "<script>window.location.href = 'product.php';</script>";
        } else {
            // Insertion failed
            echo "<script>alert('Failed to add product. Please try again.');</script>";
            echo "<script>window.location.href = 'product.php';</script>";
        }
    } else {
        // Product already exists
        echo "<script>alert('Product already exists. Please choose a different name.');</script>";
        echo "<script>window.location.href = 'product.php';</script>";
    }
}


if (isset($_POST['add_stock_btn'])) {
    // Get data from the form
    $sku = $_POST['sku'];
    $product_name = $_POST['product_stock_name'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $supplier = $_POST['supp_name'];
    $branch = $_POST['branch'];
    $expiry_date = $_POST['expiry_date'];
    $date_added = date('Y-m-d'); // Current date only


    // Get last batch number from the database for the current date
    $current_date = date('Y-m-d');
    $last_batch_number_query = "SELECT MAX(batch_number) AS last_batch_number FROM add_stock_list WHERE DATE(date_added) = '$current_date'";
    $last_batch_number_result = mysqli_query($connection, $last_batch_number_query);
    $last_batch_number_row = mysqli_fetch_assoc($last_batch_number_result);
    $last_batch_number = $last_batch_number_row['last_batch_number'];

    // Determine the new batch number
    if ($last_batch_number) {
        // Extract the count part from the last batch number
        $last_batch_count = (int)substr($last_batch_number, -3);
        if ($last_batch_count >= 999) {
            // If count reaches 999, reset to '001'
            $new_batch_count = 1;
        } else {
            // Increment the count
            $new_batch_count = $last_batch_count + 1;
        }
    } else {
        // No previous batch number for the current date, start from '001'
        $new_batch_count = 1;
    }

    // Format the count part to have leading zeros
    $new_batch_count_padded = str_pad($new_batch_count, 3, '0', STR_PAD_LEFT);

    // Construct the new batch number
    $batch_number = date('ymd') . $new_batch_count_padded;

    // Execute query to add stock
    $add_stock_query = "INSERT INTO add_stock_list (sku, product_stock_name, descript, quantity, price, supp_name, branch, expiry_date, date_added, batch_number)
                        VALUES ('$sku', '$product_name', '$description', '$quantity', '$price', '$supplier', '$branch', '$expiry_date', '$date_added', '$batch_number')";
    $add_stock_result = mysqli_query($connection, $add_stock_query);

    if ($add_stock_result) {
        // Update Stocks Available in product_list table
        $update_stocks_query = "UPDATE product_list 
                                SET stocks_available = stocks_available + '$quantity' 
                                WHERE prod_name = '$product_name'";
        mysqli_query($connection, $update_stocks_query);
        logActivity("Stock Added");
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
        $current_date = date('Y-m-d H:i:s');
        $restore_query = "INSERT INTO add_stock_list (id, sku, product_stock_name, descript, quantity, price, supp_name, branch, batch_number, expiry_date, date_added)
        VALUES ('{$row['id']}', '{$row['sku']}', '$product_name', '{$row['descript']}' , '{$row['quantity']}',  '{$row['price']}', '{$row['supplier']}','{$row['branch']}','{$row['batch_number']}', '{$row['expiry_date']}', '$current_date')";
        mysqli_query($connection, $restore_query);

        // Update the stocks_available in add_stock_list
        $update_stocks_query = "UPDATE add_stock_list SET stocks_available = stocks_available + {$row['quantity']} WHERE product_stock_name = '$product_name'";
        mysqli_query($connection, $update_stocks_query);

        // Delete the data from the archive_list table
        $delete_query = "DELETE FROM archive_list WHERE id = $restore_id";
        mysqli_query($connection, $delete_query);
        header('Location: archive.php');
    }
}


// ####################################################################
// RESTORE BUTTONS








// ####################################################################
// DELETE BUTTONS


if(isset($_POST['delete_sup_btn']))
{
    $id = $_POST['delete_id'];

    $query = "DELETE FROM supplier_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        logActivity("Deleted a Supplier");
        header('Location: add_supplier.php');
    }
}

if(isset($_POST['delete_prod_btn']))
{
    $id = $_POST['delete_id'];

    $query = "DELETE FROM product_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        logActivity("Deleted a Product");

        header('Location: product.php');
    }
}


if(isset($_POST['delete_cat_btn']))
{
    $id = $_POST['delete_id'];

    $query = "DELETE FROM category_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        logActivity("Deleted A Category");
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
        logActivity("Deleted a Discount");

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
        logActivity("Deleted a Product Type");
        header('Location: add_product_type.php');
    }
}

if(isset($_POST['delete_unit_btn']))
{
    $id = $_POST['delete_id'];

    $query = "DELETE FROM unit_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        logActivity("Deleted a Unit Type");

        header('Location: add_unit.php');
    }
}

if(isset($_POST['permanent_delete_btn']))
{
    $id = $_POST['delete_id'];

    $query = "DELETE FROM archive_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
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
        header('Location: expired_products.php');
    }
}





if (isset($_POST['move_to_archive_btn'])) {
    $move_id = $_POST['archive_id'];

    // Insert the selected record into the archive_list table
    $move_query = "INSERT INTO archive_list (sku, product_name, descript, quantity, price, branch, expiry_date) SELECT sku, product_stock_name, descript, quantity, price, branch, expiry_date FROM add_stock_list WHERE id = '$move_id'";
    $move_query_run = mysqli_query($connection, $move_query);

    if ($move_query_run) {
        // If the record was successfully moved to the archive, delete it from the add_stock_list table
        $delete_query = "DELETE FROM add_stock_list WHERE id = '$move_id'";
        echo "Move ID: " . $move_id . "<br>"; // Check the value of $move_id
        $delete_query_run = mysqli_query($connection, $delete_query);

        if ($delete_query_run) {
            // Record moved to archive and deleted from add_stock_list successfully
            header('Location: archive.php'); // Redirect to archive.php after successful operation
            exit();
        } else {
            // Error occurred while deleting from add_stock_list table
            echo "Error deleting record from add_stock_list: " . mysqli_error($connection);
        }
    } else {
        // Error occurred while moving record to archive
        echo "Error moving record to archive_list: " . mysqli_error($connection);
    }
}


/*
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
*/


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
/*if (isset($_POST['move_buffer_stock_btn'])) {
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
*/
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
$cashier_name = $_POST['full_name'];
$branch = $_POST['branch'];
$quantity = $_POST['quantity'];

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
    $insert_query = "INSERT INTO transaction_list (transaction_no, date, time, am_pm, mode_of_payment, total_amount, sub_total, list_of_items, ref_no, cashier_name, branch, quantity) 
                     VALUES ('$transaction_no', '$date', '$time', '$am_pm', '$mode_of_payment', '$total_amount', '$sub_total,', '$listOfItems', '$ref_no', '$cashier_name', '$branch', '$quantity')";

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


/*
if (isset($_POST['charge_btn'])) {
    // Handle charging logic here
   
    $total = $_POST['total'];
    $quantityToDeduct = $_POST['quantity'];
    // Retrieve the list of items from the hidden input field
    $list_of_items = $_POST['list_of_items'];
    
    $updateResult = mysqli_query($connection, $updateQuantityQuery);
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

        if($updateResult) {
            $_SESSION['success'] = "Category Added";
            header('Location: pos.php');
        } else {
            $_SESSION['status'] = "Category NOT Added";
            header('Location: pos.php');
        }
    }
}
*/


if(isset($_POST['logout_btn']))
{
    session_destroy();
    unset($_SESSION['username']);
}











?>
