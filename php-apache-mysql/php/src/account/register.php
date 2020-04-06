<?php

require_once "config.php";
require_once "../startbase.php";
// Definira potrebne varijable
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Form data
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Proverava je li polje usernme prazno 
    if(empty(trim($_POST["username"]))){
        $username_err = "Unesite korisničko ime.";
    } else{
        // select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Binda varijable za pripremljeni statement 
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Postavlja parametar username 
            $param_username = trim($_POST["username"]);
            $param_username = $link->real_escape_string($param_username);
            
            if(mysqli_stmt_execute($stmt)){
                //spremanje rezultata
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Zauzeto korisničko ime.";
                } else{
                    $username = trim($_POST["username"]);
                    $username = $link->real_escape_string($username);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            
            mysqli_stmt_close($stmt);
        }
    }
    
    // Provjera je li unesena lozinka 
    if(empty(trim($_POST["password"]))){
        $password_err = "Unesite lozinku.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Lozinka mora imati najmanje 6 znakova.";
    } else{
        $password = trim($_POST["password"]);
    }
    

    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Potvrdite lozinku.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Lozinke se ne podudaraju";
        }
    }
    
    
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Binda varijable za statement
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Postvalja parametre
            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Stvara password hash
            
            
            if(mysqli_stmt_execute($stmt)){
                // Redirect na login page 
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Registracija</h2>
        <p>Popunite formu kako bi kreirali račun.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Korisničko ime</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Lozinka</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Potvrdite lozinku</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Registriraj se">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Već imate račun? <a href="login.php">Prijava</a>.</p>
        </form>
    </div>    
</body>
</html>