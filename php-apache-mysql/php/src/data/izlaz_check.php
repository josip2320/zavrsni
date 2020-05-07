<?php

    require_once "config.php";

    $uid_kartice= $_POST['uid_kartice'];
    $uid_kartice=$link->real_escape_string($uid_kartice);  
    $sql="SELECT Zaposlenici.ZaposlenikID,Zaposlenici.prisutan FROM Zaposlenici INNER JOIN Kartice ON Zaposlenici.ZaposlenikID=Kartice.ZaposlenikID WHERE uid_kartice=?";
    if($stmt=mysqli_prepare($link,$sql))
    {
        mysqli_stmt_bind_param($stmt,"s",$param_uid_kartice);
        $param_uid_kartice = $uid_kartice;
        
        if(mysqli_stmt_execute($stmt))
        {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$zaposlenikID,$prisutan);
            if(mysqli_stmt_fetch($stmt))
            {
                
            }
            
            if($prisutan==1)
            {
                echo "1";
                $sql = "INSERT INTO Izlazi(uid_kartice,status) VALUES(?,'correct')";

            if($stmt=mysqli_prepare($link,$sql))
            {
                
                mysqli_stmt_bind_param($stmt,"s",$param_uid_kartice);
                $param_uid_kartice=$uid_kartice;
                
                if(mysqli_stmt_execute($stmt))
                {
                    mysqli_stmt_store_result($stmt);
                    
                }
                mysqli_stmt_close($stmt);   
                $sql = "UPDATE Zaposlenici SET prisutan='0' WHERE ZaposlenikID = ?";
                    if($stmt = mysqli_prepare($link,$sql))
                    {
                        mysqli_stmt_bind_param($stmt,"s",$param_zaposlenikID);
                        $param_zaposlenikID=$zaposlenikID;
                        if(mysqli_stmt_execute($stmt))
                        {
                            mysqli_stmt_close($stmt);
                        }
                    }   
            }
           }
        
        }
    }
   
   
?>