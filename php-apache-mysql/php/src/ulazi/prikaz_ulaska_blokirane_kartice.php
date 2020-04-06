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
          <li class="nav-item active">
            <a class="nav-link" href="ulazi.php">Ulazi</a>
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

    <a href="prikaz_ulaska.php" class="btn btn-success btn-lg">
        <span class="fas fa-list" ></span>
        Prikaži ulaske
    </a>
    <a href="prikaz_ulaska_netocne_lozinke.php" class="btn btn-danger btn-lg">
      <span class="fas fa-key"></span>
      Prikaži pokušaje ulaska s netočnom lozinkom 
    </a>
    <a href="prikaz_ulaska_blokirane_kartice.php" class="btn btn-danger btn-lg">
      <span class="fas fa-exclamation"></span>
      Prikaži pokušaje ulaska s blokiranim karticama
    </a>
    <a href="prikaz_ulaksa_nepoznate_kartice.php" class="btn btn-danger btn-lg">
      <span class="fas fa-question"></span>
      Prikaži pokušaje ulaska s nepoznatim karticama
    </a>
    <br>
    <br>
    <a href="prikaz_ulaska_zaposlenik.php" class="btn btn-success btn-lg">
      <span class="fas fa-user"></span>
      Filtriraj ulaske po zaposleniku
    </a>
    <a href="prikaz_ulaska_datum.php" class="btn btn-success btn-lg">
      <span class="fas fa-calendar"></span>
        Filtriraj ulaske po datumu
    </a>
    <table class = "table table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Zaposlenik</th>
                <th scope ="col">uid_kartice</th>
                <th scope="col">Vrijeme</th>
                <th scope="col">Status</th>
            </tr>    
        </thead>
        <tbody>
            <?php
                include('../data/config.php');

                $sql = "SELECT * FROM Ulazi WHERE status='block' ORDER BY ID  DESC;";
                $result=$link->query($sql);
                if($result->num_rows>0)
                {
                    while($row=$result->fetch_assoc())
                    {
                        $scope = "row";
                        echo"<tr>";
                        echo "<th '.$scope.'>".$row['ID']. "</th>";

                        $sql = "SELECT Zaposlenici.Ime,Zaposlenici.Prezime,Zaposlenici.OIB,Kartice.uid_kartice FROM Zaposlenici  INNER JOIN Kartice ON Zaposlenici.ZaposlenikID = Kartice.ZaposlenikID;";
                        
                        $result2=$link->query($sql);
                        if($result2->num_rows>0)
                        {
                            while($row2=$result2->fetch_assoc())
                            {
                                if($row['uid_kartice']==$row2['uid_kartice'])
                                {
                                    echo "<td>" . $row2['Ime'] . " " . $row2['Prezime'] . " OIB:" . $row2['OIB'] ."</td>";
                                }
                                else
                                {
                                    echo "<td>nepoznato</td>";
                                }
                            }
                        }
                        else
                        {
                          echo "<td>nepoznato</td>";
                        }
                        echo "<td>" . $row['uid_kartice'] . "</td>";
                        echo "<td>" . $row['Vrijeme'] . "</td>";
                        echo "<td>Blokirana kartica</td>";
                        echo "</tr>";
                    }
                }
            ?>
        </tbody>
    </table>      
</body>
</html>
