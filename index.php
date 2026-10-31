<html>
    <head>
        <title>Home page - Progetto PHP</title>
    </head>
    <body>
        <?php
        include("menu.php");
        ?>    

        <?php
        //frasi di benvenuto e comento multilinea /**/ 
        echo "<b>benvenuto nella home page php</b><br>";
        //print "questa è la mia prima pagina php<br>";

        //VARIABILI e COSTANTI
        $nome = "Mario";               //stringa alfanumerica
        $qta = 5;                      //intero
        $prezzo = 49.99;               //double => intero + decimale
        $totale = $prezzo * $qta;       //operatori * / + -
        //concatenazione di stringhe con il punto
        echo "nome: " . strtoupper($nome) . "<br>";   //tutte maiuscole   
        echo "qta: " . strtolower($qta) . "<br>";     //tutte minuscole
        echo "prezzo: " . ucwords($prezzo) . "&nbsp;&euro;<br>";         //iniziale maiuscola di ogni parola
        echo "totale: " . ($totale) . "&nbsp;&euro;<br>";

        //COSTRUTTI CONDIZIONALI - IF
        if($qta != 10){                //operatori condizionali > < >= >= == !=
            echo "quantita superiore a 10<br>";
        }
        else if($qta == 10 || $prezzo > 100){  //&& => ADN, || => OR
            echo "quantità uguale a 10<br>";
        }
        else {
            echo "quantità inferiore o uguale a 10<br>";

        }

        //10 esercizio - stampa scontrino
        //A. se la qta è maggiore di 4 applicare uno sconto del 10% al prezzo
        //B. se la qta è minore o uguale a 4 e il prezzo è minore di 50, applicare uno sconto del 5% al prezzo
        //C. separatamente, creare le variabili sconto e prezzo_scontato e totale_scontato
        //D. stampare tutto lo scontrino aggiornato insieme alle variabili precedenti

        $sconto = 0;
        //PUNTO A
        if($qta > 4){
            $sconto = 10;
        }
        //PUNTO B
        else if($qta <= 4 && $prezzo < 50){
            $sconto = 5;
        }
        //PUNTO B e C
        $prezzo_scontato = $prezzo - (($prezzo / 100) * $sconto);

        //PUNTO C
        $totale_sconto = $prezzo - $prezzo_scontato;
        $totale_scontato = $prezzo_scontato * $qta;

        //PUNTO D
        echo "<br><br>---------SCONTRINO-----------<br>";
        echo "qta: " . $qta . "<br>";
        echo "prezzo: " . number_format($prezzo,2,',','.') . "&nbsp;&euro;<br>";
        echo "<i>sconto: " . $sconto . "&nbsp;% - " . number_format($totale_sconto,2,',','.') . "&nbsp;&euro;</i><br>";
        echo "prezzo scontato: " . number_format($prezzo_scontato,2,',','.') . "&nbsp;&euro;<br>";
        echo "totale: " . number_format($totale,2,',','.') . "&nbsp;&euro;<br>";
        echo strtoupper("<b>da pagare: " . number_format($totale_scontato,2,',','.')) . "&nbsp;&euro;</b><br>";
        echo "<br><br>-----------------------------<br>";

        //COSTRUTTI CONDIZIONALI - SWITCH
        switch($qta){
            case 4:
                echo "<br>la qta è 4<br><br>";
                break;
            case 6:
                echo "<br>la qta è 6<br><br>";
                break;
            default:
                echo "<br>la qta ha un altro valore ovvero " . $qta . "<br><br>";
                break;
        }

        //ARRAY o VETTORI
        $mele = ["mela verde", "mela gialla", "mela rossa", "mela", "mela grande"];   //0,1,2 => = 3
        //echo $mele[1];  //stampa mela gialla

        //cicli - for
        echo "<h3> Elenco Mele</h3>";
        echo "<ol>";
        for($i = 0; $i < count($mele); $i++){
            echo "<li>" . $mele [$i] . "</li>";
        }
        echo "</ol>";
        //esercizio 1 - arrey e ciclo for
        /* 
        la lista della spesa (facile)
        obettivo:
        creare un array semplice, calcolare la sua lunghezza e stamparlo come puntata HTML.

        Testo lell'esercizio
        1. creare una array chiamato $spesa che contenga almeno 5 prodotti alimentari (es. "pane", "latte", ecc.).
        2. usare la funzione count () di php per determinare quanti elementi ci sono 'array e salva questo valore in una variabile
        3. usa un ciclo for per scorrere l'arrey e stampare ogni prodotto all'interno di un tag html <li>.
        */
        $spesa = ["acqua", "uova", "biscotti", "frutta", "caffe" ];
        $totspesa = count($spesa);
        echo "<h3> Elenco Spesa </h3>";
        echo "<ol>";
        for($i = 0; $i < count($spesa); $i++){
            echo "<li>" . $spesa[$i] . "</li>";
        }
        echo "</li>";
                  
        $spesa = ["pane", "latte", "frutta", "mele", "formaggio"  ];
        $totspesa = count($spesa);
        echo "<h3> Elenco Spesa </h3>";
        echo "<ol>";
        for($i = 0; $i < $totspesa; $i++){
            echo "<li>" . $spesa[$i] . "</li>";
        }
        echo "</ol>";
        
        //esercizio 2 - array e ciclo for
        /*
        esercizio 2: il monitor della temperatura (intermedio)
        obettivo: lavorare con array numerici, calcolare la media dei valori e formattare 1'output.

        testo del esercizio
        1. crea un array chiamato $temperatura le temperature massima registrate in una settimana (7 valori numerici, es: 22, 25, 19, .....).
        2. usa un ciclo for per stampare una riga per ogni giorno, indicato l'indice del giorno e la temperatura (es. "giorno 1: 22°c").
        3. sfida: durante il ciclo (o subito dopo), somma tutto le temperature e, fuori dal ciclo, calcola e stampa la temperatura media della settimana.

        Output attesa:
        Giorno 1: 22°c
        Giorno 2: 25°c

        ...
        Temperatura media della settimana: 21.4°c
        */
        $temperatura = [20, 22, 23, 26, 28, 30, 25];
        $media = 0;
        $somma = 0;
        echo "<h3> Temperature  settimanali </h3>";
        for($t = 0; $t < count($temperatura); $t++){ 
            echo "giorno " . ($t+1) . " _ " . $temperatura[$t] . "°C <br>";
            $somma = $somma + $temperatura[$t];
        }
        $media = $somma / count($temperatura);
        echo "La temperatura media della settimana: " , number_format($media,"2",",",".") , "°C <br>";


        $temperatura = [20, 19, 22, 25, 28, 30, 27];
        $media = 0;
        $somma = 0;
        echo "<h3> Temperature  settimanali </h3>";
            for($t=0; $t < count($temperatura); $t++){
                echo "giorno ". ($t+1) . " - " . $temperatura[$t] . "°C <br>";
                $somma = $somma + $temperatura[$t];
            }
            $media = $somma / count($temperatura);
            echo "la temperatura media della settimana: " . number_format($media,"2",",",".") . "°C <br>";
      
        $temperatura = ["22", "25", "19", "18", "26", "20", "24"];
        $media = 0;
        $somma = 0;

         echo "<h3> Temperature  settimanali </h3>";

            for($t=0; $t <count($temperatura); $t++){ //$t++ => $t=$t+1
                echo "giorno ". ($t+1) . " - " . $temperatura[$t] . "°C <br>";
                $somma = $somma + $temperatura[$t]; // normale
                // $somma += $temperatura [$t];      //breve
            }
            $media = $somma / count($temperatura);
            echo "la temperatura media della settimana: "  . number_format($media,"2",",",".") . "°C <br>" . "<br>";
            ?>
    </body>
</html>