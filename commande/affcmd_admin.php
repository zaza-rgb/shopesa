<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopStyle Admin – Produits</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:           #0a0c18;
      --sidebar-bg:   #0d0f1a;
      --card-bg:      #141728;
      --input-bg:     #1a1f35;
      --border:       #2a2f4a;
      --text:         #e8eaf6;
      --muted:        #8890b0;
      --accent-pink:  #e040c8;
      --accent-purple:#9c27b0;
      --grad:         linear-gradient(90deg, #e040c8, #9c27b0);
      --green:        #4caf7d;
      --red:          #ef5350;
      --orange:       #fbbf24;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Sora', sans-serif;
      min-height: 100vh;
    }

    /* ════════════════════════════
       SIDEBAR
    ════════════════════════════ */
    .sidebar {
      width: 240px;
      background: var(--sidebar-bg);
      border-right: 1px solid var(--border);
      position: fixed;
      top: 0; left: 0; bottom: 0;
      display: flex;
      flex-direction: column;
      padding: 24px 0 0;
      z-index: 300;
    }

    .brand {
      padding: 0 20px 24px;
      border-bottom: 1px solid var(--border);
    }

    .brand h1 {
      font-size: 1.1rem;
      font-weight: 800;
      background: var(--grad);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .brand p { font-size: 0.72rem; color: var(--muted); margin-top: 2px; }

    .nav-section {
      flex: 1;
      padding: 16px 12px;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 12px;
      border-radius: 10px;
      text-decoration: none;
      color: var(--muted);
      font-size: 0.88rem;
      font-weight: 500;
      transition: all .2s;
    }

    .nav-link:hover  { background: var(--input-bg); color: var(--text); }
    .nav-link.active { background: var(--grad); color: #fff; }
    .nav-link svg    { flex-shrink: 0; }
    .nav-link.danger { color: var(--red); }
    .nav-link.danger:hover { background: rgba(239,83,80,.1); color: var(--red); }

    .sidebar-bottom {
      padding: 12px;
      border-top: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    /* ════════════════════════════
       TOPBAR
    ════════════════════════════ */
    .topbar {
      height: 64px;
      background: var(--sidebar-bg);
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      padding: 0 28px 0 268px;
      gap: 16px;
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 200;
    }

    .search-wrap {
      flex: 1;
      max-width: 520px;
      position: relative;
    }

    .search-wrap svg {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
    }

    .search-wrap input {
      width: 100%;
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: 8px;
      padding: 10px 14px 10px 38px;
      font-family: inherit;
      font-size: 0.85rem;
      color: var(--text);
      outline: none;
      transition: border-color .2s;
    }

    .search-wrap input::placeholder { color: var(--muted); }
    .search-wrap input:focus { border-color: var(--accent-purple); }

    .topbar-right {
      margin-left: auto;
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .notif-btn {
      position: relative;
      background: none;
      border: none;
      color: var(--muted);
      cursor: pointer;
      display: flex;
      align-items: center;
      transition: color .2s;
    }

    .notif-btn:hover { color: var(--text); }

    .notif-dot {
      position: absolute;
      top: -3px; right: -3px;
      width: 10px; height: 10px;
      border-radius: 50%;
      background: var(--accent-pink);
      border: 2px solid var(--sidebar-bg);
    }

    .user-info { display: flex; align-items: center; gap: 10px; }

    .avatar {
      width: 36px; height: 36px;
      border-radius: 50%;
      background: var(--grad);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.9rem;
      color: #fff;
      flex-shrink: 0;
    }

    .user-text p:first-child { font-size: 0.85rem; font-weight: 600; }
    .user-text p:last-child  { font-size: 0.72rem; color: var(--muted); }

    /* ════════════════════════════
       MAIN
    ════════════════════════════ */
    .main {
      margin-left: 240px;
      margin-top: 64px;
      padding: 36px 32px 60px;
    }

    /* Page header */
    .page-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      margin-bottom: 28px;
    }

    .page-header h2 { font-size: 1.8rem; font-weight: 800; }
    .page-header p  { font-size: 0.85rem; color: var(--muted); margin-top: 4px; }

    .btn-add {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 22px;
      background: var(--grad);
      border: none;
      border-radius: 10px;
      color: #fff;
      font-family: inherit;
      font-size: 0.88rem;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      white-space: nowrap;
      transition: opacity .2s;
    }

    .btn-add:hover { opacity: .88; }

    /* ── TABLE CARD ── */
    .table-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      overflow: hidden;
    }

    /* Filters row */
    .filters-row {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
      flex-wrap: wrap;
    }

    .filter-search {
      flex: 1;
      min-width: 200px;
      position: relative;
    }

    .filter-search svg {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
    }

    .filter-search input {
      width: 100%;
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: 8px;
      padding: 10px 14px 10px 38px;
      font-family: inherit;
      font-size: 0.85rem;
      color: var(--text);
      outline: none;
      transition: border-color .2s;
    }

    .filter-search input::placeholder { color: var(--muted); }
    .filter-search input:focus { border-color: var(--accent-purple); }

    .select-wrap {
      position: relative;
      flex-shrink: 0;
    }

    .select-wrap select {
      appearance: none;
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: 8px;
      padding: 10px 36px 10px 14px;
      font-family: inherit;
      font-size: 0.85rem;
      color: var(--text);
      outline: none;
      cursor: pointer;
      transition: border-color .2s;
    }

    .select-wrap select:focus { border-color: var(--accent-purple); }

    .select-wrap svg {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
      pointer-events: none;
    }

    /* Table */
    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead tr { border-bottom: 1px solid var(--border); }

    thead th {
      font-size: 0.72rem;
      font-weight: 700;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .6px;
      padding: 14px 16px;
      text-align: left;
    }

    tbody tr {
      border-bottom: 1px solid var(--border);
      transition: background .15s;
    }

    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--input-bg); }

    tbody td {
      padding: 14px 16px;
      font-size: 0.87rem;
    }

    /* Product cell */
    .product-cell {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .product-thumb {
      width: 44px;
      height: 44px;
      border-radius: 8px;
      background: linear-gradient(135deg, #e040c8, #9c27b0);
      flex-shrink: 0;
    }

    .product-name { font-weight: 600; font-size: 0.88rem; }

    /* Badges statut */
    .badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.74rem;
      font-weight: 700;
      border: 1px solid transparent;
    }

    .badge.stock    { color: #4caf7d; border-color: #4caf7d; background: rgba(76,175,125,.12); }
    .badge.faible   { color: #fbbf24; border-color: #fbbf24; background: rgba(251,191,36,.12); }
    .badge.rupture  { color: #ef5350; border-color: #ef5350; background: rgba(239,83,80,.12);  }

    /* Actions */
    .actions {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .action-link {
      width: 32px; height: 32px;
      border-radius: 7px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      border: 1px solid var(--border);
      background: transparent;
      transition: all .2s;
    }

    .action-link.edit { color: var(--accent-pink); }
    .action-link.edit:hover { background: rgba(224,64,200,.12); border-color: var(--accent-pink); }

    .action-link.del  { color: var(--red); }
    .action-link.del:hover  { background: rgba(239,83,80,.12); border-color: var(--red); }

    /* Pagination */
    .pagination {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 24px;
      border-top: 1px solid var(--border);
    }

    .pagination p { font-size: 0.8rem; color: var(--muted); }

    .pag-btns { display: flex; gap: 8px; }

    .pag-btn {
      padding: 8px 18px;
      border-radius: 8px;
      font-family: inherit;
      font-size: 0.83rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      transition: all .2s;
    }

    .pag-btn.prev {
      background: var(--input-bg);
      border: 1px solid var(--border);
      color: var(--text);
    }

    .pag-btn.prev:hover { border-color: var(--accent-purple); color: var(--accent-pink); }

    .pag-btn.next {
      background: var(--grad);
      border: none;
      color: #fff;
    }

    .pag-btn.next:hover { opacity: .88; }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
      .sidebar { display: none; }
      .topbar  { padding-left: 20px; }
      .main    { margin-left: 0; }
    }
  </style>
</head>
<body>

  <!-- ════ SIDEBAR ════ -->
  <aside class="sidebar">
    <div class="brand">
      <h1>ShopStyle Admin</h1>
      <p>Panneau de gestion</p>
    </div>

    <nav class="nav-section">
      <a href="dashboard.php" class="nav-link">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
          <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
        </svg>
        Dashboard
      </a>
      <a href="produits.php" class="nav-link active">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
        Produits
      </a>
      <a href="commandes.php" class="nav-link">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/>
          <line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
        Commandes
      </a>
      <a href="clients.php" class="nav-link">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
        Clients
      </a>
    </nav>

    <div class="sidebar-bottom">
      <a href="index.php" class="nav-link">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
          <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
        Retour au site
      </a>
      <a href="parametres.php" class="nav-link">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="3"/>
          <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
        </svg>
        Paramètres
      </a>
      <a href="logout.php" class="nav-link danger">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Déconnexion
      </a>
    </div>
  </aside>

  <!-- ════ TOPBAR ════ -->
  <header class="topbar">
    <div class="search-wrap">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
      </svg>
      <input type="text" placeholder="Rechercher..." />
    </div>
    <div class="topbar-right">
      <button class="notif-btn">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        <span class="notif-dot"></span>
      </button>
      <div class="user-info">
        <div class="avatar">A</div>
        <div class="user-text">
          <p>Admin User</p>
          <p>admin@shopstyle.com</p>
        </div>
      </div>
    </div>
  </header>

  <!-- ════ MAIN ════ -->
  <main class="main">

    <!-- Page header -->
    <div class="page-header">
      <div>
        <h2>Produits</h2>
        <p>Gérez votre catalogue de produits</p>
      </div>
      <a href="ajouter-produit.php" class="btn-add">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Ajouter un produit
      </a>
    </div>

    <!-- Table card -->
    <div class="table-card">

      <!-- Filtres -->
      <div class="filters-row">
        <div class="filter-search">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
          </svg>
          <input type="text" placeholder="Rechercher un produit..." id="searchInput" oninput="filterTable()" />
        </div>

        <div class="select-wrap">
          <select id="catFilter" onchange="filterTable()">
            <option value="">Toutes les catégories</option>
            <option>Chaussures</option>
            <option>Accessoires</option>
            <option>Sacs</option>
            <option>Vêtements</option>
          </select>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"/>
          </svg>
        </div>

        <div class="select-wrap">
          <select id="statFilter" onchange="filterTable()">
            <option value="">Tous les statuts</option>
            <option value="stock">En stock</option>
            <option value="faible">Stock faible</option>
            <option value="rupture">Rupture</option>
          </select>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"/>
          </svg>
        </div>
      </div>

      <!-- Tableau -->
      <table>
        <thead>
          <tr>
            <th>Produit</th>
            <th>Catégorie</th>
            <th>Prix</th>
            <th>Stock</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="productTable">

          <tr data-cat="Chaussures" data-stat="stock">
            <td><div class="product-cell"><div class="product-thumb"></div><span class="product-name">Sneakers Premium</span></div></td>
            <td>Chaussures</td>
            <td>129.99€</td>
            <td>45</td>
            <td><span class="badge stock">En stock</span></td>
            <td><div class="actions">
              <a href="modifier-produit.php?id=1" class="action-link edit" title="Modifier">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <a href="supprimer-produit.php?id=1" class="action-link del" title="Supprimer">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              </a>
            </div></td>
          </tr>

          <tr data-cat="Accessoires" data-stat="stock">
            <td><div class="product-cell"><div class="product-thumb"></div><span class="product-name">Montre Élégante</span></div></td>
            <td>Accessoires</td>
            <td>299.99€</td>
            <td>23</td>
            <td><span class="badge stock">En stock</span></td>
            <td><div class="actions">
              <a href="modifier-produit.php?id=2" class="action-link edit" title="Modifier">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <a href="supprimer-produit.php?id=2" class="action-link del" title="Supprimer">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              </a>
            </div></td>
          </tr>

          <tr data-cat="Sacs" data-stat="stock">
            <td><div class="product-cell"><div class="product-thumb"></div><span class="product-name">Sac à Dos Design</span></div></td>
            <td>Sacs</td>
            <td>89.99€</td>
            <td>67</td>
            <td><span class="badge stock">En stock</span></td>
            <td><div class="actions">
              <a href="modifier-produit.php?id=3" class="action-link edit" title="Modifier">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <a href="supprimer-produit.php?id=3" class="action-link del" title="Supprimer">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              </a>
            </div></td>
          </tr>

          <tr data-cat="Accessoires" data-stat="faible">
            <td><div class="product-cell"><div class="product-thumb"></div><span class="product-name">Lunettes de Soleil</span></div></td>
            <td>Accessoires</td>
            <td>159.99€</td>
            <td>12</td>
            <td><span class="badge faible">Stock faible</span></td>
            <td><div class="actions">
              <a href="modifier-produit.php?id=4" class="action-link edit" title="Modifier">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <a href="supprimer-produit.php?id=4" class="action-link del" title="Supprimer">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              </a>
            </div></td>
          </tr>

          <tr data-cat="Accessoires" data-stat="stock">
            <td><div class="product-cell"><div class="product-thumb"></div><span class="product-name">Casquette Vintage</span></div></td>
            <td>Accessoires</td>
            <td>39.99€</td>
            <td>89</td>
            <td><span class="badge stock">En stock</span></td>
            <td><div class="actions">
              <a href="modifier-produit.php?id=5" class="action-link edit" title="Modifier">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <a href="supprimer-produit.php?id=5" class="action-link del" title="Supprimer">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              </a>
            </div></td>
          </tr>

          <tr data-cat="Vêtements" data-stat="rupture">
            <td><div class="product-cell"><div class="product-thumb"></div><span class="product-name">T-Shirt Premium</span></div></td>
            <td>Vêtements</td>
            <td>49.99€</td>
            <td>0</td>
            <td><span class="badge rupture">Rupture</span></td>
            <td><div class="actions">
              <a href="modifier-produit.php?id=6" class="action-link edit" title="Modifier">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <a href="supprimer-produit.php?id=6" class="action-link del" title="Supprimer">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              </a>
            </div></td>
          </tr>

          <tr data-cat="Vêtements" data-stat="stock">
            <td><div class="product-cell"><div class="product-thumb"></div><span class="product-name">Jean Slim Fit</span></div></td>
            <td>Vêtements</td>
            <td>79.99€</td>
            <td>34</td>
            <td><span class="badge stock">En stock</span></td>
            <td><div class="actions">
              <a href="modifier-produit.php?id=7" class="action-link edit" title="Modifier">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <a href="supprimer-produit.php?id=7" class="action-link del" title="Supprimer">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              </a>
            </div></td>
          </tr>

          <tr data-cat="Vêtements" data-stat="faible">
            <td><div class="product-cell"><div class="product-thumb"></div><span class="product-name">Veste en Cuir</span></div></td>
            <td>Vêtements</td>
            <td>349.99€</td>
            <td>8</td>
            <td><span class="badge faible">Stock faible</span></td>
            <td><div class="actions">
              <a href="modifier-produit.php?id=8" class="action-link edit" title="Modifier">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <a href="supprimer-produit.php?id=8" class="action-link del" title="Supprimer">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
              </a>
            </div></td>
          </tr>

        </tbody>
      </table>

      <!-- Pagination -->
      <div class="pagination">
        <p id="pageInfo">Affichage de 1 à 8 sur 8 produits</p>
        <div class="pag-btns">
          <a href="#" class="pag-btn prev">Précédent</a>
          <a href="#" class="pag-btn next">Suivant</a>
        </div>
      </div>

    </div>
  </main>

  <script>
    function filterTable() {
      const search = document.getElementById('searchInput').value.toLowerCase();
      const cat    = document.getElementById('catFilter').value;
      const stat   = document.getElementById('statFilter').value;
      const rows   = document.querySelectorAll('#productTable tr');
      let visible  = 0;

      rows.forEach(row => {
        const name    = row.querySelector('.product-name')?.textContent.toLowerCase() || '';
        const rowCat  = row.dataset.cat  || '';
        const rowStat = row.dataset.stat || '';

        const matchSearch = name.includes(search);
        const matchCat    = !cat  || rowCat === cat;
        const matchStat   = !stat || rowStat === stat;

        if (matchSearch && matchCat && matchStat) {
          row.style.display = '';
          visible++;
        } else {
          row.style.display = 'none';
        }
      });

      document.getElementById('pageInfo').textContent =
        `Affichage de 1 à ${visible} sur ${visible} produits`;
    }
  </script>
</body>
</html>