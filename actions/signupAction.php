<?php

require('actions/database.php');
//validation formulaire
if(isset($_POST['validate'])){

    //verifier si l'user a completer tous les champs
    if(!empty($_POST['pseudo']) AND !empty($_POST['lastname']) AND !empty($_POST['firstname']) AND !empty($_POST['password'])){
       // les donnes de user
        $user_pseudo = htmlspecialchars($_POST['pseudo']);
        $user_lastname = htmlspecialchars($_POST['lastname']);
        $user_firstname = htmlspecialchars($_POST['firstname']);
        $user_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        //verifier si l'utilisateur existe deja sur le site
        $checkIfUserAlreadyExists = $bdd->prepare('SELECT pseudo FROM users WHERE pseudo = ?');
        $checkIfUserAlreadyExists->execute(array($user_pseudo));

        if($checkIfUserAlreadyExists->rowCount()==0){
            //Insere l'utilisateur dans la bdd 
            $insertUserOnWebsite = $bdd->prepare('INSERT INTO users(pseudo, nom, prenom, mdp)VALUES(?,?,?,?)');
            $insertUserOnWebsite->execute(array($user_pseudo, $user_lastname, $user_firstname, $user_password));
            
            // Recuperer les information de l'utilisateur
            $getInfoOfThisUserReq = $bdd->prepare('SELECT id,pseudo, nom, prenom FROM users WHERE nom = ? AND prenom = ? AND pseudo = ?');
            $getInfoOfThisUserReq->execute(array($user_lastname,$user_firstname,$user_pseudo));
            
            $usersInfo = $getInfoOfThisUserReq->fetch();
            
            //Authentifier l'utilisateur sur le site et recuperer ses donnes dans la variable globals
            $_SESSION['auth']=true;
            $_SESSION['id']= $usersInfo['id'];
            $_SESSION['lastname']= $usersInfo['nom'];
            $_SESSION['firstname']= $usersInfo['prenom'];
            $_SESSION['pseudo']= $usersInfo['pseudo'];
             // Rederiger l'utilisateur vers la page d'acceuil
            header('Location: index.php');


        }else{
            $errorMsg = "L'utilisateur existe déjà sur le site";

        }

        

    }else{
        $errorMsg = "Veuillez compléter tous les champs ....";

    }
}