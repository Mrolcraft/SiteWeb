<?php

if(sizeof($_POST) == 3) {
    if(isset($_POST['pseudo']) and isset($_POST['password'])) {
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $password = htmlspecialchars($_POST['password']);

        try {
            $conn = new PDO('mysql:host=localhost;dbname=projet', 'root', '');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO users (username, password) VALUES (?,?)";
            $sql = $conn->prepare($sql);
            $sql->execute([$pseudo, password_hash($password, PASSWORD_DEFAULT)]);
            echo "Inscription réussie !";
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    } else {
        echo "pas bon";
    }
} else {
    echo "y'a r";
}

?>

<html>
<head>
    <title>Projet Site web</title>
    <meta charset="UTF-8">
</head>
<body>
<h1>Inscription</h1>
<form method="post" id="inscriptionForm" action="#">
    <div>
        <label for="pseudo">Votre pseudo: </label>
        <input type="text" id="pseudo" name="pseudo">
    </div>
    <div>
        <label for="password">Votre mot de passe: </label>
        <input type="password" id="password" name="password">
    </div>
    <div>
        <input type="submit" id="submit" name="submit" value="Se connecter">
    </div>
</form>
</body>
</html>
