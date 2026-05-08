<?php
session_start();
       require '../liaison.php';
       if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $ref_uti=5;
}
if (isset($_SESSION['ref_uti'])){
  $ref_uti=$_SESSION['ref_uti'];
}else{
  $ref_uti=0;
}
        
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopStyle – Montre Élégante</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
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
      --yellow:       #fbbf24;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Sora', sans-serif;
      min-height: 100vh;
    }

    /* ── TOPBAR ── */
    .topbar {
      background: var(--card-bg);
      border-bottom: 1px solid var(--border);
      padding: 0 48px;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .back-link {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.88rem;
      color: var(--text);
      text-decoration: none;
      transition: color .2s;
    }

    .back-link:hover { color: var(--accent-pink); }

    .topbar-logo {
      font-size: 1.4rem;
      font-weight: 700;
      background: var(--grad);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      text-decoration: none;
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
    }

    .topbar-cart {
      position: relative;
      color: var(--text);
      text-decoration: none;
      display: flex;
      align-items: center;
      transition: color .2s;
    }

    .topbar-cart:hover { color: var(--accent-pink); }

    .badge {
      position: absolute;
      top: -6px;
      right: -8px;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: var(--grad);
      color: #fff;
      font-size: 0.6rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* ── PAGE ── */
    .page {
      max-width: 1280px;
      margin: 0 auto;
      padding: 48px 48px 80px;
    }

    /* ── PRODUCT LAYOUT ── */
    .product-layout {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      margin-bottom: 80px;
    }

    /* ── LEFT: Images ── */
    .product-images {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
}

.main-img img {
  width: 200%;
  max-width: 500px;   /* limite la taille */
  height: auto;       /* garde les proportions */
  border-radius: 12px;
  object-fit: contain; /* ou cover selon rendu voulu */
  border: 1px solid var(--border);
  background: var(--card-bg);
}


    .wishlist-btn {
      position: absolute;
      top: 16px;
      right: 16px;
      width: 38px;
      height: 38px;
      background: var(--input-bg);
      border: 1px solid var(--border);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      color: var(--muted);
      transition: color .2s, border-color .2s;
    }

    .wishlist-btn:hover { color: var(--accent-pink); border-color: var(--accent-pink); }

    .thumb-row {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
    }

    .thumb {
      aspect-ratio: 1;
      background: var(--card-bg);
      border: 1.5px solid var(--border);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.7rem;
      color: var(--muted);
      cursor: pointer;
      transition: border-color .2s;
      text-decoration: none;
    }

    .thumb:hover,
    .thumb.active { border-color: var(--accent-pink); }

    /* ── RIGHT: Info ── */
    .product-info { display: flex; flex-direction: column; gap: 20px; }

    .product-cat {
      font-size: 0.82rem;
      color: var(--accent-pink);
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .product-name {
      font-size: 2rem;
      font-weight: 800;
      line-height: 1.15;
    }

    /* Stars */
    .stars-row {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .stars { display: flex; gap: 3px; }

    .star {
      color: var(--yellow);
      font-size: 1.1rem;
    }

    .reviews {
      font-size: 0.83rem;
      color: var(--muted);
    }

    .product-price {
      font-size: 2rem;
      font-weight: 800;
      color: var(--accent-pink);
    }

    .product-desc {
      font-size: 0.88rem;
      color: var(--muted);
      line-height: 1.7;
    }

    /* Options label */
    .opt-label {
      font-size: 0.9rem;
      font-weight: 700;
      margin-bottom: 10px;
    }

    /* Size buttons */
    .size-row { display: flex; gap: 8px; flex-wrap: wrap; }

    .size-btn {
      min-width: 48px;
      padding: 10px 14px;
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: 8px;
      color: var(--text);
      font-family: inherit;
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      transition: all .2s;
      text-align: center;
    }

    .size-btn:hover { border-color: var(--accent-purple); color: var(--accent-pink); }

    .size-btn.active {
      background: var(--grad);
      border-color: transparent;
      color: #fff;
    }

    /* Color buttons */
    .color-row { display: flex; gap: 8px; flex-wrap: wrap; }

    .color-btn {
      padding: 10px 18px;
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: 8px;
      color: var(--text);
      font-family: inherit;
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      transition: all .2s;
    }

    .color-btn:hover { border-color: var(--accent-purple); color: var(--accent-pink); }

    .color-btn.active {
      background: var(--grad);
      border-color: transparent;
      color: #fff;
    }

    /* Quantity row */
    .qty-wrap { display: flex; align-items: center; gap: 16px; }

    .qty {
      display: flex;
      align-items: center;
      background: var(--input-bg);
      border: 1px solid var(--border);
      border-radius: 8px;
      overflow: hidden;
    }

    .qty-btn {
      width: 40px;
      height: 40px;
      background: none;
      border: none;
      color: var(--text);
      font-size: 1.2rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background .2s;
    }

    .qty-btn:hover { background: var(--border); }

    .qty-val {
      width: 40px;
      text-align: center;
      font-size: 0.95rem;
      font-weight: 700;
      border-left: 1px solid var(--border);
      border-right: 1px solid var(--border);
      line-height: 40px;
    }

    .stock-badge {
      font-size: 0.82rem;
      color: #4caf7d;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .stock-badge::before {
      content: '';
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #4caf7d;
      display: inline-block;
    }

    /* Add to cart row */
    .cart-row { display: flex; gap: 10px; }

    .btn-add-cart {
      flex: 1;
      padding: 15px;
      background: var(--grad);
      border: none;
      border-radius: 10px;
      color: #fff;
      font-family: inherit;
      font-size: 0.95rem;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: opacity .2s;
      text-decoration: none;
    }

    .btn-add-cart:hover { opacity: .88; }

    .btn-wishlist {
      width: 52px;
      height: 52px;
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      color: var(--muted);
      flex-shrink: 0;
      transition: color .2s, border-color .2s;
    }

    .btn-wishlist:hover { color: var(--accent-pink); border-color: var(--accent-pink); }

    /* Garanties */
    .garanties {
      display: flex;
      gap: 0;
      border-top: 1px solid var(--border);
      padding-top: 20px;
    }

    .garantie {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      text-align: center;
    }

    .garantie-icon {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: var(--input-bg);
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--accent-pink);
    }

    .garantie span {
      font-size: 0.78rem;
      color: var(--muted);
      font-weight: 500;
    }

    /* ── SIMILAR PRODUCTS ── */
    .similar { margin-top: 16px; }

    .similar h2 {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 24px;
    }

    .similar-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      max-width: 580px;
    }

    .sim-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      overflow: hidden;
      text-decoration: none;
      transition: transform .2s, box-shadow .2s;
      display: block;
    }

    .sim-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 32px rgba(0,0,0,.4);
    }

    .sim-img {
  width: 100%;             
  aspect-ratio: 4/3;   
  object-fit: contain; 
  background: var(--input-bg);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  color: var(--muted);
}


    .sim-body { padding: 16px; }

    .sim-cat {
      font-size: 0.75rem;
      color: var(--muted);
      margin-bottom: 4px;
    }

    .sim-name {
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 6px;
    }

    .sim-price {
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--accent-pink);
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
      .topbar { padding: 0 20px; }
      .page { padding: 28px 20px 60px; }
      .product-layout { grid-template-columns: 1fr; gap: 32px; }
      .similar-grid { grid-template-columns: repeat(2, 1fr); max-width: 100%; }
    }
  </style>
