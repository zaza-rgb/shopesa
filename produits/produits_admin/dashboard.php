<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopStyle Admin – Dashboard</title>
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
      --blue:         #2979ff;
      --orange:       #ff6d00;
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

    .nav-link:hover { background: var(--input-bg); color: var(--text); }
    .nav-link.active { background: var(--grad); color: #fff; }
    .nav-link svg { flex-shrink: 0; }
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

    .page-header { margin-bottom: 28px; }
    .page-header h2 { font-size: 1.8rem; font-weight: 800; }
    .page-header p  { font-size: 0.85rem; color: var(--muted); margin-top: 4px; }

    /* ── STAT CARDS ── */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin-bottom: 28px;
    }

    .stat-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 22px 24px;
    }

    .stat-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
    }

    .stat-icon {
      width: 44px; height: 44px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .stat-icon.blue   { background: #1565c0; }
    .stat-icon.green  { background: #2e7d32; }
    .stat-icon.purple { background: #6a1b9a; }
    .stat-icon.orange { background: #e65100; }

    .stat-badge {
      font-size: 0.75rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 3px;
    }

    .stat-badge.up   { color: var(--green); }
    .stat-badge.down { color: var(--red); }

    .stat-label { font-size: 0.78rem; color: var(--muted); margin-bottom: 6px; }
    .stat-value { font-size: 1.6rem; font-weight: 800; }

    /* ── CHARTS ROW ── */
    .charts-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 28px;
    }

    .chart-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 24px;
    }

    .chart-card h3 {
      font-size: 0.95rem;
      font-weight: 700;
      margin-bottom: 20px;
    }

    canvas { width: 100% !important; }

    /* ── TABLE ── */
    .table-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 24px;
    }

    .table-card h3 {
      font-size: 0.95rem;
      font-weight: 700;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead tr {
      border-bottom: 1px solid var(--border);
    }

    thead th {
      font-size: 0.72rem;
      font-weight: 700;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .6px;
      padding: 0 16px 12px 0;
      text-align: left;
    }

    tbody tr {
      border-bottom: 1px solid var(--border);
      transition: background .15s;
    }

    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--input-bg); }

    tbody td {
      padding: 14px 16px 14px 0;
      font-size: 0.87rem;
    }

    .order-id { color: var(--muted); font-weight: 500; }

    /* Status badges */
    .badge-status {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      border: 1px solid transparent;
    }

    .badge-status.livre    { color: #4caf7d; border-color: #4caf7d; background: rgba(76,175,125,.1); }
    .badge-status.en-cours { color: #2979ff; border-color: #2979ff; background: rgba(41,121,255,.1); }
    .badge-status.attente  { color: #fbbf24; border-color: #fbbf24; background: rgba(251,191,36,.1); }

    /* ── RESPONSIVE ── */
    @media (max-width: 1200px) {
      .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 900px) {
      .charts-row { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
      .sidebar { display: none; }
      .topbar  { padding-left: 20px; }
      .main    { margin-left: 0; }
      .stats-grid { grid-template-columns: 1fr 1fr; }
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
      <a href="dashboard.php" class="nav-link active">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
          <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
        </svg>
        Dashboard
      </a>
      <a href="produits.php" class="nav-link">
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

    <div class="page-header">
      <h2>Dashboard</h2>
      <p>Vue d'ensemble de votre boutique</p>
    </div>

    <!-- STAT CARDS -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon blue">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
          </div>
          <span class="stat-badge up">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            +12.5%
          </span>
        </div>
        <p class="stat-label">Revenus totaux</p>
        <p class="stat-value">45 231€</p>
      </div>

      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon green">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
          </div>
          <span class="stat-badge up">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            +8.2%
          </span>
        </div>
        <p class="stat-label">Commandes</p>
        <p class="stat-value">1,234</p>
      </div>

      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon purple">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
              <circle cx="9" cy="7" r="4"/>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
          </div>
          <span class="stat-badge up">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            +4.3%
          </span>
        </div>
        <p class="stat-label">Clients</p>
        <p class="stat-value">8,549</p>
      </div>

      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon orange">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
              <line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
          </div>
          <span class="stat-badge down">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
            -2.1%
          </span>
        </div>
        <p class="stat-label">Produits</p>
        <p class="stat-value">342</p>
      </div>
    </div>

    <!-- CHARTS -->
    <div class="charts-row">
      <div class="chart-card">
        <h3>Évolution des ventes</h3>
        <canvas id="lineChart" height="200"></canvas>
      </div>
      <div class="chart-card">
        <h3>Revenus mensuels</h3>
        <canvas id="barChart" height="200"></canvas>
      </div>
    </div>

    <!-- COMMANDES RÉCENTES -->
    <div class="table-card">
      <h3>Commandes récentes</h3>
      <table>
        <thead>
          <tr>
            <th>Commande</th>
            <th>Client</th>
            <th>Produit</th>
            <th>Montant</th>
            <th>Statut</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="order-id">#12345</td>
            <td>Marie Dupont</td>
            <td>Sneakers Premium</td>
            <td>129.99€</td>
            <td><span class="badge-status livre">Livré</span></td>
          </tr>
          <tr>
            <td class="order-id">#12344</td>
            <td>Jean Martin</td>
            <td>Montre Élégante</td>
            <td>299.99€</td>
            <td><span class="badge-status en-cours">En cours</span></td>
          </tr>
          <tr>
            <td class="order-id">#12343</td>
            <td>Sophie Bernard</td>
            <td>Sac à Dos Design</td>
            <td>89.99€</td>
            <td><span class="badge-status en-cours">En cours</span></td>
          </tr>
          <tr>
            <td class="order-id">#12342</td>
            <td>Pierre Dubois</td>
            <td>Lunettes de Soleil</td>
            <td>159.99€</td>
            <td><span class="badge-status livre">Livré</span></td>
          </tr>
          <tr>
            <td class="order-id">#12341</td>
            <td>Claire Petit</td>
            <td>Veste en Cuir</td>
            <td>349.99€</td>
            <td><span class="badge-status attente">En attente</span></td>
          </tr>
        </tbody>
      </table>
    </div>

  </main>

  <!-- Chart.js CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
  <script>
    const gridColor  = 'rgba(42,47,74,0.8)';
    const tickColor  = '#8890b0';
    const months     = ['Jan','Fév','Mar','Avr','Mai','Juin','Juil'];

    // ── Ligne : Évolution des ventes ──
    new Chart(document.getElementById('lineChart'), {
      type: 'line',
      data: {
        labels: months,
        datasets: [{
          data: [4000, 3000, 2000, 2800, 2000, 2700, 3500],
          borderColor: '#2979ff',
          backgroundColor: 'rgba(41,121,255,0.08)',
          borderWidth: 2.5,
          pointBackgroundColor: '#2979ff',
          pointRadius: 5,
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { color: gridColor }, ticks: { color: tickColor, font: { family: 'Sora', size: 11 } } },
          y: {
            min: 0, max: 4500,
            grid: { color: gridColor },
            ticks: { color: tickColor, font: { family: 'Sora', size: 11 },
              callback: v => v.toLocaleString() }
          }
        }
      }
    });

    // ── Barres : Revenus mensuels ──
    new Chart(document.getElementById('barChart'), {
      type: 'bar',
      data: {
        labels: months,
        datasets: [{
          data: [3200, 1800, 5500, 9800, 4200, 6500, 7000],
          backgroundColor: ctx => {
            const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 300);
            g.addColorStop(0,  '#e040c8');
            g.addColorStop(1,  '#9c27b0');
            return g;
          },
          borderRadius: 6,
          borderSkipped: false
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { color: gridColor }, ticks: { color: tickColor, font: { family: 'Sora', size: 11 } } },
          y: {
            min: 0,
            grid: { color: gridColor },
            ticks: { color: tickColor, font: { family: 'Sora', size: 11 },
              callback: v => v.toLocaleString() }
          }
        }
      }
    });
  </script>
</body>
</html>