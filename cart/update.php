<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$orderID = $productID = $quantity = $addonsID = $subtotal = "";

$orderID_err = $productID_err = $quantity_err = $addonsID_err = $subtotal_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $input_orderID = trim($_POST["orderID"]);
    if(empty($input_orderID)){
        $orderID_err = "Please enter First Name";
    } else{
        $orderID = $input_orderID;
    }


    $input_userID = trim($_POST["productID"]);
    if(empty($input_userID)){
        $userID_err = "Please enter Last Name.";
    } elseif(!filter_var($input_userID, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $userID_err = "Please enter a valid Customer Name.";
    } else{
        $productID = $input_userID;
    }


    $input_addonsID = trim($_POST["addonsID"]);
    if(empty($input_addonsID)){
        $addonsID_err = "Please enter addonsID.";
    } else{
        $addonsID = $input_addonsID;
    }


    $input_quantity = trim($_POST["quantity"]);
    if(empty($input_quantity)){
        $quantity_err = "Please enter quantity.";
    } else{
        $quantity = $input_quantity;
    }

    $input_subtotal = trim($_POST["subtotal"]);
    if(empty($input_subtotal)){
        $subtotal_err = "Please enter subtotal.";
    } else{
        $subtotal = $input_subtotal;
    }
    
    
    
    // Check input errors before inserting in database
    if(empty($orderID_err) && empty($productID_err) && empty($addonsID_err) && empty($subtotal_err) && empty($quantity_err)){
        // Prepare an update statement
        $sql = "UPDATE payment SET Order_ID=?, User_ID=?, Payment_Mode=?, Time_Received=?, Total_Amount=? WHERE Payment_ID=$payID";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
             mysqli_stmt_bind_param($stmt, "sssssssss", $param_payID, $param_orderID, $param_quantity, $param_addonsID, $param_subtotal);
             echo "update hello";
            // Set parameters
            $param_payID = $payID;
            $param_orderID = $orderID;
            $param_quantity = $quantity;
            $param_addonsID = $addonsID;
            $param_subtotal = $subtotal;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM cart WHERE Order_ID = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $orderID = $row["Order_ID"];
                    $productID = $row["Product_ID"];
                    $quantity = $row["Quantity"];
                    $addonsID = $row["Addons_ID"];
                    $subtotal = $row["Subtotal"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                            <label>Order ID</label>
                            <input type="text" name="orderID" class="form-control <?php echo (!empty($orderID_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $orderID; ?>">
                            <span class="invalid-feedback"><?php echo $orderID_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>User ID</label>
                            <input type="text" name="productID" class="form-control <?php echo (!empty($productID_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $productID; ?>">
                            <span class="invalid-feedback"><?php echo $productID_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="text" name="quantity" class="form-control <?php echo (!empty($quantity_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $quantity; ?>">
                            <span class="invalid-feedback"><?php echo $quantity_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Addons</label>
                            <input type="text" name="addonsID" class="form-control <?php echo (!empty($addonsID_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $addonsID; ?>">
                            <span class="invalid-feedback"><?php echo $addonsID_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Subtotal</label>
                            <input type="text" name="date" class="form-control <?php echo (!empty($subtotal_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $subtotal; ?>">
                            <span class="invalid-feedback"><?php echo $subtotal_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>