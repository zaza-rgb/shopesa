<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <style>
    /* ── HEADER / NAVBAR ── */
    .navbar {
      width: 100%;
      background: rgba(13, 15, 26, 0.95);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid #2a2f4a;
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
      background: linear-gradient(90deg, #e040c8, #9c27b0);
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
      color: #8890b0;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
      transition: color .2s;
      font-family: 'Sora', sans-serif;
    }

    .navbar-links a:hover { color: #e8eaf6; }

    .navbar-actions {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    /* Icône panier */
    .cart-btn {
      position: relative;
      background: none;
      border: none;
      cursor: pointer;
      color: #8890b0;
      display: flex;
      align-items: center;
      transition: color .2s;
      text-decoration: none;
    }

    .cart-btn:hover { color: #e040c8; }

    .cart-badge {
      position: absolute;
      top: -6px; right: -6px;
      width: 18px; height: 18px;
      border-radius: 50%;
      background: linear-gradient(90deg, #e040c8, #9c27b0);
      color: #fff;
      font-size: 0.65rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Sora', sans-serif;
    }

    /* Icône profil */
    .profile-btn {
      width: 38px; height: 38px;
      border-radius: 50%;
      background: linear-gradient(135deg, #e040c8, #9c27b0);
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      text-decoration: none;
      transition: opacity .2s, transform .2s;
      flex-shrink: 0;
    }

    .profile-btn:hover { opacity: .85; transform: scale(1.05); }

    /* Si connecté : avatar avec initiale */
    .profile-btn.connected {
      font-size: 0.9rem;
      font-weight: 700;
      font-family: 'Sora', sans-serif;
    }

    /* Menu déroulant profil */
    .profile-wrap {
      position: relative;
    }

    .profile-dropdown {
      display: none;
      position: absolute;
      top: calc(100% + 12px);
      right: 0;
      background: #141728;
      border: 1px solid #2a2f4a;
      border-radius: 12px;
      padding: 8px;
      min-width: 200px;
      z-index: 1000;
      box-shadow: 0 16px 40px rgba(0,0,0,.5);
    }

    .profile-wrap:hover .profile-dropdown,
    .profile-wrap.open .profile-dropdown {
      display: block;
    }

    .profile-dropdown a,
    .profile-dropdown button {
      display: flex;
      align-items: center;
      gap: 10px;
      width: 100%;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 0.85rem;
      font-weight: 500;
      color: #8890b0;
      text-decoration: none;
      background: none;
      border: none;
      cursor: pointer;
      font-family: 'Sora', sans-serif;
      transition: all .15s;
      text-align: left;
    }

    .profile-dropdown a:hover,
    .profile-dropdown button:hover {
      background: #1a1f35;
      color: #e8eaf6;
    }

    .profile-dropdown .dropdown-divider {
      border: none;
      border-top: 1px solid #2a2f4a;
      margin: 6px 0;
    }

    .profile-dropdown .logout-btn {
      color: #ef5350;
    }

    .profile-dropdown .logout-btn:hover {
      background: rgba(239,83,80,.1);
      color: #ef5350;
    }

    .dropdown-user-info {
      padding: 10px 14px 6px;
    }

    .dropdown-user-info p:first-child {
      font-size: 0.88rem;
      font-weight: 600;
      color: #e8eaf6;
      font-family: 'Sora', sans-serif;
    }

    .dropdown-user-info p:last-child {
      font-size: 0.75rem;
      color: #8890b0;
      font-family: 'Sora', sans-serif;
      margin-top: 2px;
    }

    /* Bouton Admin */
    .admin-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      background: rgba(224, 64, 200, .1);
      border: 1px solid rgba(224, 64, 200, .3);
      border-radius: 8px;
      color: #e040c8;
      text-decoration: none;
      font-size: 0.8rem;
      font-weight: 600;
      font-family: 'Sora', sans-serif;
      transition: all .2s;
    }

    .admin-link:hover {
      background: rgba(224, 64, 200, .2);
      border-color: #e040c8;
    }

    @media (max-width: 768px) {
      .navbar { padding: 0 20px; }
      .navbar-links { display: none; }
    }
  </style>
</head>
<body>
<nav class="navbar">
  <!-- Brand -->
  <a href="/Shopesa/index.php" class="navbar-brand">ShopEsa</a>

  <!-- Liens nav -->
  <ul class="navbar-links">
    <li><a href="/Shopesa/index.php">Accueil</a></li>
    <li><a href="../Shopesa/produits/produits utilisateur/affprod.php">Boutique</a></li>
    <li><a href="#">Nouveautés</a></li>
    <li><a href="#">Promotions</a></li>
  </ul>

  <!-- Actions droite -->

  <div class="navbar-actions">
    <a href="produits/noctification.php" class="cart-btn" title="Mon panier">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        <span class="notif-dot"> <?php
        require __DIR__ . '/../liaison.php';
        if(!isset($_SESSION['ref_uti'])){
              echo '0';
        }else{ 
        $ref_uti=$_SESSION['ref_uti'];
        try {
    $stmt = $com->prepare("SELECT COUNT(idnotif) AS nb FROM notification WHERE ref_uti = ?");
    $stmt->execute([$ref_uti]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
          echo $row['nb'];

      } catch (PDOException $e) {
          echo "Erreur : " . $e->getMessage();
      }}
      ?></span>
      </a>
      
    <!-- Panier -->
    <a href="/Shopesa/panier/affiche_panier.php" class="cart-btn" title="Mon panier">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
      </svg>
      <span class="cart-badge"> <?php
        if(!isset($_SESSION['ref_uti'])){
              echo '0';
        }else{ 
        $ref_uti=$_SESSION['ref_uti'];
        try {
    $stmt = $com->prepare("SELECT COUNT(idpanier) AS nb FROM panier WHERE ref_uti = ?");
    $stmt->execute([$ref_uti]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
          echo $row['nb'];

      } catch (PDOException $e) {
          echo "Erreur : " . $e->getMessage();
      }}
      ?></span>
    </a>

    <!-- Profil / Connexion -->
    <div class="profile-wrap" id="profileWrap">

      <?php if (isset($_SESSION['ref_uti']) && $_SESSION['role'] === 'client'): ?>
        <!-- Connecté en tant que client -->
        <a href="" class="profile-btn connected" title="Mon profil" onclick="toggleDropdown(event)">
          <?= strtoupper(substr($_SESSION['nom'], 0, 1)) ?>
        </a>
        <div class="profile-dropdown" id="profileDropdown">
          <div class="dropdown-user-info">
            <p><?= htmlspecialchars($_SESSION['nom']) ?></p>
            <p><?= htmlspecialchars($_SESSION['email']) ?></p>
          </div>
          <hr class="dropdown-divider" />
          <a href="#">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Mon profil
          </a>
          <a href="/Shopesa/panier/histo.php">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Mes commandes
          </a>
          <hr class="dropdown-divider" />
          <a href="/Shopesa/Authentification/logout_client.php" class="logout-btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Déconnexion
          </a>
        </div>

      <?php else: ?>
        <!-- Non connecté -->
        <a href="/Shopesa/Authentification/connect_client.php" class="profile-btn" title="Connexion / Inscription">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
          </svg>
        </a>
      <?php endif; ?>

    </div>

    <!-- Lien Admin (visible uniquement si admin connecté, sinon lien discret) -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
      <a href="/Shopesa/produits/produits_admin/dashboard.php" class="admin-link">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Admin
      </a>
    <?php else: ?>
      <a href="/Shopesa/Authentification/connect_admin.php" class="admin-link">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
        Admin
      </a>
    <?php endif; ?>

  </div>
</nav>

<script>
  function toggleDropdown(e) {
    e.preventDefault();
    document.getElementById('profileWrap').classList.toggle('open');
  }
  // Fermer si on clique ailleurs
  document.addEventListener('click', function(e) {
    const wrap = document.getElementById('profileWrap');
    if (wrap && !wrap.contains(e.target)) {
      wrap.classList.remove('open');
    }
  });
</script>