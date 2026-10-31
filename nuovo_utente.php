<html>
    <head>
        <title>Nuovo Utente - Progetto PHP</title>

        <script src="js/scripts.js" language="text/javascript"></script>

    </head>
    <body>
        <center>

            <?php
            include("dbconf.php");

            include("menu.php");

            $azione = $_GET["azione"] ?? ''; //null -> ''
            ?>
        
            <h2>Inserisci Nuovo Utente</h2>

            <form method="get">
                <input type="hidden" id="azione" name="azione" value="salva" />

                <table>
                    <tr>
                        <td>Username:</td>
                        <td>
                            <input type="text" id="username" name="username" required placeholder="inserisci il tuo username" >
                        </td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td>
                            <input type="password" id="password" name="password" required placeholder="inserisci la tua password" >&nbsp;
                            <img src="images/eye.png" style="width: 20px; height: 20px; cursor:pointer" alt="vedi password" 
                            title="vedi password" onmouseover="vediPassword();" onmouseout="nascondiPassword();">
                        </td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>
                            <input type="email" id="email" name="email" required placeholder="inserisci la tua email" >
                        </td>
                    </tr>
                    <tr>
                        <td>Cellulare:</td>
                        <td>
                            <input type="tel" id="cell" name="cell" required placeholder="inserisci il tuo cellulare" >
                        </td>
                    </tr>
                    <tr>
                        <td>Attivo:</td>
                        <td>
                            <input type="radio" id="attivo" name="attivo" value="1" checked />&nbsp;Si&nbsp;&nbsp;
                            <input type="radio" id="attivo" name="attivo" value="0" />&nbsp;No
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <input type="submit" value="Salva" style="width: 70px;">
                        </td>
                    </tr>
                </table>

            </form>

            <?php
            if($azione == "salva"){

                $username = $_GET["username"];
                $password = $_GET["password"];
                $email = $_GET["email"];
                $cell = $_GET["cell"];
                $attivo = $_GET["attivo"];

                //DA FARE 2026-07-17 - inserimento a database dell utente nella tabella utenti
                $insertUtente = "INSERT INTO utenti (username, password, email, cell, attivo) 
                                             VALUES ('$username',SHA1('$password'),'$email','$cell',$attivo)";
                //echo $insertUtente . "<br>";
                mysqli_query($conn, $insertUtente);

                header("Location: http://localhost/progettophp3/utenti.php");
            }
            ?>

        </center>
    </body>
</html>