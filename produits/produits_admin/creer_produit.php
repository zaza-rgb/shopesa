<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Authentification/connect_admin.php');
    exit;
}
require_once '../liaison.php';

$errors  = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom         = trim($_POST['nom']         ?? '');
    $categorie   = trim($_POST['categorie']   ?? '');
    $statut      = trim($_POST['statut']      ?? 'en_stock');
    $prix        = trim($_POST['prix']        ?? '');
    $stock       = trim($_POST['stock']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $tailles     = isset($_POST['tailles'])  ? implode(',', $_POST['tailles'])  : '';
    $couleurs    = isset($_POST['couleurs']) ? implode(',', $_POST['couleurs']) : '';

    // Validation
    if (empty($nom))         $errors[] = 'Le nom du produit est obligatoire.';
    if (empty($categorie))   $errors[] = 'La catégorie est obligatoire.';
    if (!is_numeric($prix) || $prix < 0) $errors[] = 'Le prix doit être un nombre positif.';
    if (!is_numeric($stock) || $stock < 0) $errors[] = 'Le stock doit être un entier positif.';
    if (empty($description)) $errors[] = 'La description est obligatoire.';

    // Upload image
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed   = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $file_type = mime_content_type($_FILES['image']['tmp_name']);
        if (!in_array($file_type, $allowed)) {
            $errors[] = 'Format image non supporté (jpg, png, webp, gif).';
        } else {
            $ext        = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name  = uniqid('prod_') . '.' . $ext;
            $upload_dir = '../image/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $file_name);
            $image_path = $file_name;
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO produit (nom, categorie, statut, prix, stock, description, tailles, couleurs, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$nom, $categorie, $statut, $prix, $stock, $description, $tailles, $couleurs, $image_path]);
            header('Location: produits.php?success=1');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Erreur base de données : ' . $e->getMessage();
        }
    }
}

// S'il y a des erreurs, retourner sur le formulaire avec message
if (!empty($errors)) {
    // Stocker dans session et rediriger
    $_SESSION['prod_errors'] = $errors;
    $_SESSION['prod_data']   = $_POST;
    header('Location: ajouter-produit.php?error=1');
    exit;
}

// Si GET sans POST → rediriger
header('Location: ajouter-produit.php');
exit;
