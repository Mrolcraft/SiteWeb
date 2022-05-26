<?php
session_start();

if(sizeof($_POST) == 3) {
    if(isset($_POST['pseudo']) and isset($_POST['password'])) {
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $password = htmlspecialchars($_POST['password']);

        try {
            $conn = new PDO('mysql:host=localhost;dbname=projet', 'root', '');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM users WHERE username = :pseudo";
            $sql = $conn->prepare($sql);

            $sql->execute(['pseudo' => $pseudo]);
            $result = $sql->fetch();
            if($sql->rowCount() == 0) {
                echo "Pseudo inconnu";
            } else {
                if(password_verify($password, $result['password'])) {
                    $_SESSION['pseudo'] = $pseudo;
                    echo "Connexion réussie !";
                } else {
                    echo "Le mot de passe est faux.";
                }
            }
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
        <h1>Connexion</h1>
        <?php if(!isset($_SESSION['pseudo'])) {?>
        <form method="post" id="connexionForm" action="#">
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
        <?php } else { ?>
        <p><?= 'Bienvenue ' .$_SESSION['pseudo']?></p><?php }?>
    </body>
</html>
