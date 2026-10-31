<?php
    class Utente{  

        private string $username; 
        private string $email;
        private int $attivo;  
        
        public function __construct(string $username, string $email, int $attivo){
            $this->username = $username;
            $this->email = $email;
            $this->attivo = $attivo;    
        }
        //this --- significa, questa classe
        //quando si fa return si mette sempre cosa si deve returnare
         public function getUsername():string {
                return $this->username;
         }
         public function setUsername(string $username) {
                $this ->username = $username;
         }
         public function getEmail():string{
                return $this ->email;
         }
         public function setEmail(string $email){
                $this -> email = $email;
         }
         public function getAttivo():int{
                return $this ->attivo;
         }
         public function setAttivo(int $attivo){
                $this -> attivo = $attivo;
         }
         public function print(){// questa sarebbe una funzione che l'abbiamo creato noi 
              echo "username: " .  $this->getUsername() . "<br>";;
              echo "email: " . $this->getEmail() . "<br>";
              echo " attivo: " . $this->getAttivo() . "<br>";
         }
    }
    try{   //try significa "prova" se tutto il codoice va bene lui fa tutto cio che e scritto nel codice poi procede dalla riga 50 
       $u = new Utente("viorica", "vio.cocarcea9@libero.it",1);
       $u->setUsername(ucwords("viorica"));
       $u->print(); 
    }
    catch(Exception $ex){ // se nel codice di sopra (nel try) da errore, nel Exeption va interceptata. 
       echo "errore: " . $ex->getMessage();
    }
    // al interno di questa classe non eseste solo il get, set, costrutore e le variabile. esistono anche le funzioni che li posiamo creare noi.
?>
    
    
    
