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
<link rel="stylesheet" href="stylesheet.css">
    <head>
        <title>Projet Site web</title>
        <meta charset="UTF-8">
    </head>
    <body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">


            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="navbar-brand" href="index.html">Accueil</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="animaux.html">Animaux <span class="sr-only"></span></a>
                    </li>
                    <li>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="ticket.html">Ticket</a>
                        </div>
                    </li>
                    <li class="nav-item active">
                        <a class="navbar-brand" href="Inscription.php">Connexion</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </nav>
    </header>
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