</head>
<body>

  <!-- TOPBAR -->
  <header class="topbar">
    <a href="http://localhost/shopesa/index.php" class="back-link">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M15 18l-6-6 6-6"/>
      </svg>
      Retour
    </a>
    <a href="http://localhost/shopesa/index.php" class="topbar-logo">ShopEsa</a>
    <a href="affiche_panier.php" class="topbar-cart">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
      </svg>
      <span class="badge">
        <?php
        require '../liaison.php';
        try {
    $stmt = $com->prepare("SELECT COUNT(idpanier) AS nb FROM panier WHERE ref_uti = ?");
    $stmt->execute([$ref_uti]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
          echo $row['nb'];

      } catch (PDOException $e) {
          echo "Erreur : " . $e->getMessage();
      }
      ?>
      </span>
    </a>
  </header>
  <?php require'selectSQL.php';
      require '../liaison.php';
      $qte=1;?>
  <!-- PAGE -->
   <form method="post">
  <main class="page">

    <!-- PRODUCT -->
    <div class="product-layout">
      <!-- LEFT : Images -->
      <div class="product-images">
        <div class="main-img">
          <input type="hidden" name="idprod" value="<?php echo $id; ?>">
          <img src="../image/<?php echo $image ?>" alt="">
          
          </a>
        </div>
        <div class="thumb-row">
          <a href="" class="thumb active">watch</a>
          <a href="" class="thumb">watch</a>
          <a href="" class="thumb">watch</a>
          <a href="" class="thumb">watch</a>
        </div>
      </div>

      <!-- RIGHT : Info -->
      <div class="product-info">

        <p class="product-cat"><?php echo $nom_cat ?></p>
        <h1 class="product-name"><?php echo $nomprod ?></h1>

        <!-- Stars -->
        

        <p class="product-price"><?php echo $price ?>FCFA</p>

        <p class="product-desc">
          <?php echo $descri ?>
        </p>

        <!-- Taille -->
       

        <!-- Couleur -->
        

       

        <!-- Ajouter au panier + Wishlist -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'client') {?>
    <div class="cart-row">
          <button class="btn-add-cart" name="ajouter" formaction="affiche_panier.php">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
            Ajouter au panier
          </button>
          
        </div>
<?php
}else{

}
?>
        

        <!-- Garanties -->
        <div class="garanties">
          <div class="garantie">
            <div class="garantie-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 5v3h-7V8z"/>
                <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
              </svg>
            </div>
            <span>Livraison gratuite</span>
          </div>
          <div class="garantie">
            <div class="garantie-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/>
              </svg>
            </div>
            <span>Retours 30 jours</span>
          </div>
          <div class="garantie">
            <div class="garantie-icon">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
              </svg>
            </div>
            <span>Paiement sécurisé</span>
          </div>
        </div>

      </div>
    </div>

    <!-- PRODUITS SIMILAIRES -->
    <div class="similar">
      <h2>Produits similaires</h2>
      <div class="similar-grid">
        <?php
        try{
         $stmt = $com->prepare("SELECT * FROM produit RIGHT JOIN categorie ON produit.id_cat = categorie.id_cat WHERE nom = ? LIMIT 3");
         $stmt->execute([$nom_cat]); 
         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              ?>
              <a href="detai.php?id=<?php echo htmlspecialchars($row['idprod']); ?>" class="sim-card">
          <div class="sim-img">
            <img src="../image/<?php echo htmlspecialchars($row['image']); ?>" alt="" width="100%">
          </div>
          <div class="sim-body">
            <p class="sim-cat"><?php echo htmlspecialchars($row['nom']); ?></p>
            <p class="sim-name"><?php echo htmlspecialchars($row['nomprod']); ?></p>
            <p class="sim-price"><?php echo htmlspecialchars($row['prix']); ?>FCFA</p>
          </div>
        </a>
              <?php
                }
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
            ?>
      </div>
    </div>

  </main>
  </form>

  <script>
    // Quantité
    function changeQty(btn, delta) {
      const wrap = btn.closest('.qty');
      const val  = wrap.querySelector('.qty-val');
      let n = parseInt(val.textContent) + delta;
      if (n < 1) n = 1;
      val.textContent = n;
    }

    // Sélection taille / couleur
    function selectOpt(btn, group) {
      const parent = btn.closest('.size-row, .color-row');
      parent.querySelectorAll('button').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    }
  </script>
</body>
</html>