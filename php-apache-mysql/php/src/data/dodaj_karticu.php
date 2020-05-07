<?php
    session_start();
    require_once "config.php";
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: /account/login.php");
        exit;
    }
    $uid_kartice=$passcode=$zaposlenik="";
    $uid_kartice_err=$zaposlenik_err=$passcode_err="";

    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        if(empty(trim($_POST["uid_kartice"])))
        {
            $passcode_err="Unesite UID kartice";
        }
        else
        {
            $sql="SELECT KarticaID FROM Kartice WHERE uid_kartice = ? ";

            if($stmt=mysqli_prepare($link,$sql))
            {
                mysqli_stmt_bind_param($stmt,"s",$param_uid_kartice);
                $param_uid_kartice=trim($_POST["uid_kartice"]);
                $param_uid_kartice = $link->real_escape_string($param_uid_kartice);
                
                if(mysqli_stmt_execute($stmt))
                {
                    mysqli_stmt_store_result($stmt);

                    if(mysqli_stmt_num_rows($stmt)==1)
                    {
                        $uid_kartice_err= "U sustavu već postoji kartica s tim UID-om";
                    }
                    else
                    {
                        $uid_kartice=trim($_POST["uid_kartice"]);
                        $uid_kartice=$link->real_escape_string($uid_kartice);
                    }
                }
            }
        }
        if(empty(trim($_POST['zaposlenik'])))
        {
            $zaposlenik_err="Odaberite zaposlenika";
        }
        else
        {
            $sql="SELECT Zaposlenici.ZaposlenikID, Kartice.KarticaID FROM Zaposlenici INNER JOIN Kartice ON Zaposlenici.ZaposlenikID=Kartice.ZaposlenikID WHERE Zaposlenici.ZaposlenikID= ?";
            if($stmt=mysqli_prepare($link,$sql))
            {
                mysqli_stmt_bind_param($stmt,"s",$param_zaposlenik);
                $param_zaposlenik=trim($_POST["zaposlenik"]);
                $param_zaposlenik = $link->real_escape_string($param_zaposlenik);
                
                if(mysqli_stmt_execute($stmt))
                {
                    mysqli_stmt_store_result($stmt);

                    if(mysqli_stmt_num_rows($stmt)==1)
                    {
                        $zaposlenik_err= "Zaposlenik već posjeduje karticu";
                    }
                    else
                    {
                        $zaposlenik=trim($_POST['zaposlenik']);
                        $zaposlenik=$link->real_escape_string($zaposlenik);
                    }
                }
            }
            
        }
        if(empty(trim($_POST['passcode'])))
        {
            $passcode_err ="Unesite passcode";
        }
        else if(strlen(trim($_POST['passcode']))!=4)
        {
            $passcode_err="Passcode mora imati 4 znamenke";
        }
        else{
            $passcode_try = trim($_POST['passcode']);
            $passcode_param_error="";
            for($i=0;$i<strlen($passcode_try);$i++)
            {
                if(($passcode_try[$i]>='0' && $passcode_try[$i]<='9')===false)
                {
                    $passcode_param_error='error1';
                }
                
            }
            if($passcode_param_error==='error1')
            {
                $passcode_err="Passcode može sadržavati samo znamenke od 0 do 9";
            }
            else
            {
                $passcode= trim($_POST["passcode"]);
                $passcode= $link->real_escape_string($passcode);
            }
           
        }
        
        

        if(empty($uid_kartice_err) && empty($zaposlenik_err) && empty($passcode_err))
        {
            $sql = "INSERT INTO Kartice(uid_kartice,ZaposlenikID,Zaporka,dopusten) VALUES(?,?,?,true)";

            if($stmt=mysqli_prepare($link,$sql))
            {
                mysqli_stmt_bind_param($stmt,"sss",$param_uid_kartice,$param_zaposlenik,$param_passcode);
                $param_uid_kartice=$uid_kartice;
                
                $param_zaposlenik=$zaposlenik;
                $param_passcode=password_hash($passcode,PASSWORD_DEFAULT);
               

                if(mysqli_stmt_execute($stmt))
                {
                    header("location: /kartice/kartice.php");
                
                }
                else
                {
                    echo "Something went wrong.Please try again later";
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
    <title>Registracija kartice</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Registracija kartice</h2>
        <p>Popunite formu kako bi dodali karticu </p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($uid_kartice_err)) ? 'has-error' : ''; ?>">
                <label>UID_kartice</label>
                <input type="text" name="uid_kartice" class="form-control" value="<?php echo $uid_kartice; ?>">
                <span class="help-block"><?php echo $uid_kartice_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($zaposlenik_err)) ? 'has-error' : ''; ?>">
                <label>Zaposlenik</label>
                <select type="text" class="form-control input-sm" id="select" name="zaposlenik">
                <?php
                    include('data/config.php');
                    
                    $sql = "SELECT * FROM Zaposlenici ORDER BY ZaposlenikID ASC;";
                    $result=$link->query($sql);
                    
                    if($result->num_rows>=0)
                    {
                        while($row=$result->fetch_assoc())
                        {
                            $value = 'value="' .$row['ZaposlenikID'] .'"';
                            echo "<option " .$value . ">"  . $row['ZaposlenikID'] . "-". $row['Ime'] ." ". $row['Prezime'] ." -OIB:". $row['OIB'] ."</option>";
                        }
                    }
                    else
                    {
                        echo "0 results";
                    }
                ?>
                </select>
                <span class="help-block"><?php echo $zaposlenik_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($passcode_err)) ? 'has-error' : ''; ?>">
                <label>Passcode</label>
                <input type="password" name="passcode" class="form-control" value="<?php echo $passcode; ?>">
                <span class="help-block"><?php echo $passcode_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Registriraj karticu">
                <a  class="btn btn-danger" href="../kartice/kartice.php">
                    Nazad
                </a>
            </div>
            
        </form>
    </div>    
</body>
</html>