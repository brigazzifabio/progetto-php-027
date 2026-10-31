<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';
?>

<html>
    <head>
        <title>Contatti - Progetto PHP</title>

        <script src="js/scripts.js"></script>

    </head>

    <body>
        <center>

        <?php
        include("dbconf.php");

        include("menu.php");

        $azione = $_POST["azione"] ?? '';

        ?>

        <h2>Modulo Contatti</h2>

        <!-- 
        1. form
        2. richieste nome, cognome, email, tel/cell, casella di discesa con motivo del contatto (elenco), oggetto, messaggio, allegato, privacy e pulsante invio
        //-->
        
            <fieldset style="width: 50%;">
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="MAX_FILE_SIZE" value="3145728"> <!-- 3MB -->

                    <input type="hidden" id="azione" name="azione" value="invia">

                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 35%; text-align: right;">Nome *:</td>
                            <td style="width: 65%;">
                                <input type="text" id="nome" name="nome" required placeholder="inserisci il tuo nome" style="width: 250px;" />
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">Cognome *:</td>
                            <td>
                                <input type="text" id="cognome" name="cognome" required placeholder="inserisci il tuo cognome" style="width: 250px;" />
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">Email *:</td>
                            <td>
                                <input type="email" id="email" name="email" required placeholder="inserisci la tua email" style="width: 250px;" />
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">Tel/Cell *:</td>
                            <td>
                                <input type="tel" id="tel_cell" name="tel_cell" required placeholder="inserisci il tuo tel o cell" style="width: 200px;" />
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">Motivo Richiesta *:</td>
                            <td>
                                <select id="motivo" name="motivo" required style="width: 200px;">
                                    <option selected>Scegli una voce</option>
                                    <option value="A">Alpha</option>
                                    <option value="B">Beta</option>
                                    <option value="G">Gamma</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;" valign="top">Interessi *:</td>
                            <td>
                                <select id="interessi" name="interessi" required style="width: 200px;" multiple>
                                    <option value="I">Informatica</option>
                                    <option value="L">Libri</option>
                                    <option value="AS">Astronomia</option>
                                    <option value="C">Cucina</option>
                                    <option value="S">Sport</option>
                                    <option value="AL">Altro</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right; font-weight: bold">Oggetto:</td>
                            <td>
                                <input type="text" id="oggetto" name="oggetto" required placeholder="inserisci oggetto" style="width: 250px;" />
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right; font-weight: bold" valign="top">Messaggio *:</td>
                            <td>
                                <textarea id="messaggio" name="messaggio" cols="34" rows="5" required></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">Allegato:</td>
                            <td>
                                <input type="file" id="allegato" name="allegato" />&nbsp;(Max 3MB)
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">Privacy *:</td>
                            <td>
                                <input type="checkbox" id="privacy" name="privacy" required />&nbsp;Accetta le nostre condizioni
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <input type="submit" id="invia" value="Invia" />&nbsp;<input type="reset" value="Annulla">
                            </td>
                        </tr>
                    </table>
                </form>
            </fieldset>
        </center>

        <?php
        if($azione == "invia"){

            echo "arrivo qui 1<br>";

            $nome = $_POST["nome"];
            $cognome = $_POST["cognome"];
            $email = $_POST["email"];
            $tel_cell = $_POST["tel_cell"];
            $motivo = $_POST["motivo"];
            $interessi = $_POST["interessi"];
            $oggetto = $_POST["oggetto"];
            $msg = $_POST["messaggio"];
            $privacy = $_POST["privacy"];

            $MAX_FILE_SIZE = $_POST["MAX_FILE_SIZE"];
            $allegato = $_FILES["allegato"];

            $maxsize = 3 * 1024 * 1024; //3mb

            $nome_file_allegato = "";

            echo "arrivo qui 2<br>";

            /*if(isset($allegato)){ //se l'allegato è stato caricato con un file

               if($allegato['size'] > $maxSize){
                    //daremo un errore
                    echo "Attenzione! Il file allegato ".$allegato["name"]." è superiore alla dimensione di 3 MB.";
               }
               else{
                    $nuovoNome = $allegato['name'];

                    $cartellaDestinazione = __DIR__ . '/uploads/';
                    if (!is_dir($cartellaDestinazione)) {
                        mkdir($cartellaDestinazione, 0755, true);
                    }

                    $percorsoFinale = $cartellaDestinazione . $nuovoNome;
                    if (move_uploaded_file($allegato['tmp_name'], $percorsoFinale)) {
                        echo "File caricato con successo come: " . htmlspecialchars($nuovoNome);
                    } else {
                        echo "Si è verificato un errore nello spostamento del file.";
                    }
                    
               }
               

            }*/

            if(isset($_FILES["allegato"]) && $_FILES["allegato"]["error"] != UPLOAD_ERR_NO_FILE){ //se l'allegato e stato caricato con un file

                if($allegato['size'] > $maxsize){
                    // daremo un errore
                    echo " Attenzione: il file allegato è superiore alla dimensione di 5 MB";
                }
                else{ 

                    //upload del file
                    $file_temporaneo = $allegato['tmp_name'];

                    $path_uploads = "C:/xampp/htdocs/progettophp3/uploads/"; 

                    $nome_file = basename($allegato['name']);

                    $destinazione = $path_uploads . $nome_file;
                
                    // se file alleggato copiato con successo
                    if(move_uploaded_file($file_temporaneo, $destinazione)){
                        echo "file caricato con successo '$nome_file' !";
                    }
                    else{
                        echo "Si è verificato un errore nel caricamento del file !";
                    }
                }
            }
                    
            echo "---inizio email---";

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'mail.brigazzifabio.it';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'testdev@brigazzifabio.it';
                $mail->Password   = '$Br1g4zz1!2027@#';
                //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                //$mail->Port       = 587;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                // Mittente
                $mail->setFrom(
                    'testdev@brigazzifabio.it',
                    'Brigazzi Fabio'
                );

                // Destinatario
                $mail->addAddress($email);

                // Risposta all'indirizzo inserito dall'utente
                $mail->addReplyTo($email, $nome . ' ' . $cognome);

                // Oggetto e messaggio
                $mail->Subject = $oggetto;
                $mail->Body    = nl2br(htmlspecialchars($msg));
                $mail->isHTML(true);

                // Allegato
                if (
                    isset($destinazione) &&
                    file_exists($destinazione)
                ) {
                    $mail->addAttachment($destinazione, $nome_file);
                }

                // Invio
                $mail->send();

                echo "Email inviata con successo a " . $email;
            } catch (Exception $e) {
                echo "Errore: {$mail->ErrorInfo}";
            }

            echo "---fine email---";

            //inizio - invio la mail
            /*$to = $email;
            $subject = $oggetto;
            $message = $messaggio;

            $headers = [
                'From' => 'SlashSchool <fabio.brigazzi@slashschool.it>',
                'Reply-to' => 'Brigazzi Fabio <info@brigazzifabio.it>',
                'Cc' => 'dev@brigazzifabio.it',
                'Bcc'=> 'dev@brigazzifabio.it',
                'X-Mailer' => 'PHP/' . phpversion(),
                'Content-type' => 'text/html; charset=utf-8'
            ];

            if(mail($to, $subject, $message, $headers)){
                echo "Email inviata con successo a " . $to;
            }
            else{
                echo "Errore nell'invio dell'email";
            }*/
            //fine - invio la mail
            
        }
        ?>
    </body>
</html>