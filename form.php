<?php

// MANQUE :  unlink et file_exists

//UPLOAD PHOTO ET VERIFICATION DONNÉES FORMULAIRE NOM, PRENOM ET AGE

// Je vérifie si le formulaire est soumis comme d'habitude
if($_SERVER['REQUEST_METHOD'] === "POST") {
// Securité en php
// chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés (attention ce dossier doit être accessible en écriture)
    $uploadDir = __DIR__.'/upload/';
// le nom de fichier sur le serveur est ici généré à partir du nom de fichier sur le poste du client (mais d'autre stratégies de nommage sont possibles)
    $uploadFile = $uploadDir . uniqid();
// Je récupère l'extension du fichier
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $uploadFile .= '.'.$extension;
// Les extensions autorisées
    $authorizedExtensions = ['jpg', 'png', 'gif', 'webp'];
// Le poids max géré par PHP par défaut est de 2M
    $maxFileSize = 1000000;

// Je sécurise et effectue mes tests
    $errors = [];

    /****** Si l'extension est autorisée *************/
    if ((!in_array($extension, $authorizedExtensions))) {
        $errors[] = 'Veuillez sélectionner une image de type Jpg ou Jpeg ou Png !';
    }

    /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
    if (file_exists($_FILES['avatar']['tmp_name']) && filesize($_FILES['avatar']['tmp_name']) > $maxFileSize) {
        $errors[] = "Votre fichier doit faire moins de 1Mo !";
    }

    if (!isset($_POST['firstname']) || $_POST['firstname'] === '') {
        $errors['firstname'] = 'Le prénom est obligatoire';
    }

    if (!isset($_POST['lastname']) || $_POST['lastname'] === '') {
        $errors['lastname'] = 'Le nom est obligatoire';
    }
    if (!isset($_POST['age']) || $_POST['age'] === '') {
        $errors['age'] = "L'âge est obligatoire";
    }
    if (!$errors && !is_string($_POST['firstname'])) {
        $errors['firstname'] = 'Veuillez rentrer uniquement des lettres';
    }
    if (!$errors && !is_string($_POST['lastname'])) {
        $errors['lastname'] = 'Veuillez rentrer uniquement des lettres';
    }



    /****** Si je n'ai pas d"erreur alors j'upload *************/
    /**
     */

    // on déplace le fichier temporaire vers le nouvel emplacement sur le serveur. Ça y est, le fichier est uploadé
    move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile);
}
/*

Cette variable est un tableau de tableaux, qui prend en première clé le nom du/des champ(s) de type file du formulaire, et en seconde dimension un tableau de 5 éléments. Voici le code correspondant au formulaire écrit juste au dessus, pour le champ de type file portant le nom avatar :

$_FILES['avatar']['name'] Contient le nom d'origine du fichier (sur le poste du client)

$_FILES['avatar']['tmp_name'] Contient le nom temporaire du fichier dans le dossier temporaire du système (sur le serveur)

$_FILES['avatar']['type'] Contient le type MIME du fichier (plus fiable que l'extension)

$_FILES['avatar']['size'] Contient la taille du fichier en octets

$_FILES['avatar']['error'] Contient le code de l'erreur (le cas échéant)

Pour finir, tu dois gérer l'enregistrement du fichier sur le serveur via la fonction move_uploaded_file()


*/
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<!-- L'attribut enctype="multipart/form-data" est obligatoire pour gérer correctement les fichiers uploadés
via un formulaire, sans cet attribut l'upload ne fonctionnera pas.-->
<form method="post" enctype="multipart/form-data">

    <label for="firstname">Prénom</label>
    <input type="text" id="firstname" name="firstname" required value>
    <label for="lastname">Nom</label>
    <input type="text" id="lastname" name="lastname" value>
    <label for="age">Age</label>
    <input type="number" id="age" name="age" value>
    <br><br>
    <label for="imageUpload">Upload an profile image</label>
    <!--Le champ de type file qui va t'aider à récupérer ton image (ou fichier) de ton pc/mac.-->
    <input type="file" name="avatar" id="imageUpload" />
    <button name="send">Send</button>
</form>

</body>
</html>