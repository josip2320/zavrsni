<?php
// Pokretanje sesije
session_start();
 
//Provjera je li korisnik prijavljen, ako nije redirecta ga na login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 

require_once "config.php";
 

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Form data
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Provjerava je li upisana nova lozinka
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Unesite novu lozinku.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Lozinka mora imati najmanje 6 znakova.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Provjerava je li upisana potvrdna lozinka
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Potvrdite lozinku.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Lozinke se ne podudaraju.";
        }
    }
        
    
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Binda varijable za statement
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Postavlja parametre 
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);// Hash password
            $param_id = $_SESSION["id"];
            
            
            if(mysqli_stmt_execute($stmt)){
                
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ponovno postavljanje lozinke</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Ponovno postavljanje lozinke</h2>
        <p>Ispunite podatke</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>Nova lozinka</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Potvrdite lozinku</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Ponovno postavi lozinku">
                <a class="btn btn-link" href="../index.php">Prekini</a>
            </div>
        </form>
    </div>    
</body>
</html>