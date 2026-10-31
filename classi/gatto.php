<?php
require_once './Animale.php';

class Gatto extends Animale{
     
    #[Override]
    public function verso(): string
    {
        return "Miao!";
    }
}
?>