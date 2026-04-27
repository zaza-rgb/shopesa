<?php
require '../liaison.php';

if (isset($_POST['idprod'])){
        $id=$_POST['idprod'];
}else {
    $id=$_GET['id'];
}
        $stmt = $com->prepare("SELECT * FROM produit RIGHT JOIN categorie ON produit.id_cat = categorie.id_cat WHERE idprod = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $image = htmlspecialchars($row['image']);
            $nomprod = htmlspecialchars($row['nomprod']);
            $price = htmlspecialchars($row['prix']);
            $descri = htmlspecialchars($row['description']);
            $nom_cat= htmlspecialchars($row['nom']);
        } else {
            echo "Produit introuvable.";
        }

    ?>