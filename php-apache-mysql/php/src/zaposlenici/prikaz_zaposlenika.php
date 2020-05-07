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
    <script src="https://kit.fontawesome.com/f6ebf49847.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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
              
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="zaposlenici.php">Zaposlenici</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../kartice/kartice.php">Kartice</a>
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
    <a href="../data/dodaj_zaposlenika.php" class="btn btn-primary btn-lg">
      <span class="fas fa-plus" ></span>
      Dodaj zaposlenika
    </a>
    <a href="prikaz_zaposlenika.php" class="btn btn-success btn-lg">
        <span class="fas fa-list" ></span>
        Prikaži zaposlenike
    </a>
    <a href="obrisi_zaposlenika.php" class="btn btn-danger btn-lg">
        <span class="fas fa-user-minus"></span>
        Obriši zaposlenika
    </a>
    <br>
    <br>
    <a href="prikaz_prisutnih_zaposlenika.php" class="btn btn-primary btn-lg">
       Prikaži prisutne zaposlenike
    </a>
    <a href="prikaz_neprisutnih_zaposleniika.php" class="btn btn-danger btn-lg">
     Prikaži zaposlenike koje nisu na poslu
    </a>
    <table class="table table-striped">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Id</th>
          <th scope="col">Ime</th>
          <th scope="col">Prezime</th>
          <th scope="col">OIB</th>
          <th scope ="col">Prisutnost</th>
        </tr>
      </thead>
      <tbody>
      <?php
          include('../data/config.php');
          
          $sql = "SELECT * FROM Zaposlenici ORDER BY ZaposlenikID ASC;";
          $result = $link->query($sql);
         
          if($reuslt->num_rows >=0)
          {
            while($row=$result->fetch_assoc())
            {
              $scope= "row";
              echo"<tr>";
              echo "<th '.$scope.'>" . $row['ZaposlenikID'] . "</th>";
              echo "<td>" . $row['Ime'] . "</td>";
              echo "<td>" . $row['Prezime'] . "</td>";
              echo "<td>" . $row['OIB'] . "</td>";
              if($row['prisutan']=="1")
              {
                  echo '<td>Prisutan</td>';
              }
              else
              {
                echo '<td class="bg-danger">Nije prisutan</td>';
              }
              echo "</tr>";
            }
          }
          else
          {
            echo "0 results";
          }
        ?>
     </tbody>   
    </table>  
</body>
</html>