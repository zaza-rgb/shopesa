<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopStyle – Tous les produits</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/index.css" />
  <style>
    .page-banner {
      width: 100%;
      min-height: 180px;
      background: linear-gradient(135deg, #e040c8 0%, #9c27b0 50%, #6a0dad 100%);
      display: flex;
      align-items: center;
      padding: 48px 80px;
    }

    .page-banner h1 {
      font-size: 2.2rem;
      font-weight: 800;
      color: #fff;
    }

    .page-banner p {
      font-size: 0.95rem;
      color: rgba(255,255,255,.8);
      margin-top: 8px;
    }

    .card-actions {
      display: flex;
      gap: 8px;
      margin-top: 12px;
    }

    .btn-cart {
      flex: 1;
      padding: 10px 8px;
      background: linear-gradient(90deg, #e040c8, #9c27b0);
      border: none;
      border-radius: 8px;
      color: #fff;
      font-family: 'Sora', sans-serif;
      font-size: 0.78rem;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      transition: opacity .2s, transform .1s;
    }

    .btn-cart:hover  { opacity: .88; }
    .btn-cart:active { transform: scale(.97); }

    .btn-details {
      padding: 10px 12px;
      background: transparent;
      border: 1.5px solid #2a2f4a;
      border-radius: 8px;
      color: #e8eaf6;
      font-family: 'Sora', sans-serif;
      font-size: 0.78rem;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      transition: border-color .2s, color .2s;
      white-space: nowrap;
    }

    .btn-details:hover {
      border-color: #e040c8;
      color: #e040c8;
    }

    .filters {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .filter-btn {
      padding: 9px 20px;
      border-radius: 8px;
      border: 1.5px solid #2a2f4a;
      background: transparent;
      color: #e8eaf6;
      font-family: 'Sora', sans-serif;
      font-size: 0.85rem;
      font-weight: 500;
      cursor: pointer;
      transition: all .2s;
      text-decoration: none;
    }

    .filter-btn.active,
    .filter-btn:hover {
      background: linear-gradient(90deg, #e040c8, #9c27b0);
      border-color: transparent;
      color: #fff;
    }
  </style>
</head>
<body>
  <?php include '../include/header.php'; ?>

  <!-- BANNER -->
  <section class="page-banner">
    <div>
      <h1>Tous les produits</h1>
      <p>Découvrez l'ensemble de notre catalogue</p>
    </div>
  </section>

  <!-- PRODUCTS -->
  <section class="products">
    <div class="products-header">
      <h2>Tous les produits</h2>
      <div class="filters">
        <a href="tous.php" class="filter-btn active">Tous</a>
        <a href="vetements.php" class="filter-btn">Vêtements</a>
        <a href="accessoires.php" class="filter-btn">Accessoires</a>
        <a href="chaussures.php" class="filter-btn">Chaussures</a>
      </div>
    </div>

    <div class="grid">
      <?php
        require '../liaison.php';
        try {
          $stmt = $pdo->query("SELECT * FROM produit LEFT JOIN categorie ON produit.id_cat = categorie.id_cat WHERE produit.statut = 'actif'");
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      ?>
        <form method="post" action="../panier/selectSQL.php">
          <div class="card">
            <div class="card-img">
              <img src="../image/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['nomprod']); ?>" width="100%">
            </div>
            <div class="card-body">
              <input type="hidden" name="qte" value="1">
              <input type="hidden" name="idprod" value="<?php echo $row['idprod']; ?>">
              <p class="card-cat"><?php echo htmlspecialchars($row['nom'] ?? 'Sans catégorie'); ?></p>
              <p class="card-name"><?php echo htmlspecialchars($row['nomprod']); ?></p>
              <p class="card-price"><?php echo number_format($row['prix'], 0, ',', ' '); ?> FCFA</p>
              <div class="card-actions">
                <button class="btn-cart" name="ajouter" formaction="../panier/affiche_panier.php">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                  </svg>
                  Ajouter
                </button>
                <button class="btn-details" formaction="../panier/detai.php">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                  </svg>
                  Détails
                </button>
              </div>
            </div>
          </div>
        </form>
      <?php
          }
        } catch (PDOException $a) {
          echo "<p style='color:red;padding:20px'>Erreur : " . $e->getMessage() . "</p>";
        }
      ?>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-top">
      <div class="footer-brand">
        <span class="brand-name">ShopStyle</span>
        <p>Votre destination mode en ligne</p>
      </div>
      <div class="footer-col">
        <h4>Boutique</h4>
        <ul>
          <li><a href="#">Nouveautés</a></li>
          <li><a href="#">Promotions</a></li>
          <li><a href="#">Collections</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Service client</h4>
        <ul>
          <li><a href="#">Contact</a></li>
          <li><a href="#">Livraison</a></li>
          <li><a href="#">Retours</a></li>
        </ul>
      </div>
      <div class="footer-newsletter">
        <h4>Newsletter</h4>
        <p>Restez informé des nouveautés</p>
        <div class="newsletter-row">
          <input type="email" placeholder="Email" />
          <button type="button">OK</button>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      © 2026 ShopStyle. Tous droits réservés.
    </div>
  </footer>
</body>
</html>