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
          <li class="nav-item active">
            <a class="nav-link" href="kartice.php">Kartice</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../ulazi/ulazi.php">Ulazi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../izlazi/izlazi.php">Izlazi</a>
          </li>
         </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
              <a href="../account/reset-password.php" class="btn btn-warning">Reset lozinke</a>
            </li>
          <li class="nav-item">
            <a href="../account/logout.php" class="btn btn-danger">Odjavi se</a>
          </li>
        </ul>
        
      </div>
      

    </nav>
    <br>
    <a href="../data/dodaj_karticu.php" class="btn btn-primary btn-lg">
      <span class="fas fa-plus" ></span>
      Dodaj karticu
    </a>
    <a href="prikaz_kartica.php" class="btn btn-success btn-lg">
        <span class="fas fa-list" ></span>
        Prikaži kartice
    </a>
    <a href="prikaz_blokiranih_kartica.php" class="btn btn-danger btn-lg">
        <span class="fas fa-exclamation"> </span>
        Prikaži blokirane kartice
    </a>
    <a href ="obrisi_karticu.php" class="btn btn-danger btn-lg">
        <span class="fas fa-trash-alt"> </span>
            Obriši karticu
    </a>
    <a href="odblokiraj_karticu.php" class="btn btn-warning btn-lg">
      <span class="fas fa-lock-open"></span>
        Odblokiraj karticu 
    </a>
    <a href="blokiraj_karticu.php" class ="btn btn-danger btn-lg">
      <span class="fas fa-lock"></span>
        Blokiraj karticu
    </a>
    <form action="" method="post">
        <div class="form-group">
            <label for="select">Izaberi karticu </label>
            <select class="form-control input-sm" id="select" name="kartica">
                <?php
                    include('../data/config.php');

                    $sql = "SELECT Kartice.KarticaID , Kartice.uid_kartice, Zaposlenici.Ime,Zaposlenici.Prezime,Zaposlenici.OIB FROM Kartice INNER JOIN Zaposlenici ON Zaposlenici.ZaposlenikID=Kartice.ZaposlenikID;";
                    $result=$link->query($sql);

                    if($result->num_rows>=0)
                    {
                        while($row=$result->fetch_assoc())
                         {
                             $value = 'value' . $row['KarticaID'] . '"';
                             echo "<option ". $value .">" . $row['KarticaID'] . '-' .$row['uid_kartice'] . " " . $row['Ime'] . " " . $row['Prezime'] . " OIB:" . $row['OIB'];

                         }
                    }
                    ?>
        </select>
        </div>
        <input type="submit" name="submit" class="btn btn-danger" value="Obriši karticu">
    </form>
    <?php
        include('../data/config.php');

        if(isset($_POST['submit']))
        {
            $selected_val = $_POST['kartica'];
            if(empty(trim($selected_val)))
            {
                echo "Prazna vrijednost";
                exit();
            }

            $stmt = "DELETE FROM Kartice WHERE KarticaID = ?";
            if($stmt = mysqli_prepare($link,$stmt))
            {
                mysqli_stmt_bind_param($stmt, "s" ,$param_id);
                    $param_id=$selected_val;
                    $param_id= $link->real_escape_string($param_id);
                    
                    if(mysqli_stmt_execute($stmt))
                    {
                        echo "Kartica uspjesno izbrisana";
                    }
                    else{
                        echo "Something went wrong. Please try again later.";
                    }
                    mysqli_stmt_close($stmt);
            }
        }
        ?>
</body>
</html>