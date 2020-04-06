<?php
    require_once "config.php";
    
    
    $temp = $_POST["uid_kartice"];
    $temp = $link->real_escape_string($temp);
    $sql= "SELECT KarticaID ,dopusten FROM Kartice WHERE uid_kartice LIKE ?";
    if($stmt = mysqli_prepare($link,$sql))
    {
        mysqli_stmt_bind_param($stmt,"s",$param_uid);
        $param_uid=$temp;
        if(mysqli_stmt_execute($stmt))
        {
            mysqli_stmt_store_result($stmt);
            
            if(mysqli_stmt_num_rows($stmt)==1)
            {
                mysqli_stmt_bind_result($stmt,$uid_kartice,$dopusten);
                if(mysqli_stmt_fetch($stmt))
                {
                    if($dopusten=='1')
                    {
                        echo "1";
                    }
                    else
                    {
                        echo "2";
                    }
                }
                
            }
            else 
            {
                echo "0";
            }
        }
        mysqli_stmt_close($stmt);
    }
    


    ?>