<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopStyle Admin – Ajouter un produit</title>
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
      --red:          #ef5350;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Sora', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
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
      padding: 0 28px 0 260px;
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

    .user-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }

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

    .user-text p:first-child {
      font-size: 0.85rem;
      font-weight: 600;
    }

    .user-text p:last-child {
      font-size: 0.72rem;
      color: var(--muted);
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

    .brand p {
      font-size: 0.72rem;
      color: var(--muted);
      margin-top: 2px;
    }

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

    .nav-link.active {
      background: var(--grad);
      color: #fff;
    }

    .nav-link svg { flex-shrink: 0; }

    .sidebar-bottom {
      padding: 12px;
      border-top: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .nav-link.danger { color: var(--red); }
    .nav-link.danger:hover { background: rgba(239,83,80,.1); color: var(--red); }

    /* ════════════════════════════
       MAIN
    ════════════════════════════ */
    .main {
      margin-left: 240px;
      margin-top: 64px;
      padding: 36px 32px;
      min-height: calc(100vh - 64px);
    }

    /* Page header */
    .page-header {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 32px;
    }

    .back-btn {
      width: 34px; height: 34px;
      background: var(--input-bg);
      border: 1px solid var(--border);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      color: var(--muted);
      transition: color .2s, border-color .2s;
      flex-shrink: 0;
    }

    .back-btn:hover { color: var(--text); border-color: var(--accent-purple); }

    .page-header h2 { font-size: 1.6rem; font-weight: 800; }
    .page-header p  { font-size: 0.82rem; color: var(--muted); margin-top: 2px; }

    /* Content grid */
    .content-grid {
      display: grid;
      grid-template-columns: 1fr 320px;
      gap: 24px;
      align-items: start;
    }

    /* ── CARDS ── */
    .card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 28px;
      margin-bottom: 20px;
    }

    .card h3 {
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 22px;
    }

    /* Fields */
    .field { margin-bottom: 18px; }

    .field label {
      display: block;
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 7px;
    }

    .field label .req { color: var(--accent-pink); }

    .field input,
    .field select,
    .field textarea {
      width: 100%;
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: 8px;
      padding: 11px 14px;
      font-family: inherit;
      font-size: 0.875rem;
      color: var(--text);
      outline: none;
      transition: border-color .2s;
      appearance: none;
    }

    .field input::placeholder,
    .field textarea::placeholder { color: var(--muted); }

    .field input:focus,
    .field select:focus,
    .field textarea:focus { border-color: var(--accent-purple); }

    .field textarea { resize: vertical; min-height: 110px; }

    /* Select arrow */
    .select-wrap { position: relative; }

    .select-wrap svg {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
      pointer-events: none;
    }

    /* Two columns row */
    .row-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    /* Image upload */
    .upload-zone {
      width: 140px;
      height: 140px;
      background: var(--input-bg);
      border: 2px dashed var(--border);
      border-radius: 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 8px;
      cursor: pointer;
      transition: border-color .2s, color .2s;
      color: var(--muted);
      font-size: 0.78rem;
    }

    .upload-zone:hover {
      border-color: var(--accent-purple);
      color: var(--accent-pink);
    }

    .upload-hint {
      font-size: 0.75rem;
      color: var(--muted);
      margin-top: 10px;
    }

    /* Variant chips */
    .chips { display: flex; gap: 8px; flex-wrap: wrap; }

    .chip {
      padding: 8px 16px;
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: 8px;
      font-size: 0.83rem;
      font-weight: 600;
      color: var(--text);
      cursor: pointer;
      transition: all .2s;
      user-select: none;
    }

    .chip:hover { border-color: var(--accent-purple); color: var(--accent-pink); }
    .chip.active { background: var(--grad); border-color: transparent; color: #fff; }

    .variant-label {
      font-size: 0.82rem;
      font-weight: 600;
      color: var(--muted);
      margin-bottom: 10px;
    }

    .variant-group { margin-bottom: 20px; }

    /* ── APERÇU (right col) ── */
    .preview-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 24px;
      position: sticky;
      top: 84px;
    }

    .preview-card h3 {
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 18px;
    }

    .preview-img {
      width: 100%;
      aspect-ratio: 1;
      background: var(--input-bg);
      border: 1px solid var(--border);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--muted);
      margin-bottom: 20px;
    }

    .preview-rows { display: flex; flex-direction: column; gap: 14px; margin-bottom: 24px; }

    .preview-row p:first-child {
      font-size: 0.72rem;
      color: var(--muted);
      margin-bottom: 3px;
    }

    .preview-row p:last-child {
      font-size: 0.88rem;
      font-weight: 600;
      color: var(--text);
    }

    .preview-row p:last-child.dash { color: var(--muted); font-weight: 400; }

    .divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }

    /* Buttons */
    .btn-create {
      width: 100%;
      padding: 13px;
      background: var(--grad);
      border: none;
      border-radius: 10px;
      color: #fff;
      font-family: inherit;
      font-size: 0.9rem;
      font-weight: 700;
      cursor: pointer;
      transition: opacity .2s;
      margin-bottom: 10px;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .btn-create:hover { opacity: .88; }

    .btn-cancel {
      width: 100%;
      padding: 13px;
      background: transparent;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      color: var(--text);
      font-family: inherit;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      transition: border-color .2s, color .2s;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .btn-cancel:hover { border-color: var(--accent-purple); color: var(--accent-pink); }

    /* ── RESPONSIVE ── */
    @media (max-width: 1100px) {
      .content-grid { grid-template-columns: 1fr; }
      .preview-card { position: static; }
    }

    @media (max-width: 768px) {
      .sidebar { display: none; }
      .topbar  { padding-left: 20px; }
      .main    { margin-left: 0; }
      .row-2   { grid-template-columns: 1fr; }
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
          <line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
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
      <a href="../../index.php" class="nav-link">
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
      <a href="../../Authentification/connect_admin.php" class="nav-link danger">
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
      <a href="produits.php" class="back-btn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
      </a>
      <div>
        <h2>Ajouter un produit <?php require '../../liaison.php';
        if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    if (!empty($_POST['nom']) && !empty($_POST['categorie']) && !empty($_POST['statut']) 
        && !empty($_POST['stock']) && !empty($_POST['prix']) && !empty($_POST['description'])) {
    $nomp = $_POST['nom'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    $statut = $_POST['statut'];
    $categorie = $_POST['categorie'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../image/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $stmt = $com->prepare("INSERT INTO produit (nomprod, prix, description, image, stock, statut, id_cat)
                       VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nomp, $prix, $description, $image, $stock, $statut, $categorie])) {
                echo "     ---notification:Produit ajouté avec succès !";
            } else {
                echo "    ---Erreur lors de l'insertion.";
            }
        } else {
            echo "---erreur :Erreur lors de l'upload de l'image.";
        }
    } else {
        echo "---erreur :Vous devez obligatoirement choisir une image pour ce produit.";
    }
}else {
        echo "---erreur :Vous devez obligatoirement saisir les infos pour ce produit.";
    }}
