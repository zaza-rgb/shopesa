<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopStyle – Accueil</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
 
  <link rel="stylesheet" href="css/index.css" />
</head>
<body>
  <?php include 'include/header.php'; ?>
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
        <a href="" class="filter-btn active">Tous</a>
        <a href="" class="filter-btn">Vêtements</a>
        <a href="" class="filter-btn" >Accessoires</a>   
        <a href=""class="filter-btn">Chaussures></a> 
      </div>
    </div>

    <div class="grid">
      <div class="card">
        <div class="card-img"></div>
        <div class="card-body">
          <p class="card-cat">Accessoires</p>
          <p class="card-name">Casquette Vintage</p>
          <p class="card-price">39.99€</p>
        </div>
      </div>
      <div class="card">
        <div class="card-img"></div>
        <div class="card-body">
          <p class="card-cat">Vêtements</p>
          <p class="card-name">T-Shirt Premium</p>
          <p class="card-price">49.99€</p>
        </div>
      </div>
      <div class="card">
        <div class="card-img"></div>
        <div class="card-body">
          <p class="card-cat">Vêtements</p>
          <p class="card-name">Jean Slim Fit</p>
          <p class="card-price">79.99€</p>
        </div>
      </div>
      <div class="card">
        <div class="card-img"></div>
        <div class="card-body">
          <p class="card-cat">Vêtements</p>
          <p class="card-name">Veste en Cuir</p>
          <p class="card-price">349.99€</p>
        </div>
      </div>
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