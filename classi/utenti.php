<?php
    class Utente {

        private string $username;
        private string $email;
        private int $attivo;

        public function __construct(string $username, string $email, int $attivo){
            $this->username = $username;
            $this->email = $email;
            $this->attivo = $attivo;
        }

        public function getUsername():string {
            return $this->username;
        }

        public function setUsername(string $username) {
            $this->username = $username;
        }

        public function getEmail():string {
            return $this->email;
        }

        public function setEmail(string $email) {
            $this->email = $email;
        }

        public function getAttivo():int {
            return $this->attivo;
        }

        public function setAttivo(string $attivo) {
            $this->attivo = $attivo;
        }

        public function print(){
            echo "username: " . $this->getUsername() . "<br>";     //prendete il valore dello username
            echo "email: " . $this->getEmail() . "<br>";
            echo "attivo: " . $this->getAttivo() . "<br>";
        }

    }

    try{
        $u = new Utente("fabio", "fabio.brigazzi@gmail.com", 1);
        $u->setUsername(ucwords("fabio"));                  //dare il valore ad una variabile della classe
        $u->print();
    }
    catch(Exception $ex){
        echo "errore: " . $ex->getMessage();
    }

?> 