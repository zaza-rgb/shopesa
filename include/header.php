<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopStyle – Navbar</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --nav-bg:       #0d0f1a;
      --border:       #2a2f4a;
      --text:         #e8eaf6;
      --muted:        #8890b0;
      --input-bg:     #1a1f35;
      --accent-pink:  #e040c8;
      --accent-purple:#9c27b0;
      --grad:         linear-gradient(90deg, #e040c8, #9c27b0);
    }

    body {
      background: #0d0f1a;
      font-family: 'Sora', sans-serif;
    }

    /* ── NAVBAR ── */
    nav {
      width: 100%;
      background: var(--nav-bg);
      border-bottom: 1px solid var(--border);
      padding: 0 48px;
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    /* Logo */
    .nav-logo {
      font-size: 1.4rem;
      font-weight: 700;
      background: var(--grad);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      text-decoration: none;
      white-space: nowrap;
      flex-shrink: 0;
    }

    /* Links */
    .nav-links {
      display: flex;
      align-items: center;
      gap: 32px;
      list-style: none;
    }

    .nav-links a {
      font-size: 0.9rem;
      font-weight: 500;
      color: var(--text);
      text-decoration: none;
      transition: color .2s;
      white-space: nowrap;
    }

    .nav-links a:hover { color: var(--accent-pink); }

    /* Search */
    .nav-search {
      flex: 1;
      max-width: 340px;
      position: relative;
      display: flex;
      align-items: center;
    }

    .nav-search svg {
      position: absolute;
      left: 12px;
      color: var(--muted);
      pointer-events: none;
    }

    .nav-search input {
      width: 100%;
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: 8px;
      padding: 9px 14px 9px 38px;
      font-family: inherit;
      font-size: 0.85rem;
      color: var(--text);
      outline: none;
      transition: border-color .2s;
    }

    .nav-search input::placeholder { color: var(--muted); }
    .nav-search input:focus { border-color: var(--accent-purple); }

    /* Icons group */
    .nav-icons {
      display: flex;
      align-items: center;
      gap: 20px;
      flex-shrink: 0;
    }

    .icon-btn {
      background: none;
      border: none;
      cursor: pointer;
      color: var(--text);
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      transition: color .2s;
    }

    .icon-btn:hover { color: var(--accent-pink); }

    /* Cart badge */
    .badge {
      position: absolute;
      top: -6px;
      right: -7px;
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

    /* Admin button */
    .btn-admin {
      padding: 9px 22px;
      background: var(--grad);
      border: none;
      border-radius: 8px;
      color: #fff;
      font-family: inherit;
      font-size: 0.875rem;
      font-weight: 600;
      cursor: pointer;
      transition: opacity .2s;
      white-space: nowrap;
      text-decoration: none;
      
    }

    .btn-admin:hover { opacity: .88; }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
      nav { padding: 0 20px; gap: 16px; }
      .nav-links { gap: 20px; }
      .nav-search { max-width: 200px; }
    }

    @media (max-width: 680px) {
      .nav-links { display: none; }
      .nav-search { max-width: 160px; }
    }

    a .styl{
      color: #fff;
      text-decoration: none;
    }
  </style>
</head>
<body>

  <nav>
    <!-- Logo -->
    <a href="#" class="nav-logo">ShopStyle</a>

    <!-- Links -->
    <ul class="nav-links">
      <li><a href="#">Nouveautés</a></li>
      <li><a href="#">Hommes</a></li>
      <li><a href="#">Femmes</a></li>
      <li><a href="#">Accessoires</a></li>
    </ul>

    <!-- Search -->
    <div class="nav-search">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
      </svg>
      <input type="text" placeholder="Rechercher..." />
    </div>

    <!-- Icons -->
    <div class="nav-icons">
      <!-- Wishlist -->
      <button class="icon-btn" title="Favoris">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </svg>
      </button>

      <!-- Account -->
      <button class="icon-btn" title="Compte">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
          <circle cx="12" cy="7" r="4"/>
        </svg>
      </button>

      <!-- Cart -->
      <button class="icon-btn" title="Panier">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        <span class="badge">3</span>
      </button>

      <!-- Admin -->
      <button class="btn-admin"><a  href="Authentification/connect_admin.php">Admin</a></button>
    </div>
  </nav>

</body>
</html>