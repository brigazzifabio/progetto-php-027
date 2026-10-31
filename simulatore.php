<?php
//pagina in cui il docente spiega parti del php viste nel simulatore

//1. FUNZIONE strptags() oppure strip_tags()
//cosa fa: rimuove tutti i tags html da una stringa o solo quelli che gli vengono detti

echo "----------FUNZIONE strip_tags()-------------<br><br>";

$input = "<p>Testo con <b>grassetto</b> e un <a href='https://www.google.com'>link</a></p>";
echo "input:" . $input . "<br>";

//A. pulisce tutti i tag
$input_pulito = strip_tags($input);

echo "input_pulito:" . $input_pulito . "<br>";

//B. pulire solo i tag che si vuole
$input_parziale = strip_tags($input, ['b','p']);

echo "input_parziale:" . $input_parziale . "<br>";

echo "----------FINE FUNZIONE strip_tags()---------<br><br>";

//2. FUNZIONI DI ORDINAMENTO DEGLI ARRAY
// A.asort(), arsort(), B.sort(), ksort() / krsort(), C.rsort(), D.uksort(), E.usort() / uasort()

// A.asort() /  - ordina in desc e mantiene le chiavi-valore
echo "asort()<br><br>";
$eta = ["Rossana" => 24, "Fabio" => 52, "Viorica" => 38];
print_r($eta);

echo "<br><br>";

asort($eta);
print_r($eta);

echo "<br><br>";

// B. arsort() - ordina in asc
echo "arsort()<br><br>";
$eta2 = ["Pina" => 50, "Ugo" => 52, "Mariangela" => 15];
print_r($eta2);

echo "<br><br>";

arsort($eta2);
print_r($eta2);

//C. sort() e rsort() - 1. crescente per valore 2. reverse per valore 
//(non mantengono le chiavi-valore e gli indici)
echo "<br><br>sort()<br><br>";
//$frutti = ["b" => "Banana", "a" => "Arancia", "m" => "Mela"];
$frutti = ["Banana", "Arancia", "Mela"];
print_r($frutti);

echo "<br><br>";

sort($frutti);
print_r($frutti);

echo "<br><br>";
echo "rsort()<br><br>";

rsort($frutti);
print_r($frutti);

//D. ksort() e krsort() - 1. ordine alfabetico crescente delle chiavi  2. ordine alfabetico decrescente delle chiavi
echo "<br><br>ksort()<br><br>";
$rubrica = [
    "Zaccaria" => "333111",
    "Alberto"  => "347222",
    "Carlo"    => "320333"
];
print_r($rubrica);

echo "<br><br>";

ksort($rubrica);
print_r($rubrica);

echo "<br><br>krsort()<br><br>";

krsort($rubrica);
print_r($rubrica);
?>