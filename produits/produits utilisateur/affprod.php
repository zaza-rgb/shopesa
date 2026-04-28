<?php
session_start();
require_once '../../liaison.php';

// Récupérer tous les produits depuis la BDD
$produits = [];
try {
    $categorie_filtre = $_GET['cat'] ?? '';
    $search           = trim($_GET['q'] ?? '');

    $sql    = "SELECT * FROM produit WHERE statut != 'desactive'";
    $params = [];

    if ($categorie_filtre && $categorie_filtre !== 'Tous') {
        $sql    .= " AND categorie = ?";
        $params[] = $categorie_filtre;
    }
    if ($search) {
        $sql    .= " AND (nom LIKE ? OR description LIKE ? OR categorie LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $sql .= " ORDER BY date_ajout DESC";

    $stmt    = $pdo->prepare($sql);
    $stmt->execute($params);
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    // Données démo si table absente
    $produits = [
        ['ref_produit'=>1,'nom'=>'Sneakers Premium',  'categorie'=>'Chaussures',  'prix'=>129.99,'stock'=>45,'statut'=>'en_stock','image'=>'','description'=>'Sneakers confortables et stylées'],
        ['ref_produit'=>2,'nom'=>'Montre Élégante',   'categorie'=>'Accessoires', 'prix'=>299.99,'stock'=>23,'statut'=>'en_stock','image'=>'','description'=>'Montre élégante en acier inoxydable'],
        ['ref_produit'=>3,'nom'=>'Sac à Dos Design',  'categorie'=>'Sacs',        'prix'=>89.99, 'stock'=>67,'statut'=>'en_stock','image'=>'','description'=>'Sac à dos design et résistant'],
        ['ref_produit'=>4,'nom'=>'Lunettes de Soleil','categorie'=>'Accessoires', 'prix'=>159.99,'stock'=>12,'statut'=>'en_stock','image'=>'','description'=>'Lunettes de soleil polarisées'],
        ['ref_produit'=>5,'nom'=>'Casquette Vintage', 'categorie'=>'Accessoires', 'prix'=>39.99, 'stock'=>89,'statut'=>'en_stock','image'=>'','description'=>'Casquette style vintage 100% coton'],
        ['ref_produit'=>6,'nom'=>'T-Shirt Premium',   'categorie'=>'Vêtements',   'prix'=>49.99, 'stock'=>0, 'statut'=>'rupture', 'image'=>'','description'=>'T-Shirt premium 100% coton bio'],
        ['ref_produit'=>7,'nom'=>'Jean Slim Fit',     'categorie'=>'Vêtements',   'prix'=>79.99, 'stock'=>34,'statut'=>'en_stock','image'=>'','description'=>'Jean slim fit stretch confortable'],
        ['ref_produit'=>8,'nom'=>'Veste en Cuir',     'categorie'=>'Vêtements',   'prix'=>349.99,'stock'=>8, 'statut'=>'en_stock','image'=>'','description'=>'Veste en cuir véritable artisanale'],
    ];
    // Appliquer filtre côté PHP si BDD absente
    if ($categorie_filtre && $categorie_filtre !== 'Tous') {
        $produits = array_filter($produits, fn($p) => $p['categorie'] === $categorie_filtre);
    }
    if ($search) {
        $produits = array_filter($produits, fn($p) =>
            stripos($p['nom'], $search) !== false || stripos($p['description'], $search) !== false
        );
    }
    $produits = array_values($produits);
}

$categories  = ['Tous', 'Vêtements', 'Accessoires', 'Chaussures', 'Sacs'];
$total       = count($produits);

// Couleurs de fond par défaut par catégorie (pour les cartes sans image)
$cat_colors = [
    'Vêtements'  => ['#e040c8','#9c27b0'],
    'Accessoires'=> ['#9c27b0','#4a0080'],
    'Chaussures' => ['#6a0dad','#e040c8'],
    'Sacs'       => ['#c2185b','#9c27b0'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopStyle – Boutique</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:           #0d0f1a;
      --card-bg:      #141728;
      --input-bg:     #1a1f35;
      --border:       #2a2f4a;
      --text:         #e8eaf6;
      --muted:        #8890b0;
      --accent-pink:  #e040c8;
      --accent-purple:#9c27b0;
      --grad:         linear-gradient(90deg, #e040c8, #9c27b0);
      --grad-diag:    linear-gradient(135deg, #e040c8 0%, #9c27b0 50%, #4a0080 100%);
      --green:        #4caf7d;
      --red:          #ef5350;
    }

    html { scroll-behavior: smooth; }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Sora', sans-serif;
      min-height: 100vh;
    }

    /* ══════════════════════════════
       NAVBAR
    ══════════════════════════════ */
    .navbar {
      width: 100%;
      background: rgba(13,15,26,.95);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 80px;
      height: 68px;
      position: sticky;
      top: 0;
      z-index: 999;
    }

    .navbar-brand {
      font-size: 1.4rem;
      font-weight: 800;
      background: var(--grad);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      text-decoration: none;
    }

    .navbar-links {
      display: flex;
      align-items: center;
      gap: 32px;
      list-style: none;
    }

    .navbar-links a {
      color: var(--muted);
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
      transition: color .2s;
    }

    .navbar-links a:hover,
    .navbar-links a.active { color: var(--accent-pink); }

    .navbar-actions { display: flex; align-items: center; gap: 14px; }

    .cart-btn {
      position: relative;
      background: none;
      border: none;
      cursor: pointer;
      color: var(--muted);
      display: flex;
      align-items: center;
      transition: color .2s;
      text-decoration: none;
    }
    .cart-btn:hover { color: var(--accent-pink); }

    .cart-badge {
      position: absolute;
      top: -6px; right: -6px;
      width: 18px; height: 18px;
      border-radius: 50%;
      background: var(--grad);
      color: #fff;
      font-size: 0.65rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .profile-btn {
      width: 38px; height: 38px;
      border-radius: 50%;
      background: var(--grad);
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      text-decoration: none;
      transition: opacity .2s;
    }
    .profile-btn:hover { opacity: .85; }

    @media (max-width: 768px) {
      .navbar { padding: 0 20px; }
      .navbar-links { display: none; }
    }

    /* ══════════════════════════════
       HERO BOUTIQUE
    ══════════════════════════════ */
    .boutique-hero {
      background: var(--grad-diag);
      padding: 56px 80px 48px;
      position: relative;
      overflow: hidden;
    }

    .boutique-hero::before {
      content: '';
      position: absolute;
      width: 500px; height: 500px;
      border-radius: 50%;
      background: rgba(255,255,255,.04);
      top: -150px; right: -100px;
      pointer-events: none;
    }

    .boutique-hero::after {
      content: '';
      position: absolute;
      width: 300px; height: 300px;
      border-radius: 50%;
      background: rgba(255,255,255,.03);
      bottom: -100px; left: 200px;
      pointer-events: none;
    }

    .hero-top {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 20px;
      position: relative;
      z-index: 1;
    }

    .hero-title h1 {
      font-size: 2.8rem;
      font-weight: 900;
      color: #fff;
      line-height: 1.1;
      letter-spacing: -0.02em;
    }

    .hero-title h1 span {
      display: block;
      font-weight: 300;
      font-size: 1.1rem;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: rgba(255,255,255,.7);
      margin-bottom: 8px;
    }

    .hero-count {
      background: rgba(255,255,255,.15);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,.2);
      border-radius: 12px;
      padding: 16px 24px;
      text-align: center;
    }

    .hero-count p:first-child {
      font-size: 2rem;
      font-weight: 800;
      color: #fff;
    }

    .hero-count p:last-child {
      font-size: 0.8rem;
      color: rgba(255,255,255,.7);
      margin-top: 2px;
    }

    /* ══════════════════════════════
       SEARCH + FILTERS
    ══════════════════════════════ */
    .controls {
      padding: 32px 80px 0;
      background: var(--bg);
    }

    .controls-row {
      display: flex;
      align-items: center;
      gap: 16px;
      flex-wrap: wrap;
    }

    /* Recherche */
    .search-box {
      flex: 1;
      min-width: 260px;
      position: relative;
    }

    .search-box svg {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
    }

    .search-box input {
      width: 100%;
      background: var(--card-bg);
      border: 1.5px solid var(--border);
      border-radius: 12px;
      padding: 13px 14px 13px 44px;
      font-family: inherit;
      font-size: 0.9rem;
      color: var(--text);
      outline: none;
      transition: border-color .2s;
    }

    .search-box input::placeholder { color: var(--muted); }
    .search-box input:focus { border-color: var(--accent-purple); }

    /* Tri */
    .sort-wrap {
      position: relative;
    }

    .sort-wrap select {
      appearance: none;
      background: var(--card-bg);
      border: 1.5px solid var(--border);
      border-radius: 12px;
      padding: 13px 40px 13px 16px;
      font-family: inherit;
      font-size: 0.88rem;
      color: var(--text);
      outline: none;
      cursor: pointer;
      transition: border-color .2s;
    }

    .sort-wrap select:focus { border-color: var(--accent-purple); }

    .sort-wrap svg {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
      pointer-events: none;
    }

    /* Filtres catégorie */
    .cat-filters {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      padding: 20px 80px 28px;
      background: var(--bg);
    }

    .cat-btn {
      padding: 9px 22px;
      border-radius: 100px;
      border: 1.5px solid var(--border);
      background: transparent;
      color: var(--muted);
      font-family: inherit;
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      transition: all .25s;
      text-decoration: none;
      display: inline-block;
    }

    .cat-btn:hover {
      border-color: var(--accent-pink);
      color: var(--accent-pink);
    }

    .cat-btn.active {
      background: var(--grad);
      border-color: transparent;
      color: #fff;
    }

    /* Séparateur résultats */
    .results-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 80px 24px;
      background: var(--bg);
    }

    .results-bar p {
      font-size: 0.85rem;
      color: var(--muted);
    }

    .results-bar p strong { color: var(--text); }

    /* ══════════════════════════════
       GRILLE PRODUITS
    ══════════════════════════════ */
    .products-section {
      padding: 0 80px 80px;
      background: var(--bg);
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 22px;
    }

    /* CARTE PRODUIT */
    .card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 18px;
      overflow: hidden;
      transition: transform .25s, box-shadow .25s, border-color .25s;
      position: relative;
      cursor: pointer;
    }

    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 20px 48px rgba(0,0,0,.5);
      border-color: rgba(224,64,200,.3);
    }

    /* Image produit */
    .card-img {
      width: 100%;
      aspect-ratio: 4/3;
      position: relative;
      overflow: hidden;
    }

    .card-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform .4s;
    }

    .card:hover .card-img img { transform: scale(1.06); }

    /* Placeholder si pas d'image */
    .card-img-placeholder {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.8rem;
      position: relative;
    }

    .card-img-placeholder::after {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,.15);
    }

    /* Badge rupture */
    .badge-rupture {
      position: absolute;
      top: 12px;
      left: 12px;
      background: rgba(239,83,80,.9);
      color: #fff;
      font-size: 0.7rem;
      font-weight: 700;
      padding: 4px 10px;
      border-radius: 6px;
      z-index: 2;
      backdrop-filter: blur(4px);
    }

    /* Badge nouveau */
    .badge-new {
      position: absolute;
      top: 12px;
      left: 12px;
      background: var(--grad);
      color: #fff;
      font-size: 0.7rem;
      font-weight: 700;
      padding: 4px 10px;
      border-radius: 6px;
      z-index: 2;
    }

    /* Bouton favori */
    .btn-fav {
      position: absolute;
      top: 12px;
      right: 12px;
      width: 32px; height: 32px;
      border-radius: 50%;
      background: rgba(13,15,26,.7);
      backdrop-filter: blur(6px);
      border: 1px solid rgba(255,255,255,.1);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: var(--muted);
      z-index: 2;
      transition: all .2s;
    }

    .btn-fav:hover,
    .btn-fav.liked { color: #e040c8; border-color: #e040c8; }

    /* Bouton ajouter au panier (apparaît au hover) */
    .btn-cart-overlay {
      position: absolute;
      bottom: 0; left: 0; right: 0;
      background: var(--grad);
      color: #fff;
      border: none;
      padding: 12px;
      font-family: inherit;
      font-size: 0.83rem;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      opacity: 0;
      transform: translateY(100%);
      transition: opacity .25s, transform .25s;
      z-index: 2;
    }

    .card:hover .btn-cart-overlay {
      opacity: 1;
      transform: translateY(0);
    }

    .card-body {
      padding: 16px 18px 20px;
    }

    .card-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 6px;
    }

    .card-cat {
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .06em;
      color: var(--accent-pink);
    }

    .card-stock {
      font-size: 0.72rem;
      color: var(--muted);
    }

    .card-stock.low { color: #fbbf24; }

    .card-name {
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 10px;
      line-height: 1.3;
    }

    .card-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .card-price {
      font-size: 1.1rem;
      font-weight: 800;
      background: var(--grad);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .card-price.rupture {
      background: none;
      -webkit-text-fill-color: var(--muted);
      color: var(--muted);
      text-decoration: line-through;
      font-size: 0.9rem;
    }

    .btn-add-small {
      width: 32px; height: 32px;
      border-radius: 8px;
      background: var(--grad);
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      transition: opacity .2s;
      flex-shrink: 0;
    }

    .btn-add-small:hover { opacity: .85; }
    .btn-add-small:disabled { opacity: .35; cursor: not-allowed; }

    /* ── EMPTY STATE ── */
    .empty-state {
      grid-column: 1 / -1;
      text-align: center;
      padding: 80px 20px;
    }

    .empty-icon {
      font-size: 4rem;
      margin-bottom: 20px;
      display: block;
    }

    .empty-state h3 {
      font-size: 1.4rem;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .empty-state p {
      color: var(--muted);
      margin-bottom: 28px;
    }

    .btn-reset {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 28px;
      background: var(--grad);
      border: none;
      border-radius: 10px;
      color: #fff;
      font-family: inherit;
      font-size: 0.9rem;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      transition: opacity .2s;
    }

    .btn-reset:hover { opacity: .88; }

    /* ══════════════════════════════
       MODAL PRODUIT
    ══════════════════════════════ */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.8);
      backdrop-filter: blur(6px);
      z-index: 2000;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .modal-overlay.open { display: flex; }

    .modal {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 20px;
      max-width: 680px;
      width: 100%;
      position: relative;
      overflow: hidden;
      animation: slideUp .3s ease;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .modal-close {
      position: absolute;
      top: 16px; right: 16px;
      width: 36px; height: 36px;
      border-radius: 50%;
      background: rgba(255,255,255,.08);
      border: 1px solid var(--border);
      color: var(--muted);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10;
      transition: all .2s;
    }

    .modal-close:hover { background: rgba(239,83,80,.15); color: #ef5350; }

    .modal-inner {
      display: grid;
      grid-template-columns: 1fr 1fr;
    }

    .modal-img {
      aspect-ratio: 1;
      background: var(--input-bg);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 5rem;
      position: relative;
    }

    .modal-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .modal-body {
      padding: 32px 28px;
      display: flex;
      flex-direction: column;
    }

    .modal-cat {
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: var(--accent-pink);
      margin-bottom: 10px;
    }

    .modal-name {
      font-size: 1.4rem;
      font-weight: 800;
      color: var(--text);
      margin-bottom: 12px;
      line-height: 1.2;
    }

    .modal-price {
      font-size: 1.8rem;
      font-weight: 900;
      background: var(--grad);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 16px;
    }

    .modal-desc {
      font-size: 0.85rem;
      color: var(--muted);
      line-height: 1.7;
      margin-bottom: 24px;
      flex: 1;
    }

    .modal-stock {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.82rem;
      margin-bottom: 20px;
    }

    .stock-dot {
      width: 8px; height: 8px;
      border-radius: 50%;
    }

    .stock-dot.green { background: var(--green); }
    .stock-dot.red   { background: var(--red); }
    .stock-dot.orange{ background: #fbbf24; }

    .modal-btn {
      width: 100%;
      padding: 14px;
      background: var(--grad);
      border: none;
      border-radius: 12px;
      color: #fff;
      font-family: inherit;
      font-size: 0.95rem;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: opacity .2s;
    }

    .modal-btn:hover { opacity: .88; }
    .modal-btn:disabled { opacity: .4; cursor: not-allowed; }

    /* ══════════════════════════════
       FOOTER
    ══════════════════════════════ */
    footer {
      background: #0a0b14;
      border-top: 1px solid var(--border);
      padding: 48px 80px 28px;
    }

    .footer-bottom {
      text-align: center;
      font-size: 0.82rem;
      color: var(--muted);
    }

    /* ══════════════════════════════
       TOAST
    ══════════════════════════════ */
    .toast {
      position: fixed;
      bottom: 28px;
      right: 28px;
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-left: 3px solid var(--accent-pink);
      border-radius: 12px;
      padding: 14px 20px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 0.87rem;
      font-weight: 500;
      z-index: 9999;
      box-shadow: 0 12px 32px rgba(0,0,0,.5);
      transform: translateX(120%);
      transition: transform .3s cubic-bezier(.34,1.56,.64,1);
    }

    .toast.show { transform: translateX(0); }
    .toast svg  { color: var(--accent-pink); flex-shrink: 0; }

    /* ══════════════════════════════
       RESPONSIVE
    ══════════════════════════════ */
    @media (max-width: 1200px) {
      .grid { grid-template-columns: repeat(3, 1fr); }
    }

    @media (max-width: 900px) {
      .boutique-hero { padding: 40px 32px; }
      .controls      { padding: 24px 32px 0; }
      .cat-filters   { padding: 16px 32px 20px; }
      .results-bar   { padding: 0 32px 18px; }
      .products-section { padding: 0 32px 60px; }
      .grid { grid-template-columns: repeat(2, 1fr); }
      .modal-inner { grid-template-columns: 1fr; }
      .modal-img   { display: none; }
      footer { padding: 32px; }
    }

    @media (max-width: 600px) {
      .boutique-hero { padding: 32px 20px; }
      .hero-title h1 { font-size: 2rem; }
      .controls      { padding: 20px 20px 0; }
      .cat-filters   { padding: 14px 20px 18px; }
      .results-bar   { padding: 0 20px 16px; }
      .products-section { padding: 0 20px 48px; }
      .grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
      .card-body { padding: 12px 14px 16px; }
      footer { padding: 24px 20px; }
      .navbar { padding: 0 20px; }
    }
  </style>
</head>
<body>

<!-- ════ NAVBAR ════ -->
<nav class="navbar">
  <a href="index.php" class="navbar-brand">ShopStyle</a>
  <ul class="navbar-links">
    <li><a href="index.php">Accueil</a></li>
    <li><a href="boutique.php" class="active">Boutique</a></li>
    <li><a href="#">Nouveautés</a></li>
    <li><a href="#">Promotions</a></li>
  </ul>
  <div class="navbar-actions">
    <a href="panier/affiche_panier.php" class="cart-btn" title="Mon panier">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
      </svg>
      <span class="cart-badge" id="cartCount">0</span>
    </a>
    <?php if (isset($_SESSION['ref_uti']) && $_SESSION['role'] === 'client'): ?>
      <a href="#" class="profile-btn" style="font-size:.9rem;font-weight:700;">
        <?= strtoupper(substr($_SESSION['nom'], 0, 1)) ?>
      </a>
    <?php else: ?>
      <a href="Authentification/connect_client.php" class="profile-btn" title="Connexion">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
        </svg>
      </a>
    <?php endif; ?>
  </div>
</nav>

<!-- ════ HERO ════ -->
<div class="boutique-hero">
  <div class="hero-top">
    <div class="hero-title">
      <h1>
        <span>Notre collection</span>
        Tous les produits
      </h1>
    </div>
    <div class="hero-count">
      <p id="heroCount"><?= $total ?></p>
      <p>articles disponibles</p>
    </div>
  </div>
</div>

<!-- ════ CONTRÔLES ════ -->
<div class="controls">
  <div class="controls-row">
    <!-- Recherche -->
    <form method="GET" action="boutique.php" class="search-box">
      <?php if ($categorie_filtre): ?>
        <input type="hidden" name="cat" value="<?= htmlspecialchars($categorie_filtre) ?>" />
      <?php endif; ?>
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
      </svg>
      <input type="text" name="q" placeholder="Rechercher un produit..."
             value="<?= htmlspecialchars($search) ?>" />
    </form>

    <!-- Tri JS côté client -->
    <div class="sort-wrap">
      <select id="sortSelect" onchange="sortProducts()">
        <option value="default">Trier par : Défaut</option>
        <option value="price-asc">Prix croissant</option>
        <option value="price-desc">Prix décroissant</option>
        <option value="name-asc">Nom A → Z</option>
        <option value="name-desc">Nom Z → A</option>
      </select>
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
    </div>
  </div>
</div>

<!-- Filtres catégories -->
<div class="cat-filters">
  <?php foreach ($categories as $cat):
    $active = ($cat === 'Tous' && !$categorie_filtre) || $cat === $categorie_filtre ? 'active' : '';
    $href   = $cat === 'Tous' ? 'boutique.php' : 'boutique.php?cat=' . urlencode($cat);
    if ($search) $href .= ($cat === 'Tous' ? '?' : '&') . 'q=' . urlencode($search);
  ?>
    <a href="<?= $href ?>" class="cat-btn <?= $active ?>"><?= $cat ?></a>
  <?php endforeach; ?>
</div>

<!-- Barre résultats -->
<div class="results-bar">
  <p id="resultCount">
    <strong><?= $total ?></strong> produit<?= $total > 1 ? 's' : '' ?>
    <?= $categorie_filtre ? ' dans <strong>' . htmlspecialchars($categorie_filtre) . '</strong>' : '' ?>
    <?= $search ? ' pour <strong>"' . htmlspecialchars($search) . '"</strong>' : '' ?>
  </p>
</div>

<!-- ════ GRILLE ════ -->
<section class="products-section">
  <div class="grid" id="productsGrid">

    <?php if (empty($produits)): ?>
      <div class="empty-state">
        <span class="empty-icon">🔍</span>
        <h3>Aucun produit trouvé</h3>
        <p>Essayez une autre recherche ou une autre catégorie.</p>
        <a href="boutique.php" class="btn-reset">
          Voir tous les produits
        </a>
      </div>

    <?php else:
      $emojis = ['Vêtements'=>'👕','Accessoires'=>'⌚','Chaussures'=>'👟','Sacs'=>'👜'];
      foreach ($produits as $i => $p):
        $colors  = $cat_colors[$p['categorie']] ?? ['#e040c8','#9c27b0'];
        $emoji   = $emojis[$p['categorie']] ?? '🛍️';
        $rupture = $p['statut'] === 'rupture' || $p['stock'] == 0;
        $low     = !$rupture && $p['stock'] > 0 && $p['stock'] <= 10;
        $isNew   = $i < 2; // les 2 premiers = "Nouveau"
        $img     = !empty($p['image']) ? 'image/' . $p['image'] : '';
    ?>
      <div class="card" data-price="<?= $p['prix'] ?>" data-name="<?= htmlspecialchars($p['nom']) ?>"
           onclick="openModal(<?= htmlspecialchars(json_encode($p)) ?>)">

        <!-- IMAGE -->
        <div class="card-img">
          <?php if ($img): ?>
            <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['nom']) ?>" />
          <?php else: ?>
            <div class="card-img-placeholder"
                 style="background: linear-gradient(135deg, <?= $colors[0] ?>, <?= $colors[1] ?>)">
              <?= $emoji ?>
            </div>
          <?php endif; ?>

          <!-- Badges -->
          <?php if ($rupture): ?>
            <span class="badge-rupture">Rupture</span>
          <?php elseif ($isNew): ?>
            <span class="badge-new">✨ Nouveau</span>
          <?php endif; ?>

          <!-- Favori -->
          <button class="btn-fav" onclick="toggleFav(event, this)" title="Ajouter aux favoris">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
          </button>

          <!-- Ajouter au panier (overlay hover) -->
          <?php if (!$rupture): ?>
            <button class="btn-cart-overlay" onclick="addToCart(event, <?= $p['ref_produit'] ?>, '<?= htmlspecialchars($p['nom'], ENT_QUOTES) ?>', <?= $p['prix'] ?>)">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
              </svg>
              Ajouter au panier
            </button>
          <?php endif; ?>
        </div>

        <!-- BODY -->
        <div class="card-body">
          <div class="card-meta">
            <span class="card-cat"><?= htmlspecialchars($p['categorie']) ?></span>
            <?php if ($rupture): ?>
              <span class="card-stock" style="color:var(--red)">Indisponible</span>
            <?php elseif ($low): ?>
              <span class="card-stock low">⚠ <?= $p['stock'] ?> restants</span>
            <?php else: ?>
              <span class="card-stock">En stock</span>
            <?php endif; ?>
          </div>

          <p class="card-name"><?= htmlspecialchars($p['nom']) ?></p>

          <div class="card-footer">
            <span class="card-price <?= $rupture ? 'rupture' : '' ?>">
              <?= number_format($p['prix'], 2, ',', ' ') ?>&nbsp;€
            </span>
            <button class="btn-add-small"
                    <?= $rupture ? 'disabled' : '' ?>
                    onclick="addToCart(event, <?= $p['ref_produit'] ?>, '<?= htmlspecialchars($p['nom'], ENT_QUOTES) ?>', <?= $p['prix'] ?>)"
                    title="Ajouter au panier">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
              </svg>
            </button>
          </div>
        </div>

      </div>
    <?php endforeach; endif; ?>

  </div>
</section>

<!-- ════ FOOTER ════ -->
<footer>
  <div class="footer-bottom">© 2026 ShopStyle. Tous droits réservés.</div>
</footer>

<!-- ════ MODAL DÉTAIL ════ -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModalOutside(event)">
  <div class="modal">
    <button class="modal-close" onclick="closeModal()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <div class="modal-inner">
      <div class="modal-img" id="modalImg"></div>
      <div class="modal-body">
        <p class="modal-cat" id="modalCat"></p>
        <h2 class="modal-name" id="modalName"></h2>
        <p class="modal-price" id="modalPrice"></p>
        <p class="modal-desc" id="modalDesc"></p>
        <div class="modal-stock" id="modalStock"></div>
        <button class="modal-btn" id="modalCartBtn">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
          </svg>
          Ajouter au panier
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ════ TOAST ════ -->
<div class="toast" id="toast">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
  <span id="toastMsg">Produit ajouté au panier !</span>
</div>

<script>
  const catColors = <?= json_encode($cat_colors) ?>;
  const emojis    = {"Vêtements":"👕","Accessoires":"⌚","Chaussures":"👟","Sacs":"👜"};

  // ── MODAL ──
  function openModal(p) {
    const rupture = p.statut === 'rupture' || parseInt(p.stock) === 0;
    const low     = !rupture && parseInt(p.stock) <= 10 && parseInt(p.stock) > 0;
    const colors  = catColors[p.categorie] || ['#e040c8','#9c27b0'];
    const emoji   = emojis[p.categorie]    || '🛍️';

    // Image
    const imgEl = document.getElementById('modalImg');
    if (p.image) {
      imgEl.innerHTML = `<img src="image/${p.image}" alt="${p.nom}" style="width:100%;height:100%;object-fit:cover;">`;
    } else {
      imgEl.innerHTML = emoji;
      imgEl.style.background = `linear-gradient(135deg,${colors[0]},${colors[1]})`;
      imgEl.style.fontSize   = '5rem';
    }

    document.getElementById('modalCat').textContent  = p.categorie;
    document.getElementById('modalName').textContent = p.nom;
    document.getElementById('modalPrice').textContent = parseFloat(p.prix).toFixed(2).replace('.', ',') + ' €';
    document.getElementById('modalDesc').textContent  = p.description || 'Aucune description disponible.';

    // Stock
    const stockEl = document.getElementById('modalStock');
    if (rupture) {
      stockEl.innerHTML = `<span class="stock-dot red"></span> Rupture de stock`;
    } else if (low) {
      stockEl.innerHTML = `<span class="stock-dot orange"></span> Stock faible — ${p.stock} restant(s)`;
    } else {
      stockEl.innerHTML = `<span class="stock-dot green"></span> En stock (${p.stock} disponibles)`;
    }

    // Bouton
    const btn = document.getElementById('modalCartBtn');
    btn.disabled = rupture;
    btn.innerHTML = rupture
      ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg> Indisponible'
      : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg> Ajouter au panier';
    if (!rupture) {
      btn.onclick = () => { addToCart(null, p.ref_produit, p.nom, p.prix); closeModal(); };
    }

    document.getElementById('modalOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    document.getElementById('modalOverlay').classList.remove('open');
    document.body.style.overflow = '';
  }

  function closeModalOutside(e) {
    if (e.target === document.getElementById('modalOverlay')) closeModal();
  }

  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

  // ── PANIER ──
  let cartCount = 0;

  function addToCart(e, id, nom, prix) {
    if (e) e.stopPropagation();
    cartCount++;
    document.getElementById('cartCount').textContent = cartCount;
    showToast(`"${nom}" ajouté au panier !`);

    // Envoyer vers le serveur (optionnel)
    fetch('panier/add_to_cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `ref_produit=${id}&quantite=1`
    }).catch(() => {}); // silencieux si page pas encore créée
  }

  // ── TOAST ──
  function showToast(msg) {
    const t = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
  }

  // ── FAVORIS ──
  function toggleFav(e, btn) {
    e.stopPropagation();
    btn.classList.toggle('liked');
    btn.innerHTML = btn.classList.contains('liked')
      ? '<svg width="14" height="14" viewBox="0 0 24 24" fill="#e040c8" stroke="#e040c8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>'
      : '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>';
  }

  // ── TRI JS côté client ──
  function sortProducts() {
    const val  = document.getElementById('sortSelect').value;
    const grid = document.getElementById('productsGrid');
    const cards = [...grid.querySelectorAll('.card')];

    cards.sort((a, b) => {
      if (val === 'price-asc')  return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
      if (val === 'price-desc') return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
      if (val === 'name-asc')   return a.dataset.name.localeCompare(b.dataset.name, 'fr');
      if (val === 'name-desc')  return b.dataset.name.localeCompare(a.dataset.name, 'fr');
      return 0;
    });

    cards.forEach(c => grid.appendChild(c));
  }
</script>
</body>
</html>