<?php

if(sizeof($_POST) != 0) {
    foreach($_POST as $k => $v)
    {
        if(strpos($k, 'solved') !== false) {
            $id = explode('-', $k)[1];
            try {
                $conn = new PDO('mysql:host=localhost;dbname=projet', 'root', '');
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "UPDATE tickets SET state = 2 WHERE id=:id";
                $sql = $conn->prepare($sql);
                $sql->execute( ['id' => $id]);
                header('Location: ticket.php');
            } catch (PDOException $e) {
                echo "Erreur: " . $e->getMessage();
            }
        }
    }
}

