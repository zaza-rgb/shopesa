<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopStyle – Accueil</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/index.css" />
  <style>
    /* ── CARD ACTIONS ── */
    .card {
      position: relative;
      overflow: hidden;
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
      text-decoration: none;
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
      text-decoration: none;
      white-space: nowrap;
    }

    .btn-details:hover {
      border-color: #e040c8;
      color: #e040c8;
    }
  </style>
</head>
<body>
  <?php include 'include/header.php';?>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-content">
      <h1>Besoin d'article de toute urgence !</h1>
      <p>Bingo !, vous êtes au bon endroit, Découvrez les dernières tendances mode</p>
      <a href="#" class="btn-hero">Découvrir maintenant</a>
    </div>
  </section>

  <!-- PRODUCTS -->
  <section class="products">
    <div class="products-header">
      <h2>Produits populaires</h2>
      <div class="filters">
        <form method="post" action="index.php">
        <button type="submit" class="filter-btn active">Tous</button>
        <?php
       require 'liaison.php'; 
      try{
         $stmt = $com->query("SELECT nom FROM categorie ");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              ?>
              <button type="submit" name="cate_retour" value="<?php echo $row['nom']; ?>" class="filter-btn">
                <?php echo $row['nom']; ?>
              </button>
              <?php
                }
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
            ?>
            </form>
      </div>
    </div>

    <div class="grid">

      <!-- Card -->
       <?php
       require 'liaison.php'; 
      try{
        if (isset($_POST['cate_retour']) && ($_SERVER["REQUEST_METHOD"] == "POST")){
          $cate=$_POST['cate_retour'];
          $stmt = $com->prepare("SELECT * FROM produit RIGHT JOIN categorie ON produit.id_cat = categorie.id_cat WHERE categorie.nom = ? ");
          $stmt->execute([$cate]);
        }else {
            $stmt = $com->query("SELECT * FROM produit RIGHT JOIN categorie ON produit.id_cat = categorie.id_cat ");
        }
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              ?>
            <form method="post" action="panier/selectSQL.php">
           <div class="card">
        <div class="card-img">
          <img src="image/<?php echo htmlspecialchars($row['image']); ?>" alt="" width="100%">
        </div>
        <div class="card-body">
          <input type="hidden" name="qte" value="1" min="1">
          <input type="hidden" name="idprod" value="<?php echo $row['idprod']; ?>">
          <p class="card-cat" ><?php echo $row['nom']?></p>
          <p class="card-name" ><?php echo $row['nomprod']?></p>
          <p class="card-price" ><?php echo $row['prix']?>FCFA</p>
          <div class="card-actions">
            <button class="btn-cart" name="ajouter" formaction="panier/affiche_panier.php">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
              </svg>
              Ajouter
            </button>
            <button class="btn-details" formaction="panier/detai.php">
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
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
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
          <button>OK</button>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      © 2026 ShopStyle. Tous droits réservés.
    </div>
  </footer>

  <script>
    document.querySelectorAll('.filter-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
      });
    });
  </script>
</body>
</html>