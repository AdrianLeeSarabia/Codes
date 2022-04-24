<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$fname = $lname = $repnum = $street = $city = $state = $zip = $commission = $rate = "";

$fname_err = $lname_err = $repnum_err = $street_err = $city_err = $state_err = $zip_err = $commission_err = $rate_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
   // Validate name
    $input_fname = trim($_POST["fname"]);
    if(empty($input_fname)){
        $fname_err = "Please enter first name.";
    } elseif(!filter_var($input_fname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $fname_err = "Please enter a valid name.";
    } else{
        $fname = $input_fname;
    }
    
    // Validate lastname
    $input_lname = trim($_POST["lname"]);
    if(empty($input_lname)){
        $lname_err = "Please enter lastname.";     
    } else{
        $lname = $input_lname;
    }

    $input_street = trim($_POST["street"]);
    if(empty($input_street)){
        $street_err = "Please enter Street.";
    } else{
        $street = $input_street;
    }

    
    $input_city = trim($_POST["city"]);
    if(empty($input_city)){
        $city_err = "Please enter City.";
    } elseif(!filter_var($input_city, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $city_err = "Please enter a valid City.";
    } else{
        $city = $input_city;
    }



    $input_state = trim($_POST["state"]);
    if(empty($input_street)){
        $street_err = "Please enter State.";
    } else{
        $state = $input_state;
    }

    $input_zip = trim($_POST["zip"]);
    if(empty($input_zip)){
        $zip_err = "Please enter Zip.";
    } else{
        $zip = $input_zip;
    }


    $input_commission = trim($_POST["commission"]);
    if(empty($input_commission)){
        $commission_err = "Please enter Commission.";
    } else{
        $commission = $input_commission;
    }



    $input_rate = trim($_POST["rate"]);
    if(empty($input_rate)){
        $credit_rate = "Please enter Credit Limit.";
    
    } else{
        $rate = $input_rate;
    }

    // Validate salary
    $input_repnum = trim($_POST["repnum"]);
     if(empty($input_repnum)){
        $repnum_err = "Please enter representative number.";     
    } else{
        $repnum = $input_repnum;
    }
    
    
    // Check input errors before inserting in database
    if(empty($Fname_err) && empty($lname_err) && empty($street_err) && empty($city_err) && empty($state_err) &&empty($zip_err) && empty($balance_err) && empty($rate_err) && empty($repnum_err)){
        // Prepare an update statement
        $sql = "UPDATE rep SET FIRST_NAME=?, LAST_NAME=?, REP_NUM=? WHERE REP_NUM=$repnum";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
             
            
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
        $sql = "SELECT * FROM REP WHERE REP_NUM = ?";
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
                    $fname = $row["FIRST_NAME"];
                    $lname = $row["LAST_NAME"];
                    $repnum = $row["REP_NUM"];
                    $street = $row["STREET"];
                    $city = $row["CITY"];
                    $state = $row["STATE"];
                    $zip = $row["ZIP"];
                    $commission = $row["COMMISSION"];
                    $rate = $row["RATE"];
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
                            <label>First Name</label>
                            <input type="text" name="fname" class="form-control <?php echo (!empty($fname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fname; ?>">
                            <span class="invalid-feedback"><?php echo $fname_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <textarea name="lname" class="form-control <?php echo (!empty($lname_err)) ? 'is-invalid' : ''; ?>"><?php echo $lname; ?></textarea>
                            <span class="invalid-feedback"><?php echo $lname_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Representative</label>
                            <input type="text" name="repnum" class="form-control <?php echo (!empty($repnum_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $repnum; ?>">
                            <span class="invalid-feedback"><?php echo $repnum_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Street</label>
                            <input type="text" name="street" class="form-control <?php echo (!empty($street_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $street; ?>">
                            <span class="invalid-feedback"><?php echo $street_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" class="form-control <?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $city; ?>">
                            <span class="invalid-feedback"><?php echo $city_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" name="state" class="form-control <?php echo (!empty($state_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $state; ?>">
                            <span class="invalid-feedback"><?php echo $cNum_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Zip</label>
                            <input type="text" name="zip" class="form-control <?php echo (!empty($zip_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $zip; ?>">
                            <span class="invalid-feedback"><?php echo $zip_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Commission</label>
                            <input type="text" name="commission" class="form-control <?php echo (!empty($commission_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $commission; ?>">
                            <span class="invalid-feedback"><?php echo $commission_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Rate</label>
                            <input type="text" name="rate" class="form-control <?php echo (!empty($rate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $rate; ?>">
                            <span class="invalid-feedback"><?php echo $rate_err;?></span>
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