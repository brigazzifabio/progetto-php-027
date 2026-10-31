<?php
    /*
    Crea una classe Animale con:
-> proprietà protected $nome ed $eta
-> un costruttore che le inizializza
-> un metodo presentati() che restituisce "Sono {nome} e ho {eta} anni"
-> un metodo verso() che restituisce "..."

->Crea poi le sottoclassi Cane e Gatto, che sovrascrivono verso() restituendo rispettivamente "Bau!" e "Miao!".

Infine crea un array con alcuni animali e stampa per ognuno la presentazione e il verso.

Output atteso:

Sono Fido e ho 3 anni - Bau!
Sono Micio e ho 5 anni - Miao!
    */

   class Animale{
      private string $nome;
      private int $eta;
      
      public function __construct(string $nome, int $eta){
        $this->nome = $nome;
        $this->eta = $eta;
      }
      public function getName():string{
        return $this->nome;
      }
      public function setName(string $nome){
        $this->nome = $nome;
      }
      public function getEta():int{
        return $this->eta;
      }
      public function setEta(int $eta){
        $this->eta = $eta;
      }
      public function presentati():string{
        $presentazione = "<br><br>Sono " . $this->nome . " e ho " . $this->eta . " anni<br>";
        return $presentazione;
      }
      public function verso():string{
        return "...";
      }
   }
   //consegna senza classi
   $animali = [
        ["nome" => "leone", "verso" => "Roar!"],["nome" => "cavallo", "verso" => "ih ih!"],["nome" => "bue", "verso" => "muu!"],
   ];    
   for($i = 0; $i < count($animali); $i++){
      echo "<br>Sono un " . $animali[$i]["nome"] . " il mio verso è " . $animali[$i]["verso"];
   }
   //consegna con le classi
?>