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
  

    <form action="" method="post">
        <div class="form-group">
            <label for="select">Izaberi zaposlenika </label>
            <select class="form-control input-sm" id="select" name="zaposlenik">
            <?php
                    include('../data/config.php');

                    $sql = "SELECT ZaposlenikID,Ime,Prezime,OIB FROM Zaposlenici;";
                    $result=$link->query($sql);

                    if($result->num_rows>=0)
                    {
                        while($row=$result->fetch_assoc())
                         {
                             $value = 'value' . $row['ZaposlenikID'] . '"';
                             echo "<option ". $value .">" . $row['ZaposlenikID'] . '-'  . $row['Ime'] . " " . $row['Prezime'] . " OIB:" . $row['OIB'];

                         }
                    }
                    ?>
        </select>
        </div>
        <input type="submit" name="submit" class="btn btn-primary" value="Filtriraj po zaposelniku">
    </form>
    <?php
        include('../data/config.php');
        if(isset($_POST['submit']))
        {
            $id_zaposlenika=$_POST['zaposlenik'];

            if(empty(trim($id_zaposlenika)))
            {
                echo "Prazna vrijednost";
                exit();
            }
            $sql = "SELECT Zaposlenici.Ime,Zaposlenici.Prezime,Zaposlenici.OIB,Kartice.uid_kartice  FROM Zaposlenici  INNER JOIN Kartice ON Zaposlenici.ZaposlenikID = Kartice.ZaposlenikID WHERE Zaposlenici.ZaposlenikID=?";
            if($stmt=mysqli_prepare($link,$sql))
            {
                
                mysqli_stmt_bind_param($stmt,"s",$param_id_zaposlenika);
                $param_id_zaposlenika = $id_zaposlenika;
                $param_id_zaposlenika=$link->real_escape_string($param_id_zaposlenika);
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
                            $stmt = "SELECT * FROM Izlazi WHERE uid_kartice = ? ORDER BY ID DESC";
                            if($stmt=mysqli_prepare($link,$stmt))
                            {
                                mysqli_stmt_bind_param($stmt,"s",$param_uid_kartice);
                                $param_uid_kartice=$uid_kartice;

                                if(mysqli_stmt_execute($stmt))
                                {
                                    mysqli_stmt_store_result($stmt);
                                    mysqli_stmt_bind_result($stmt,$id,$uid_kartice,$vrijeme,$status);
                                    if(mysqli_stmt_num_rows($stmt)>0)
                                    {
                                        while(mysqli_stmt_fetch($stmt))
                                        {
                                            $scope = "row";
                                            echo"<tr>";
                                            echo "<th '.$scope.'>".$id. "</th>";
                                            echo "<td>" . $ime . " " . $prezime . " OIB:" .$oib . "</td>";
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
        ?>
  </body>
</html>