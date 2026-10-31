function cancellaUtente(id_utente){
    if(confirm("Vuoi cancella l'utente selezionato ?")){
        window.open("utenti.php?azione=cancella&id_utente="+id_utente,"_top");
    }
}
function vediPassword(){
    document.getElementById('password').type = "text";
}
function nascondiPassword(){
    document.getElementById('password').type = "password";
}
function recuperaUtente(id_utente){
    if(confirm("Vuoi recuperare l'utente selezionato ?")){
        window.open("utenti_cestino.php?azione=recupera&id_utente="+id_utente,"_top");
    }
}
function esci(){
    if(confirm("Sei sicuro di voler uscire ?")){
        window.open("login.php?azione=logout","_top");
    }
}