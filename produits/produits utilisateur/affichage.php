<?php
session_start();
require_once "liaison.php";

if(!isset($_SESSION['ref_uti'])){
    header("Location: login.php");
    exit;
}

$ref_uti = $_SESSION['ref_uti'];

/* AJOUT AU PANIER */
if(isset($_POST['add'])){

    $idprod = $_POST['idprod'];

    $check = $pdo->prepare("
        SELECT * FROM panier
        WHERE ref_uti = ?
        AND idprod = ?
    ");

    $check->execute([$ref_uti,$idprod]);

    if($check->rowCount() > 0){

        $update = $pdo->prepare("
            UPDATE panier
            SET quantite = quantite + 1
            WHERE ref_uti = ?
            AND idprod = ?
        ");

        $update->execute([$ref_uti,$idprod]);

    } else {

        $insert = $pdo->prepare("
            INSERT INTO panier(ref_uti,idprod,quantite)
            VALUES(?,?,1)
        ");

        $insert->execute([$ref_uti,$idprod]);
    }
}

/* produits */
$req = $pdo->query("
SELECT * FROM produit
WHERE statut = 'actif'
AND stock > 0
");

$produits = $req->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/produits.css">
</head>
<body>
  
    <?php include("include/header.php"); ?>
<div class="page">

  
    <div class="products">

        <h1>Nos Produits </h1>

        <div class="product-list">

            <?php foreach ($produits as $prod) { ?>

                <div class="product-card">
                    <img src="image/<?php echo $prod['image']; ?>" alt="">
                    <h3><?php echo $prod['nomprod']; ?></h3>
                    <p><?php echo $prod['prix']; ?> FCFA</p>
                   
                    <form method="POST">

                        <input type="hidden"
                        name="idprod"
                        value="<?php echo $prod['idprod']; ?>">

                        <?php if($prod['stock'] > 0){ ?>

                            <button type="submit" name="add">
                                Ajouter au panier
                            </button>

                        <?php } else { ?>

                            <button disabled style="background:gray;">
                                Rupture de stock
                            </button>

                        <?php } ?>

                    </form>
                    
                </div>

            <?php } ?>

        </div>

    </div>




</div>

<?php include("include/footer.php"); ?>
</body>
</html>