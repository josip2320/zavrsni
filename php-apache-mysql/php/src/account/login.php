<?php
// Pokrece sesiju
session_start();

// Provjerava je li korisnik prijavljen, ako je redirecta ga na glavnu stranicu
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: ../index.php");
    exit;
}

//  config file

require_once "../startbase.php";
require_once "config.php";
$username = $password = "";
$username_err = $password_err = "";
 
// Procesiranje form data
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Provjerava je li polje username prazno
    if(empty(trim($_POST["username"]))){
        $username_err = "Unesite username.";
    } else{
        $username = trim($_POST["username"]);
        $username = $link->real_escape_string($username);
    }
    
    // Provjerava je li polje password prazno 
    if(empty(trim($_POST["password"]))){
        $password_err = "Unesite lozinku.";
    } else{
        $password = trim($_POST["password"]);
        
    }
    
    // Provjera informacija 
    if(empty($username_err) && empty($password_err)){
        
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            //Binda varijable s pripremljenim sql statemntom 
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Postvalja parametre
            $param_username = $username;
            
            
            // Pokusaj executanja statementa
            if(mysqli_stmt_execute($stmt)){
                // Sprema rezultate
                mysqli_stmt_store_result($stmt);
                
                //Provjerava postoji li username, ako postoji provjerava lozinku 
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Binda rezultate s varijablama 
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Ako je lozinka tocna pokrece sesiju
                            session_start();
                            
                            // Spremanje podataka u sesiju 
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect na glavnu stanicu
                            header("location: ../index.php");
                        } else{
                            // Error za pogresnu lozinku
                            $password_err = "Lozinka je netočna.";
                        }
                    }
                } else{
                    // Error ako korisnik ne postoji 
                    $username_err = "Ne postoji račun s tim imenom.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

        
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
    <title>Prijava</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Prijava</h2>
        <p>Molimo unesite vaše podatke</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Korisničko ime</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Lozinka</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Prijava">
            </div>
            <p>Nemate račun? <a href="register.php">Registracija </a>.</p>
        </form>
    </div>    
</body>
</html>