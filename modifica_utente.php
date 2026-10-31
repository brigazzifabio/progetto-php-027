<html>
    <head>
        <title>Modifica Utente - Progetto PHP</title>

        <script src="js/scripts.js" language="text/javascript"></script>

    </head>
    <body>
        <center>

            <?php
            include("dbconf.php");

            include("menu.php");

            $azione = $_GET["azione"] ?? ''; //null -> ''
            $id_utente = $_GET["id_utente"] ?? '';
            //echo "id_utente: " . $id_utente . "<br>";

            $selectUtente = "select * from utenti where id_utente = " . $id_utente;
            $resultUtente = mysqli_query($conn, $selectUtente);

            while($modificaUtente = mysqli_fetch_array($resultUtente)){
                $username_mod = $modificaUtente["username"];
                $email_mod = $modificaUtente["email"];
                $cell_mod = $modificaUtente["cell"];
                $attivo_mod = $modificaUtente["attivo"];
            }
            ?>
        
            <h2>Modifica Utente</h2>

            <form method="get">
                <input type="hidden" id="azione" name="azione" value="aggiorna" />
                <input type="hidden" id="id_utente" name="id_utente" value="<?php echo $id_utente; ?>" />

                <!-- commento html //-->

                <table>
                    <tr>
                        <td>Username:</td>
                        <td>
                            <input type="text" id="username" name="username" required placeholder="inserisci il tuo username" value="<?php echo $username_mod; ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td>
                            <input type="password" id="password" name="password" placeholder="inserisci la tua password" >&nbsp;
                            <img src="images/eye.png" style="width: 20px; height: 20px; cursor:pointer" alt="vedi password" 
                            title="vedi password" onmouseover="vediPassword();" onmouseout="nascondiPassword();">
                        </td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>
                            <input type="email" id="email" name="email" required placeholder="inserisci la tua email" value="<?php echo $email_mod; ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td>Cellulare:</td>
                        <td>
                            <input type="tel" id="cell" name="cell" required placeholder="inserisci il tuo cellulare" value="<?php echo $cell_mod; ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td>Attivo:</td>
                        <td>
                            <?php
                            if($attivo_mod == 1){
                                ?>
                                <input type="radio" id="attivo" name="attivo" value="1" checked />&nbsp;Si&nbsp;&nbsp;
                                <input type="radio" id="attivo" name="attivo" value="0" />&nbsp;No
                                <?php
                            }
                            else if($attivo_mod == 0){
                                ?>
                                <input type="radio" id="attivo" name="attivo" value="1" />&nbsp;Si&nbsp;&nbsp;
                                <input type="radio" id="attivo" name="attivo" value="0" checked />&nbsp;No
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <input type="submit" value="Aggiorna" style="width: 70px;">
                        </td>
                    </tr>
                </table>

            </form>

            <?php
            if($azione == "aggiorna"){

                $id_utente = $_GET["id_utente"];
                $username = $_GET["username"];
                $password = $_GET["password"];
                $email = $_GET["email"];
                $cell = $_GET["cell"];
                $attivo = $_GET["attivo"];

                if(empty($password)){ //'' "" 0 null <= tutti questi 4 controlli
                    $updateUtente = "UPDATE utenti 
                                        SET username = '$username',
                                            email = '$email',
                                            cell = '$cell',
                                            attivo = $attivo 
                                    WHERE id_utente = " . $id_utente;
                }
                else{
                    $updateUtente = "UPDATE utenti 
                                        SET username = '$username',
                                            password = SHA1('$password'),
                                            email = '$email',
                                            cell = '$cell',
                                            attivo = $attivo 
                                    WHERE id_utente = " . $id_utente;
                }  

                //echo $updateUtente . "<br>";
                mysqli_query($conn, $updateUtente);

                header("Location: http://localhost/progettophp3/utenti.php");
            }
            ?>

        </center>
    </body>
</html>