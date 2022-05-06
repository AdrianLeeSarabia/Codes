<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$payID = $userID = $orderID = $paymentMode = $date = $time = $totalAmount = "";

$payID_err = $userID_err = $orderID_err = $paymentMode_err = $date_err = $time_err = $totalAmount_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $input_payID = trim($_POST["payID"]);
    if(empty($input_payID)){
        $payID_err = "Please enter First Name";
    } else{
        $payID = $input_payID;
    }


    $input_userID = trim($_POST["userID"]);
    if(empty($input_userID)){
        $userID_err = "Please enter Last Name.";
    } elseif(!filter_var($input_userID, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $userID_err = "Please enter a valid Customer Name.";
    } else{
        $userID = $input_userID;
    }


    $input_paymentMode = trim($_POST["paymentMode"]);
    if(empty($input_paymentMode)){
        $paymentMode_err = "Please enter paymentMode.";
    } else{
        $paymentMode = $input_paymentMode;
    }

    
    $input_date = trim($_POST["date"]);
    if(empty($input_date)){
        $date_err = "Please enter date.";
    } elseif(!filter_var($input_date, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $date_err = "Please enter a valid date.";
    } else{
        $date = $input_date;
    }



    $input_time = trim($_POST["time"]);
    if(empty($input_time)){
        $time_err = "Please enter time.";
    } else{
        $time = $input_time;
    }

    $input_totalAmount = trim($_POST["totalAmount"]);
    if(empty($input_totalAmount)){
        $totalAmount_err = "Please enter totalAmount.";
    } else{
        $totalAmount = $input_totalAmount;
    }

    $input_orderID = trim($_POST["orderID"]);
     if(empty($input_orderID)){
        $orderID_err = "Please enter representative number.";     
    } else{
        $orderID = $input_orderID;
    }
    
    
    
    // Check input errors before inserting in database
    if(empty($payID_err) && empty($userID_err) && empty($paymentMode_err) && empty($date_err) && empty($time_err) &&empty($totalAmount_err) && empty($balance_err) && empty($rate_err) && empty($orderID_err)){
        // Prepare an update statement
        $sql = "UPDATE payment SET Order_ID=?, User_ID=?, Payment_Mode=?, Time_Received=?, Total_Amount=? WHERE Payment_ID=$payID";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
             mysqli_stmt_bind_param($stmt, "sssssssss", $param_payID, $param_orderID, $param_rep_num, $param_street, $param_city, $param_state, $param_zip, $param_commission, $param_rate);
             echo "update hello";
            // Set parameters
            $param_payID = $payID;
            $param_orderID = $orderID;
            $param_rep_num = $repnum;
            $param_street = $street;
            $param_city = $city;
            $param_state = $state;
            $param_zip = $zip;
            $param_commission = $commission;
            $param_rate = $rate;

             
            
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
        $sql = "SELECT * FROM payment WHERE Payment_ID = ?";
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
                    $payID = $row["Payment_ID"];
                    $orderID = $row["Order_ID"];
                    $userID = $row["User_ID"];
                    $paymentMode = $row["Payment_Mode"];
                    $timeReceived = $row["Time_Received"];
                    $totalAmount = $row["Total_Amount"];
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
                            <label>Pay ID</label>
                            <input type="text" name="payID" class="form-control <?php echo (!empty($payID_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $payID; ?>">
                            <span class="invalid-feedback"><?php echo $payID_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>User ID</label>
                            <input type="text" name="userID" class="form-control <?php echo (!empty($userID_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $userID; ?>">
                            <span class="invalid-feedback"><?php echo $userID_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Order ID</label>
                            <input type="text" name="orderID" class="form-control <?php echo (!empty($orderID_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $orderID; ?>">
                            <span class="invalid-feedback"><?php echo $orderID_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Payment Mode</label>
                            <input type="text" name="paymentMode" class="form-control <?php echo (!empty($paymentMode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $paymentMode; ?>">
                            <span class="invalid-feedback"><?php echo $paymentMode_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Time Received</label>
                            <input type="text" name="time" class="form-control <?php echo (!empty($time_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $time; ?>">
                            <span class="invalid-feedback"><?php echo $time_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="text" name="totalAmount" class="form-control <?php echo (!empty($totalAmount_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $totalAmount; ?>">
                            <span class="invalid-feedback"><?php echo $totalAmount_err;?></span>
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