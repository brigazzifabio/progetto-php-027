<?php
require_once './Cane.php';
require_once './Gatto.php';

try{
    $a = new Cane("Fido",3);
    $a->setName("Fido");
    echo $a->presentati();
    echo $a->verso();
}
catch(Exception $ex){
    echo "errore" . $ex->getMessage(); 
}
try{
    $a = new Gatto("Micio",5);
    $a->setName("Micio");
    echo $a->presentati();
    echo $a->verso();
}
catch(Exception $ex){
    echo "errore" . $ex->getMessage();
}
?>