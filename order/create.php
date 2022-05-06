<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$orderID = $userID = $timeordered = "";

$orderID_err = $userID_err = $timeordered_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $input_orderID = trim($_POST["orderID"]);
    if(empty($input_orderID)){
        $orderID_err = "Please enter First Name";
    } else{
        $orderID = $input_orderID;
    }


    $input_userID = trim($_POST["userID"]);
    if(empty($input_userID)){
        $userID_err = "Please enter Last Name.";
    } elseif(!filter_var($input_userID, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $userID_err = "Please enter a valid Customer Name.";
    } else{
        $userID = $input_userID;
    }


    $input_timeordered = trim($_POST["timeordered"]);
    if(empty($input_timeordered)){
        $timeordered_err = "Please enter timeordered.";
    } else{
        $timeordered = $input_timeordered;
    }
    
    
    // Check input errors before inserting in database
    if(empty($orderID_err) && empty($userID_err) && empty($timeordered_err)){
        // Prepare an insert timeorderedment
        $sql = "INSERT INTO rep (Order_ID, Product_ID, Time_Ordered) VALUES ('$orderID', '$userID', '$timeordered')";
         
        if($stmt = mysqli_prepare($link, $sql)){
            
            // Attempt to execute the prepared timeorderedment
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close timeorderedment
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
                            <label>Order ID</label>
                            <input type="text" name="orderID" class="form-control <?php echo (!empty($orderID_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $orderID; ?>">
                            <span class="invalid-feedback"><?php echo $orderID_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>User ID</label>
                            <input type="text" name="userID" class="form-control <?php echo (!empty($userID_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $userID; ?>">
                            <span class="invalid-feedback"><?php echo $userID_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Time Ordered</label>
                            <input type="text" name="timeordered" class="form-control <?php echo (!empty($timeordered_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $timeordered; ?>">
                            <span class="invalid-feedback"><?php echo $timeordered_err;?></span>
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