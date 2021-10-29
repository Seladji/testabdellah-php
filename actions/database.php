<?php

try{
    session_start();
    $bdd = new PDO('mysql:host=localhost;dbname=forum;charest=utf8;','root','');
}catch(Exeption $e){
    die('une erreur a ètè trouvée : ' . $e->getMessage());
}

