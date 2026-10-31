<script>
    function esci(){
        if(confirm("Sei sicuro di voler uscire ?")){
            window.open("login.php?azione=logout","_top");
        }
    }
</script>

<a href="index.php"><button style="width: 120px;"><b>Home Page</b></button></a>&nbsp;
<!--<a href="utenti.php"><button style="width: 120px;"><b>Utenti</b></button></a>
<a href="contatti.php"><button style="width: 120px;" disabled>Contatti</button></a>//-->
<a href="pannello_controllo.php"><button style="width: 120px;">Pannello Controllo</button></a>&nbsp;
<button style="width: 120px;" onclick="esci();">Esci</button>
<br><br>