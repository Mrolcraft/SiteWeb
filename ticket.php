<?php

session_start();
if(!isset($_SESSION['pseudo'])) {
    header('Location: Connexion.php');
}

function formatPriorite($priority) {
    if($priority == "basse") return 0;
    if($priority == "moyenne") return 0;
    if($priority == "haute") return 0;
}

if(sizeof($_POST) != 0) {

    if(isset($_POST['motif'])) {
        $motif = htmlspecialchars($_POST['motif']);
        $subject = htmlspecialchars($_POST['sujet']);
        $email = htmlspecialchars($_POST['email']);
        $sector = htmlspecialchars($_POST['secteur']);
        $priorite = htmlspecialchars($_POST['priorite']);

        try {
            $conn = new PDO('mysql:host=localhost;dbname=projet', 'root', '');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO tickets (username, email, subject, reason, sector, priority) VALUES (?,?,?,?,?,?)";
            $sql = $conn->prepare($sql);
            $sql->execute([$_SESSION['pseudo'], $email, $subject, $motif, $sector, formatPriorite($priorite)]);
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

<!DOCTYPE html>
<link rel="stylesheet" href="ticket.css">
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width"/>
    <title>Zoo de Cergy | Tickets</title>
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
                        <a class="dropdown-item" href="ticket.php">Ticket</a>
                    </div>
                </li>
                <li class="nav-item active">
                    <a class="navbar-brand" href="Connexion.php">Connexion</a>
                </li>
                <li class="nav-item active">
                    <a class="navbar-brand" href="Inscription.php">Inscription</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<main>Création de tickets
    <form method="post" action="#">
        <div class="form-group">
            <label for="exampleInputEmail1">adresse email</label>
            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" placeholder="Entrez l'email">
            <small id="emailHelp" class="form-text text-muted">Votre Email restera confidentiel.</small>
        </div>
        <div class="form-group">
            <label for="sujet">Sujet</label>
            <input type="text" name="sujet" class="form-control" id="sujet" placeholder="sujet">
        </div>
        <div class="form-group">
            <label for="priorite">priorité</label>
            <select name="priorite" id="priorite">
                <option value="">--Choississez une option--</option>
                <option value="basse">Basse</option>
                <option value="moyenne">Moyenne</option>
                <option value="haute">Haute</option>
            </select>
        </div>
        <div class="form-group">
            <label for="secteur">Secteur</label>
            <select name="secteur" id="secteur">
                <option value="">--Choississez une option--</option>
                <option value="est">Est</option>
                <option value="ouest">Ouest</option>
                <option value="nord">Nord</option>
                <option value="sud">Sud</option>
            </select>
        </div>
        <div class="form-group">
            <label for="probleme">Votre problème</label>
            <input type="text" name="motif" class="form-control" id="probleme" placeholder="probleme">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <?php
    try {
        $conn = new PDO('mysql:host=localhost;dbname=projet', 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if(!isset($_SESSION['pseudo']) OR !isset($_SESSION['role'])) header('Location: Connexion.php');

        $sql = '';
        if($_SESSION['role'] == 1) {
            $sql = "SELECT * FROM tickets ORDER BY date DESC";
            $sql = $conn->prepare($sql);
            $sql->execute();
        } else {
            $sql = "SELECT * FROM tickets WHERE username=:username ORDER BY date DESC";
            $sql = $conn->prepare($sql);
            $sql->execute(['username' => $_SESSION['pseudo']]);
        }
        if($sql->rowCount() == 0) {
            echo "Il n'y a aucun ticket";
        } else {
            echo '<form action="UpdateTicket.php" method="post">';
            while($result = $sql->fetch()) {
                if($_SESSION['role'] == 1) {
                    echo '<div>';
                    echo 'Ticket n°' . $result['id'] . ' - ' . $result['date'] . ' - ' . $result['username'] . ' - ' . 'priorité: ' . $result['priority'] . ' </br> Motif: ' . $result['reason'] . '</br>Status: ' . formatState($result['state']). '</br><input type="checkbox" name="solved-' . $result['id'] . '" id="solved"></br></br>';
                    echo '</div>';
                } else {
                    echo '<div>';
                    echo 'Ticket n°' . $result['id'] . ' - ' . $result['date'] . ' - ' . $result['username'] . ' - ' . 'priorité: ' . $result['priority'] . ' </br> Motif: ' . $result['reason'] . '</br>Status: ' . formatState($result['state']). '</br></br>';
                    echo '</div>';
                }
            }
            if($_SESSION['role'] == 1) {
                echo '<input type="submit" value="Mettre à jour" name="submit">';
            }
            echo '</form>';
        }
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }

    function formatState($state) {
        if($state == 0) return "Viens d'être ajouté";
        if($state == 1) return "En cours de résolution...";
        if($state == 2) return "Résolu";
    }
    ?>
</main>
<footer></footer>
</body>
</html>