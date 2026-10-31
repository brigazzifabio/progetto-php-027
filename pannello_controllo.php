<?php
session_start();

if(!isset($_SESSION["username"])){ // == != => se non è settata la variabile di sessione username o scaduta.
    header("Location: http://localhost/progettophp3/login.php?azione=logout");
}
?>
<html>
    <head>
        <title>Pannello di Controllo - Progetto PHP</title>

        <script src="js/scripts.js"></script>

        <script>
        function esci(){
            if(confirm("Sei sicuro di voler uscire ?")){
                window.open("login.php?azione=logout","_top");
            }
        }
        </script>

    </head>
    <body>
        <div style="text-align: center;">
            <h2>Pannello di Controllo</h2>

            <?php
            include("dbconf.php");

            echo "Benvenuto/a " . ucwords($_SESSION["username"]) . "<br><br>"; 

            $selectVociMenu = "select * from voci_menu where canc = 0 and pagina_voce = 'pannello_controllo.php'";
            $resultVociMenu = mysqli_query($conn, $selectVociMenu);

            while($arrayVociMenu = mysqli_fetch_array($resultVociMenu)){
                $nome_voce = $arrayVociMenu["nome_voce"];
                $url_voce = $arrayVociMenu["url_voce"];
                $voce_attiva = $arrayVociMenu["attivo"];

                $nonAttivo = "";
                if($voce_attiva == 0) $nonAttivo = " disabled ";

                ?>
                <a href="<?php echo $url_voce; ?>"><button <?php echo $nonAttivo; ?> style="width: 120px;"><?php echo $nome_voce; ?></button></a><br><br>
                <?php
            }
            ?>
            
            <button style="width: 120px;" onclick="esci();">Esci</button>
            
        </div>
    </body>
</html>