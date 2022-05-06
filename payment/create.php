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
        // Prepare an insert timement
        $sql = "INSERT INTO rep (Payment_ID, Order_ID, User_ID, Payment_Mode, Time_Received, Total_Amount,) VALUES ('$payID', '$userID', '$paymentMode', '$date', '$time', '$totalAmount', '$orderID')";
         
        if($stmt = mysqli_prepare($link, $sql)){
            
            // Attempt to execute the prepared timement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close timement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add representative record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        
                        
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>