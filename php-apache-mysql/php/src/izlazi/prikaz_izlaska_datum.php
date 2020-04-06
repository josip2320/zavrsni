<?php
// Pokretanje sesije
session_start();

 
// Provjerava je li korisnik prijavljen, ako nije redirecta na login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: /account/login.php");
    exit;
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dobrodošli na stranicu evidencije radnog vremena</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/f6ebf49847.js" crossorigin="anonymous"></script>
    <style type="text/css">
        .page-header{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Pozdrav, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Dobrodošli na stranicu evidencije radnog vremena</h1>
    </div>
       
    <br>
    
 
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

        <a class="navbar-brand" href="#">Tehnička škola Ruđera Boškovića</a>


          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav">
            <span class="navbar-toggler-icon"></span>
          </button>


      <div class="collapse navbar-collapse" id="basicExampleNav">

        <!-- Links -->
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="../index.php">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../zaposlenici/zaposlenici.php">Zaposlenici</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../kartice/kartice.php">Kartice</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../ulazi/ulazi.php">Ulazi</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="izlazi.php">Izlazi</a>
          </li>
        
         </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
              <a href="account/reset-password.php" class="btn btn-warning">Reset lozinke</a>
            </li>
          <li class="nav-item">
            <a href="account/logout.php" class="btn btn-danger">Odjavi se</a>
          </li>
        </ul>
        
      </div>
      

    </nav>
    <br>

    <a href="prikaz_izlaska.php" class="btn btn-success btn-lg">
        <span class="fas fa-list"></span>
            Prikaži izlaske
    </a>
    <br>
    <br>
    <a href="prikaz_izlaska_zaposlenik.php" class="btn btn-success btn-lg">
      <span class="fas fa-user"></span>
      Filtriraj izlaske po zaposleniku
    </a>
    <a href="prikaz_izlaska_datum.php" class="btn btn-success btn-lg">
      <span class="fas fa-calendar"></span>
        Filtriraj izlaske po datumu
    </a>
    <br>
    <br>
    <form action="" method="post">
        <div class="form-group">
        <label for="datum1">Izaberi 1.datum </label>
        <input type="date" id="datum1" name="datum1">
        <label for="datum1">Izaberi 2.datum </label>
        <input type="date" id="datum2" name="datum2">
        <input type="submit" name="submit" class="btn btn-primary" value="Filtriraj po datumu">
        </div>
    </form>
    <?php
        date_default_timezone_set('Europe/Zagreb');
        include('../data/config.php');
        if(isset($_POST['datum1']) && isset($_POST['datum2']))
        {
            $datum1=$_POST['datum1'];
            $datum2=$_POST['datum2'];
            $danas = new DateTime();

            if(empty($datum1)||empty($datum2))
            {
                echo "Izaberi oba datuma";
                exit();
            }
            else
            {
                $datum1 = new DateTime($datum1);
                $datum2 = new DateTIme($datum2);
                 if($datum2<$datum1)
                {
                    echo "2.datum mora biti veći";
                }
                else if($datum2>$danas || $datum1>$danas)
                {
                    echo "Datum ne smije biti veći od današnjeg";
                }
                else
                {
                    $datum1 = $datum1->format('Y-m-d');
                    $datum2 = $datum2->format('Y-m-d');
                    
                    $sql  = "SELECT Zaposlenici.Ime,Zaposlenici.Prezime,Zaposlenici.OIB,Kartice.uid_kartice  FROM Zaposlenici  INNER JOIN Kartice ON Zaposlenici.ZaposlenikID = Kartice.ZaposlenikID";
                    if($stmt =mysqli_prepare($link,$sql))
                    {
                        if(mysqli_stmt_execute($stmt))
                        {
                            mysqli_stmt_store_result($stmt);
                            mysqli_stmt_bind_result($stmt,$ime,$prezime,$oib,$uid_kartice);
                            if(mysqli_stmt_num_rows($stmt)>0)
                            {
                                while (mysqli_stmt_fetch($stmt)) {
                                    echo '<table class = "table table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Zaposlenik</th>
                                            <th scope ="col">uid_kartice</th>
                                            <th scope="col">Vrijeme</th>
                                            
                                        </tr>    
                                    </thead>
                                    <tbody>';
                                    $sql = "SELECT * FROM Izlazi WHERE date(Vrijeme) >=? AND date(Vrijeme) <=? ORDER BY ID DESC";
                                    if($stmt=mysqli_prepare($link,$sql))
                                    {
                                        mysqli_stmt_bind_param($stmt,"ss",$param_datum1,$param_datum2);
                                        $param_datum1=$datum1;
                                        $param_datum2=$datum2;

                                        if(mysqli_stmt_execute($stmt))
                                        {
                                            mysqli_stmt_store_result($stmt);
                                            mysqli_stmt_bind_result($stmt,$id,$uid_kartice,$vrijeme,$status);
                                            if(mysqli_stmt_num_rows($stmt)>0)
                                            {
                                                while(mysqli_stmt_fetch($stmt))
                                                {
                                                    $scope = "row";
                                                    echo "<tr>";
                                                    echo "<th '.$scope.'>".$id. "</th>";
                                                    if($status=='unknown')
                                                    {
                                                        echo "<td>nepoznato</td>";
                                                    }
                                                    else
                                                    {
                                                        echo "<td>" . $ime . " " . $prezime . " OIB:" .$oib . "</td>";     
                                                    }
                                                    
                                                    echo "<td>" . $uid_kartice . "</td>";
                                                    echo "<td>" . $vrijeme . "</td>";
                                                   
                                                    echo "</tr>";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    
    ?>    
</body>
</html>