?>
</h2>
        <p>Créez un nouveau produit dans votre catalogue</p>
      </div>
    </div>
    <form method="POST" action="ajoutprod.php" enctype="multipart/form-data">
    <div class="content-grid">

      <!-- ── LEFT : Formulaire ── -->
      <div>

        <!-- Informations générales -->
        <div class="card">
          <h3>Informations générales</h3>

          <div class="field">
            <label>Nom du produit <span class="req">*</span></label>
            <input name="nom" type="text" placeholder="Ex: T-Shirt Premium" />
          </div>

          <div class="row-2">
            <div class="field">
              <label>Catégorie <span class="req">*</span></label>
              <div class="select-wrap">
                <select name="categorie">
                <?php
                require '../../liaison.php'; 
                try {
                    $stmt = $com->query("SELECT id_cat, nom FROM categorie");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // Chaque option a comme valeur l'id_cat et affiche le nom
                        echo '<option value="'.$row['id_cat'].'">'.$row['nom'].'</option>';
                    }
                } catch (PDOException $e) {
                    echo "<option>Erreur : " . $e->getMessage() . "</option>";
                }
                ?>
              </select>

                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="6 9 12 15 18 9"/>
                </svg>
              </div>
            </div>
            <div class="field">
              <label>Statut</label>
              <div class="select-wrap">
                <select name="statut">
                  <option>actif</option>
                  <option>archive</option>
                </select>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="6 9 12 15 18 9"/>
                </svg>
              </div>
            </div>
          </div>

          <div class="row-2">
            <div class="field">
              <label>Prix (FCFA) <span class="req">*</span></label>
              <input name="prix" type="number" placeholder="5000" step="500" min="500" />
            </div>
            <div class="field">
              <label>Stock <span class="req">*</span></label>
              <input name="stock" type="number" placeholder="100" min="0" />
            </div>
          </div>

          <div class="field">
            <label>Description <span class="req">*</span></label>
            <textarea name="description" placeholder="Décrivez votre produit en détail..."></textarea>
          </div>
        </div>

        <!-- Images -->
        <div class="card">
              <label for="image">Image du produit <span class="req">*</span></label>
              <input class="upload-zone" type="file" name="image" id="file-input" accept="image/*" />
        
          <p class="upload-hint">Recommandé : Images carrées de 1000x1000px minimum</p>
        </div>

        <!-- Variantes -->
        <div class="card">
          <h3>Variantes</h3>

          <div class="variant-group">
            <p class="variant-label">Tailles disponibles</p>
            <div class="chips">
              <span class="chip" onclick="toggleChip(this)">XS</span>
              <span class="chip" onclick="toggleChip(this)">S</span>
              <span class="chip" onclick="toggleChip(this)">M</span>
              <span class="chip" onclick="toggleChip(this)">L</span>
              <span class="chip" onclick="toggleChip(this)">XL</span>
              <span class="chip" onclick="toggleChip(this)">XXL</span>
            </div>
          </div>

          <div class="variant-group">
            <p class="variant-label">Couleurs disponibles</p>
            <div class="chips">
              <span class="chip" onclick="toggleChip(this)">Noir</span>
              <span class="chip" onclick="toggleChip(this)">Blanc</span>
              <span class="chip" onclick="toggleChip(this)">Gris</span>
              <span class="chip" onclick="toggleChip(this)">Bleu</span>
              <span class="chip" onclick="toggleChip(this)">Rouge</span>
              <span class="chip" onclick="toggleChip(this)">Vert</span>
              <span class="chip" onclick="toggleChip(this)">Rose</span>
              <span class="chip" onclick="toggleChip(this)">Violet</span>
            </div>
          </div>
        </div>

      </div>

      <!-- ── RIGHT : Aperçu ── -->


      <div class="preview-card">
        <h3>Aperçu</h3>

        <div class="preview-img">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="16 16 12 12 8 16"/>
            <line x1="12" y1="12" x2="12" y2="21"/>
            <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
          </svg>
        </div>

        <div class="preview-rows">
          <div class="preview-row">
            <p>Catégorie</p>
            <p id="prev-cat">-</p>
          </div>
          <div class="preview-row">
            <p>Nom</p>
            <p id="prev-name" class="dash">—</p>
          </div>
          <div class="preview-row">
            <p>Prix</p>
            <p id="prev-price" class="dash">—</p>
          </div>
          <div class="preview-row">
            <p>Stock</p>
            <p id="prev-stock" class="dash">—</p>
          </div>
          <div class="preview-row">
            <p>Tailles</p>
            <p id="prev-sizes" class="dash">—</p>
          </div>
          <div class="preview-row">
            <p>Couleurs</p>
            <p id="prev-colors" class="dash">—</p>
          </div>
        </div>

        <hr class="divider" />
        <button class="btn-create">Créer le produit</button>
        <a href="" class="btn-cancel">Annuler</a>
      </div>

    </div>
    </form>
  </main>

  <script>
    // Toggle chip actif
    function toggleChip(el) {
      el.classList.toggle('active');
      updatePreview();
    }

    // Mise à jour aperçu en temps réel
    function updatePreview() {
      // Nom
      const name = document.querySelector('input[placeholder="Ex: T-Shirt Premium"]').value;
      const pName = document.getElementById('prev-name');
      pName.textContent = name || '—';
      pName.className = name ? '' : 'dash';

      // Prix
      const price = document.querySelector('input[placeholder="5000"]').value;
      const pPrice = document.getElementById('prev-price');
      pPrice.textContent = price ? price + 'FCFA' : '—';
      pPrice.className = price ? '' : 'dash';

      // Stock
      const stock = document.querySelector('input[placeholder="100"]').value;
      const pStock = document.getElementById('prev-stock');
      pStock.textContent = stock || '—';
      pStock.className = stock ? '' : 'dash';

      // Catégorie
      const cat = document.querySelector('select').value;
      document.getElementById('prev-cat').textContent = cat;

      // Tailles actives
      const sizes = [...document.querySelectorAll('.chips')][0]
        .querySelectorAll('.chip.active');
      const pSizes = document.getElementById('prev-sizes');
      pSizes.textContent = sizes.length ? [...sizes].map(s => s.textContent).join(', ') : '—';
      pSizes.className = sizes.length ? '' : 'dash';

      // Couleurs actives
      const colors = [...document.querySelectorAll('.chips')][1]
        .querySelectorAll('.chip.active');
      const pColors = document.getElementById('prev-colors');
      pColors.textContent = colors.length ? [...colors].map(c => c.textContent).join(', ') : '—';
      pColors.className = colors.length ? '' : 'dash';
    }

    // Écoute en temps réel
    document.querySelectorAll('input, select, textarea').forEach(el => {
      el.addEventListener('input', updatePreview);
    });
  </script>
</body>
</html>