<?php
    
    session_start();
    require_once "config.php";
    // Provjerava je li korisnik prijavljen, ako nije redirecta na login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: /account/login.php");
    exit;
    }
    $ime=$prezime=$oib="";
    $ime_err=$prezime_err=$oib_err="";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {

        if(empty(trim($_POST["ime"])))
        {
            $ime_err="Unesite ime";
        }
        else
        {
            $ime=trim($_POST["ime"]);
            $ime=$link->real_escape_string($ime);
        }
    
        if(empty(trim($_POST["prezime"])))
        {
            $prezime_err="Unesite prezime";
        }
        else
        {
            $prezime=trim($_POST["prezime"]);
            $prezime=$link->real_escape_string($prezime);
        }
        
        if(empty(trim($_POST["oib"])))
        {
            $oib_err="Unesite OIB";
        }
        else
        {
            $sql="SELECT ZaposlenikID FROM Zaposlenici WHERE OIB = ?";
            
            if($stmt= mysqli_prepare($link,$sql))
            {
                mysqli_stmt_bind_param($stmt,"s",$param_oib);
                $param_oib = trim($_POST["oib"]);
                $param_oib = $link->real_escape_string($param_oib);

                if(mysqli_stmt_execute($stmt))
                {
                    mysqli_stmt_store_result($stmt);

                    if(mysqli_stmt_num_rows($stmt) == 1)
                    {
                        $oib_err = "Vec postoji zaposlenik s tim OIB-om";
                    }
                    else{
                        $oib= trim($_POST["oib"]);
                    }
                }
                else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }   

        if(empty($ime_err) && empty($prezime_err) && empty($oib_err))
        {
            $sql = "INSERT INTO Zaposlenici(Ime,Prezime,OIB) VALUES (?,?,?)";

            if($stmt=mysqli_prepare($link,$sql))
            {
                mysqli_stmt_bind_param($stmt,"sss",$param_ime,$param_prezime,$param_oib);

                $param_ime = $ime;
                $param_prezime = $prezime;
                $param_oib = $oib;

                if(mysqli_stmt_execute($stmt))
                {
                    
                    header("location: /zaposlenici/zaposlenici.php");
                }
                else
                {
                    echo "Something went wrong. Please try again later.";
                }

                mysqli_stmt_close($stmt);
            }
        }
        
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
        <h2>Dodavanje zaposlenika</h2>
        <p>Popunite formu kako bi dodali zaposlenika </p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Ime</label>
                <input type="text" name="ime" class="form-control" value="<?php echo $ime; ?>">
                <span class="help-block"><?php echo $ime_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($prezime_err)) ? 'has-error' : ''; ?>">
                <label>Prezime</label>
                <input type="text" name="prezime" class="form-control" value="<?php echo $prezime; ?>">
                <span class="help-block"><?php echo $prezime_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($oib_err)) ? 'has-error' : ''; ?>">
                <label>OIB</label>
                <input type="text" name="oib" class="form-control" value="<?php echo $oib; ?>">
                <span class="help-block"><?php echo $oib_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Registriraj zaposlenika">
                <a  class="btn btn-danger" href="../zaposlenici/zaposlenici.php">
                    Nazad
                </a>
            </div>
            
        </form>
    </div>    
</body>
</html>