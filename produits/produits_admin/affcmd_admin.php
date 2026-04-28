<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Authentification/connect_admin.php');
    exit;
}
require_once '../../liaison.php';

// Récupérer les commandes depuis la BDD (si table commande existe)
// Sinon on utilise des données de démonstration
$commandes = [];
try {
    $stmt = $pdo->query("
        SELECT c.ref_cmd, c.date_cmd, c.montant_total, c.statut,
               u.nom, u.email,
               COUNT(dc.ref_produit) AS nb_articles
        FROM commande c
        JOIN utilisateur u ON c.ref_uti = u.ref_uti
        LEFT JOIN detail_commande dc ON c.ref_cmd = dc.ref_cmd
        GROUP BY c.ref_cmd
        ORDER BY c.date_cmd DESC
        LIMIT 50
    ");
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Table pas encore créée → données démo
    $commandes = [
        ['ref_cmd'=>'#12345','nom'=>'Marie Dupont',  'email'=>'marie.dupont@email.com',  'date_cmd'=>'2026-04-24','nb_articles'=>1,'montant_total'=>129.99,'statut'=>'livre'],
        ['ref_cmd'=>'#12344','nom'=>'Jean Martin',   'email'=>'jean.martin@email.com',   'date_cmd'=>'2026-04-24','nb_articles'=>1,'montant_total'=>299.99,'statut'=>'en_cours'],
        ['ref_cmd'=>'#12343','nom'=>'Sophie Bernard','email'=>'sophie.bernard@email.com','date_cmd'=>'2026-04-23','nb_articles'=>1,'montant_total'=>89.99, 'statut'=>'en_cours'],
        ['ref_cmd'=>'#12342','nom'=>'Pierre Dubois', 'email'=>'pierre.dubois@email.com', 'date_cmd'=>'2026-04-23','nb_articles'=>1,'montant_total'=>159.99,'statut'=>'livre'],
        ['ref_cmd'=>'#12341','nom'=>'Claire Petit',  'email'=>'claire.petit@email.com',  'date_cmd'=>'2026-04-22','nb_articles'=>1,'montant_total'=>349.99,'statut'=>'attente'],
        ['ref_cmd'=>'#12340','nom'=>'Thomas Moreau', 'email'=>'thomas.moreau@email.com', 'date_cmd'=>'2026-04-22','nb_articles'=>2,'montant_total'=>219.98,'statut'=>'livre'],
        ['ref_cmd'=>'#12339','nom'=>'Emma Laurent',  'email'=>'emma.laurent@email.com',  'date_cmd'=>'2026-04-21','nb_articles'=>1,'montant_total'=>179.99,'statut'=>'annule'],
    ];
}

// Labels et couleurs des statuts
$statuts = [
    'livre'    => ['label'=>'Livré',      'class'=>'livre'],
    'en_cours' => ['label'=>'En cours',   'class'=>'en-cours'],
    'attente'  => ['label'=>'En attente', 'class'=>'attente'],
    'annule'   => ['label'=>'Annulé',     'class'=>'annule'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopStyle Admin – Commandes</title>
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
      --orange:       #fbbf24;
    }

    body { background: var(--bg); color: var(--text); font-family: 'Sora', sans-serif; min-height: 100vh; }

    /* ── SIDEBAR ── */
    .sidebar {
      width: 240px; background: var(--sidebar-bg);
      border-right: 1px solid var(--border);
      position: fixed; top: 0; left: 0; bottom: 0;
      display: flex; flex-direction: column; padding: 24px 0 0; z-index: 300;
    }
    .brand { padding: 0 20px 24px; border-bottom: 1px solid var(--border); }
    .brand h1 { font-size: 1.1rem; font-weight: 800; background: var(--grad); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .brand p { font-size: 0.72rem; color: var(--muted); margin-top: 2px; }
    .nav-section { flex: 1; padding: 16px 12px; display: flex; flex-direction: column; gap: 4px; }
    .nav-link { display: flex; align-items: center; gap: 12px; padding: 11px 12px; border-radius: 10px; text-decoration: none; color: var(--muted); font-size: 0.88rem; font-weight: 500; transition: all .2s; }
    .nav-link:hover { background: var(--input-bg); color: var(--text); }
    .nav-link.active { background: var(--grad); color: #fff; }
    .nav-link svg { flex-shrink: 0; }
    .nav-link.danger { color: var(--red); }
    .nav-link.danger:hover { background: rgba(239,83,80,.1); color: var(--red); }
    .sidebar-bottom { padding: 12px; border-top: 1px solid var(--border); display: flex; flex-direction: column; gap: 4px; }

    /* ── TOPBAR ── */
    .topbar {
      height: 64px; background: var(--sidebar-bg); border-bottom: 1px solid var(--border);
      display: flex; align-items: center; padding: 0 28px 0 268px; gap: 16px;
      position: fixed; top: 0; left: 0; right: 0; z-index: 200;
    }
    .search-wrap { flex: 1; max-width: 520px; position: relative; }
    .search-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--muted); }
    .search-wrap input { width: 100%; background: var(--input-bg); border: 1.5px solid var(--border); border-radius: 8px; padding: 10px 14px 10px 38px; font-family: inherit; font-size: 0.85rem; color: var(--text); outline: none; transition: border-color .2s; }
    .search-wrap input::placeholder { color: var(--muted); }
    .search-wrap input:focus { border-color: var(--accent-purple); }
    .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 16px; }
    .notif-btn { position: relative; background: none; border: none; color: var(--muted); cursor: pointer; display: flex; align-items: center; transition: color .2s; }
    .notif-btn:hover { color: var(--text); }
    .notif-dot { position: absolute; top: -3px; right: -3px; width: 10px; height: 10px; border-radius: 50%; background: var(--accent-pink); border: 2px solid var(--sidebar-bg); }
    .user-info { display: flex; align-items: center; gap: 10px; }
    .avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--grad); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; color: #fff; flex-shrink: 0; }
    .user-text p:first-child { font-size: 0.85rem; font-weight: 600; }
    .user-text p:last-child  { font-size: 0.72rem; color: var(--muted); }

    /* ── MAIN ── */
    .main { margin-left: 240px; margin-top: 64px; padding: 36px 32px 60px; }

    .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 28px; flex-wrap: wrap; gap: 16px; }
    .page-header h2 { font-size: 1.8rem; font-weight: 800; }
    .page-header p  { font-size: 0.85rem; color: var(--muted); margin-top: 4px; }

    .btn-export {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 12px 22px; background: var(--grad); border: none; border-radius: 10px;
      color: #fff; font-family: inherit; font-size: 0.88rem; font-weight: 700;
      cursor: pointer; text-decoration: none; transition: opacity .2s;
    }
    .btn-export:hover { opacity: .88; }

    /* ── TABLE CARD ── */
    .table-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }

    .filters-row { display: flex; align-items: center; gap: 12px; padding: 20px 24px; border-bottom: 1px solid var(--border); flex-wrap: wrap; }

    .filter-search { flex: 1; min-width: 200px; position: relative; }
    .filter-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--muted); }
    .filter-search input { width: 100%; background: var(--input-bg); border: 1.5px solid var(--border); border-radius: 8px; padding: 10px 14px 10px 38px; font-family: inherit; font-size: 0.85rem; color: var(--text); outline: none; transition: border-color .2s; }
    .filter-search input::placeholder { color: var(--muted); }
    .filter-search input:focus { border-color: var(--accent-purple); }

    .select-wrap { position: relative; flex-shrink: 0; }
    .select-wrap select { appearance: none; background: var(--input-bg); border: 1.5px solid var(--border); border-radius: 8px; padding: 10px 36px 10px 14px; font-family: inherit; font-size: 0.85rem; color: var(--text); outline: none; cursor: pointer; transition: border-color .2s; }
    .select-wrap select:focus { border-color: var(--accent-purple); }
    .select-wrap svg { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; }

    table { width: 100%; border-collapse: collapse; }
    thead tr { border-bottom: 1px solid var(--border); }
    thead th { font-size: 0.72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .6px; padding: 14px 16px; text-align: left; }
    tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--input-bg); }
    tbody td { padding: 14px 16px; font-size: 0.87rem; }

    .client-cell { display: flex; flex-direction: column; }
    .client-name { font-weight: 600; }
    .client-email { font-size: 0.75rem; color: var(--muted); margin-top: 2px; }
    .order-id { color: var(--muted); font-weight: 600; }

    /* BADGES STATUT */
    .badge {
      display: inline-block; padding: 5px 13px; border-radius: 20px;
      font-size: 0.74rem; font-weight: 700; border: 1px solid transparent;
    }
    .badge.livre    { color: #4caf7d; border-color: #4caf7d; background: rgba(76,175,125,.12); }
    .badge.en-cours { color: #2979ff; border-color: #2979ff; background: rgba(41,121,255,.12); }
    .badge.attente  { color: #fbbf24; border-color: #fbbf24; background: rgba(251,191,36,.12); }
    .badge.annule   { color: #ef5350; border-color: #ef5350; background: rgba(239,83,80,.12);  }

    /* BOUTON VOIR */
    .btn-voir {
      width: 32px; height: 32px; border-radius: 7px;
      display: inline-flex; align-items: center; justify-content: center;
      text-decoration: none; border: 1px solid var(--border);
      background: transparent; color: var(--accent-pink); cursor: pointer;
      transition: all .2s;
    }
    .btn-voir:hover { background: rgba(224,64,200,.12); border-color: var(--accent-pink); }

    /* PAGINATION */
    .pagination { display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; border-top: 1px solid var(--border); flex-wrap: wrap; gap: 12px; }
    .pagination p { font-size: 0.8rem; color: var(--muted); }
    .pag-btns { display: flex; gap: 8px; }
    .pag-btn { padding: 8px 18px; border-radius: 8px; font-family: inherit; font-size: 0.83rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; transition: all .2s; }
    .pag-btn.prev { background: var(--input-bg); border: 1px solid var(--border); color: var(--text); }
    .pag-btn.prev:hover { border-color: var(--accent-purple); color: var(--accent-pink); }
    .pag-btn.next { background: var(--grad); border: none; color: #fff; }
    .pag-btn.next:hover { opacity: .88; }

    /* MODAL DÉTAIL COMMANDE */
    .modal-overlay {
      display: none; position: fixed; inset: 0;
      background: rgba(0,0,0,.7); backdrop-filter: blur(4px);
      z-index: 1000; align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal {
      background: var(--card-bg); border: 1px solid var(--border);
      border-radius: 16px; padding: 32px; max-width: 520px; width: 90%;
      position: relative;
    }
    .modal-close {
      position: absolute; top: 16px; right: 16px;
      background: none; border: none; color: var(--muted);
      cursor: pointer; display: flex; align-items: center;
      transition: color .2s;
    }
    .modal-close:hover { color: var(--text); }
    .modal h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; }
    .modal-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border); font-size: 0.87rem; }
    .modal-row:last-child { border-bottom: none; }
    .modal-row span:first-child { color: var(--muted); }
    .modal-row span:last-child  { font-weight: 600; }

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
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="produits.php" class="nav-link">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      Produits
    </a>
    <a href="commandes.php" class="nav-link active">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
      Commandes
    </a>
    <a href="clients.php" class="nav-link">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Clients
    </a>
  </nav>
  <div class="sidebar-bottom">
    <a href="../index.php" class="nav-link">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      Retour au site
    </a>
    <a href="parametres.php" class="nav-link">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Paramètres
    </a>
    <a href="logout.php" class="nav-link danger">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Déconnexion
    </a>
  </div>
</aside>

<!-- ════ TOPBAR ════ -->
<header class="topbar">
  <div class="search-wrap">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
    <input type="text" placeholder="Rechercher..." />
  </div>
  <div class="topbar-right">
    <button class="notif-btn">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      <span class="notif-dot"></span>
    </button>
    <div class="user-info">
      <div class="avatar"><?= strtoupper(substr($_SESSION['nom'], 0, 1)) ?></div>
      <div class="user-text">
        <p><?= htmlspecialchars($_SESSION['nom']) ?></p>
        <p><?= htmlspecialchars($_SESSION['email']) ?></p>
      </div>
    </div>
  </div>
</header>

<!-- ════ MAIN ════ -->
<main class="main">

  <div class="page-header">
    <div>
      <h2>Commandes</h2>
      <p>Gérez toutes vos commandes</p>
    </div>
    <a href="#" class="btn-export" onclick="exportCSV()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Exporter
    </a>
  </div>

  <div class="table-card">

    <!-- Filtres -->
    <div class="filters-row">
      <div class="filter-search">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" placeholder="Rechercher une commande..." id="searchInput" oninput="filterTable()" />
      </div>
      <div class="select-wrap">
        <select id="statFilter" onchange="filterTable()">
          <option value="">Tous les statuts</option>
          <option value="livre">Livré</option>
          <option value="en-cours">En cours</option>
          <option value="attente">En attente</option>
          <option value="annule">Annulé</option>
        </select>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
      <div class="select-wrap">
        <select id="periodeFilter" onchange="filterTable()">
          <option value="">Toutes les périodes</option>
          <option value="7">7 derniers jours</option>
          <option value="30">30 derniers jours</option>
          <option value="90">90 derniers jours</option>
        </select>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>

    <!-- Tableau -->
    <table>
      <thead>
        <tr>
          <th>Commande</th>
          <th>Client</th>
          <th>Date</th>
          <th>Articles</th>
          <th>Montant</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="commandeTable">
        <?php foreach ($commandes as $cmd):
          $ref    = htmlspecialchars($cmd['ref_cmd']);
          $nom    = htmlspecialchars($cmd['nom']);
          $email  = htmlspecialchars($cmd['email']);
          $date   = isset($cmd['date_cmd']) ? date('d M Y', strtotime($cmd['date_cmd'])) : '—';
          $nb     = $cmd['nb_articles'] ?? 1;
          $mtnt   = number_format($cmd['montant_total'], 2, '.', ' ') . '€';
          $stat   = $cmd['statut'];
          $label  = $statuts[$stat]['label'] ?? ucfirst($stat);
          $class  = $statuts[$stat]['class'] ?? $stat;
          // Encode data pour modal
          $dataJson = htmlspecialchars(json_encode([
            'ref'   => $cmd['ref_cmd'],
            'nom'   => $cmd['nom'],
            'email' => $cmd['email'],
            'date'  => $date,
            'nb'    => $nb,
            'mtnt'  => $mtnt,
            'stat'  => $label,
          ]));
        ?>
        <tr data-stat="<?= $class ?>">
          <td class="order-id"><?= $ref ?></td>
          <td>
            <div class="client-cell">
              <span class="client-name"><?= $nom ?></span>
              <span class="client-email"><?= $email ?></span>
            </div>
          </td>
          <td><?= $date ?></td>
          <td><?= $nb ?></td>
          <td><?= $mtnt ?></td>
          <td><span class="badge <?= $class ?>"><?= $label ?></span></td>
          <td>
            <button class="btn-voir" title="Voir le détail" onclick='openModal(<?= $dataJson ?>)'>
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
      <p id="pageInfo">Affichage de 1 à <?= count($commandes) ?> sur <?= count($commandes) ?> commandes</p>
      <div class="pag-btns">
        <a href="#" class="pag-btn prev">Précédent</a>
        <a href="#" class="pag-btn next">Suivant</a>
      </div>
    </div>

  </div>
</main>

<!-- ════ MODAL DÉTAIL ════ -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModalOutside(event)">
  <div class="modal">
    <button class="modal-close" onclick="closeModal()">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <h3 id="modalTitle">Détail commande</h3>
    <div id="modalBody"></div>
  </div>
</div>

<script>
  // ── FILTRES ──
  function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const stat   = document.getElementById('statFilter').value;
    const rows   = document.querySelectorAll('#commandeTable tr');
    let visible  = 0;

    rows.forEach(row => {
      const text     = row.textContent.toLowerCase();
      const rowStat  = row.dataset.stat || '';
      const matchS   = text.includes(search);
      const matchSt  = !stat || rowStat === stat;
      if (matchS && matchSt) { row.style.display = ''; visible++; }
      else                   { row.style.display = 'none'; }
    });

    document.getElementById('pageInfo').textContent =
      `Affichage de 1 à ${visible} sur ${visible} commandes`;
  }

  // ── MODAL ──
  function openModal(data) {
    document.getElementById('modalTitle').textContent = 'Commande ' + data.ref;
    document.getElementById('modalBody').innerHTML = `
      <div class="modal-row"><span>Client</span><span>${data.nom}</span></div>
      <div class="modal-row"><span>Email</span><span>${data.email}</span></div>
      <div class="modal-row"><span>Date</span><span>${data.date}</span></div>
      <div class="modal-row"><span>Nb articles</span><span>${data.nb}</span></div>
      <div class="modal-row"><span>Montant total</span><span>${data.mtnt}</span></div>
      <div class="modal-row"><span>Statut</span><span>${data.stat}</span></div>
    `;
    document.getElementById('modalOverlay').classList.add('open');
  }

  function closeModal() {
    document.getElementById('modalOverlay').classList.remove('open');
  }

  function closeModalOutside(e) {
    if (e.target === document.getElementById('modalOverlay')) closeModal();
  }

  // ── EXPORT CSV ──
  function exportCSV() {
    const rows   = document.querySelectorAll('#commandeTable tr:not([style*="none"])');
    let csv = 'Commande,Client,Email,Date,Articles,Montant,Statut\n';
    rows.forEach(row => {
      const cells = row.querySelectorAll('td');
      if (!cells.length) return;
      const ref   = cells[0].textContent.trim();
      const nom   = cells[1].querySelector('.client-name')?.textContent.trim() || '';
      const email = cells[1].querySelector('.client-email')?.textContent.trim() || '';
      const date  = cells[2].textContent.trim();
      const nb    = cells[3].textContent.trim();
      const mtnt  = cells[4].textContent.trim();
      const stat  = cells[5].textContent.trim();
      csv += `"${ref}","${nom}","${email}","${date}","${nb}","${mtnt}","${stat}"\n`;
    });
    const blob = new Blob([csv], { type: 'text/csv' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href = url; a.download = 'commandes.csv'; a.click();
    URL.revokeObjectURL(url);
  }
</script>
</body>
</html>
