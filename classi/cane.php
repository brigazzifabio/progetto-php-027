<?php
require_once './Animale.php';

class Cane extends Animale{

    #[Override]
    public function verso(): string
    {
        return "Bau!<br>";
    }
}
?>