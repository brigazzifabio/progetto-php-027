<html>
    <head>
        <title>Utenti Cestino - Progetto PHP</title>

        <script src="js/scripts.js" language="text/javascript"></script>

    </head>
    <body>
        <center>

        <?php
        include("dbconf.php");

        include("menu.php");

        $azione = $_GET["azione"] ?? '';

        if($azione == "recupera"){

            $id_utente = $_GET["id_utente"];

            $delete = "UPDATE utenti SET canc = 0 WHERE id_utente = " . $id_utente;
            //echo $delete . "<br>";
            mysqli_query($conn, $delete);

        }
        ?> 

        <?php
            //leggiamo tutti gli utenti e poi li mettiamo a video
            $select = "SELECT * FROM `utenti` where canc = 1";
            $result = mysqli_query($conn, $select);
            $totale = mysqli_num_rows($result); 

            ?>
            Elenco Utenti Cancellati - Totale Num. <?php echo $totale; ?>
            <br><br>
            <?php

            $msgStd = "[messaggio standard]";

            //cicli - wille e do.. while
            //$array_utenti = mysqli_fetch_array($result);
            echo "<table border='1'>";
            echo "<tr style='background-color:lightpink'>";
            echo "<th>ID Utente</th>";
            echo "<th>Username</th>";
            //echo "<th>Password</th>";
            echo "<th>Email</th>";
            echo "<th>Cell</th>";
            echo "<th>WhatsApp</th>";
            echo "<th>Attivo</th>";
            echo "<th>Azioni</th>";
            echo "</tr>";
            While($array_utenti = mysqli_fetch_array($result)){
                
                $attivo = $array_utenti["attivo"];
                $lblAttivo = "";
                if($attivo == 0) $lblAttivo = "<span style='color:red'>no</span>";
                if($attivo == 1) $lblAttivo = "<span style='color:darkgreen'>si</span>";

                echo "<tr>";
                echo "<td style='text-align:center'>" . $array_utenti["id_utente"] . "</td>";
                echo "<td>" . $array_utenti["username"] . "</td>";
                //echo "<td>" . $array_utenti["password"] . "</td>";
                echo "<td><a target='_blank' href='mailto:" . $array_utenti["email"] . "'>" . $array_utenti["email"] . "</a></td>";
                //echo $array_utenti["email"] . "<br>";
                echo "<td><a href='tel:".$array_utenti["cell"]."'>" . $array_utenti["cell"] . "</a></td>";
                echo "<td><a href='https://wa.me/".$array_utenti["cell"]."?text=Buongiorno ".$array_utenti["username"].",".$msgStd."'>" . $array_utenti["cell"] . "</a></td>";
                echo "<td style='text-align:center'>" . $lblAttivo . "</td>";
                echo "<td style='text-align:center'>";
                //echo "<img src='images/edit.png' width='20px' height='20px' />&nbsp;";
                echo "<img src='images/restore2.png' width='20px' height='20px' title='recupera utente' alt='recupera utente' style='cursor:pointer' onclick='recuperaUtente(".$array_utenti["id_utente"].");' />";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
            

        ?>

        </center>
    </body>
</html>