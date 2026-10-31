<html>
    <head>
        <title>Files | Progetto PHP</title>
    </head>
<body>
    <center>
    <h2>Pagina Test per i Files</h2>

    <?php
    //I FILES
    //fopen('files/dati.txt','r');     #1 - r => read
    //fopen('files/dati.txt','w');     #2 - w => write =>  svuota il file e scrive (se file non esiste, lo crea e scrive).
    //fopen('files/dati.txt','a');     #3 - a => append => scrive alla fine del file.

    $nome_file = 'files/dati.txt';
    //ESEMPIO - WRITE
    /*echo "leggo file in scrittura<br>";
    $file_dati = fopen($nome_file,'a'); 
    fwrite($file_dati, "Terza riga\n");
    fwrite($file_dati, "Ultima riga\n");
    fclose($file_dati);
    echo "chiudo file in scrittura<br>";*/

    //ESEMPIO - READ 1
    /*echo "leggo file in lettura<br><br>";
    $file_dati = fopen($nome_file,'r');
    $contenuto = fread($file_dati, filesize($nome_file));
    echo nl2br(htmlspecialchars($contenuto));
    fclose($file_dati);
    echo "<br><br>chiudo file in lettura";*/

    //ESEMPIO - READ 2
    echo "leggo file '$nome_file' in lettura<br><br>";
    $file_dati = fopen($nome_file,'r');

    while(($riga = fgets($file_dati)) != false){
        echo trim($riga) . "<br>";
    }
    
    fclose($file_dati);
    echo "<br>chiudo file in lettura<br>";
    ?>

    </center>
</body>
</html>