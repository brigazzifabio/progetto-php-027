<html>
    <head>
        <title>Login - Progetto PHP</title>

        <script src="js/scripts.js" language="text/javascript"></script>

    </head>
    <body>
        <center>

            <?php
            include("dbconf.php");

            $azione = $_GET["azione"] ?? ''; //null -> ''
            ?>
        
            <fieldset style="width: 30%;">
                <legend style="text-align: center;"><h2>Login</h2></legend>
                
                <form method="get">
                    <input type="hidden" id="azione" name="azione" value="accedi" />

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
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <input type="submit" value="Accedi" style="width: 70px;">&nbsp;&nbsp;
                                <input type="button" value="Registrati" style="width: 70px;">
                            </td>
                        </tr>
                    </table>

                </form>
            </fieldset>

            <?php
            if($azione == "accedi"){

                $username = $_GET["username"];
                $password = $_GET["password"];
        
                $selectUtente = "SELECT * FROM utenti WHERE username = '$username' and password = SHA1('$password') and attivo = 1 and canc = 0";
                //echo $selectUtente . "<br>";
                $resultUtente = mysqli_query($conn, $selectUtente);
                $totaleUtente = mysqli_num_rows($resultUtente);

                if($totaleUtente == 0){
                    echo "<span style='color:red'><b>Attenzione!<br>Username e/o Password sbagliati o Utente non Attivo.</b></span>";
                }
                else{
                    session_start();
                    $_SESSION["username"] = $username;
                    header("Location: http://localhost/progettophp3/pannello_controllo.php");
                }

                
            }

            if($azione == "logout"){

                $_SESSION["username"] = "";
                session_abort();
            }
            ?>

        </center>
    </body>
</html>