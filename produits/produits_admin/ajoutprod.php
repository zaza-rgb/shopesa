<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Authentification/connect_admin.php');
    exit;
}

// Récupérer erreurs et données précédentes si redirection depuis creer-produit.php
$errors   = $_SESSION['prod_errors'] ?? [];
$old      = $_SESSION['prod_data']   ?? [];
unset($_SESSION['prod_errors'], $_SESSION['prod_data']);
?>
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
      --bg: #0a0c18; --sidebar-bg: #0d0f1a; --card-bg: #141728;
      --input-bg: #1a1f35; --border: #2a2f4a; --text: #e8eaf6;
      --muted: #8890b0; --accent-pink: #e040c8; --accent-purple: #9c27b0;
      --grad: linear-gradient(90deg, #e040c8, #9c27b0); --red: #ef5350; --green: #4caf7d;
    }
    body { background: var(--bg); color: var(--text); font-family: 'Sora', sans-serif; min-height: 100vh; }

    /* SIDEBAR */
    .sidebar { width: 240px; background: var(--sidebar-bg); border-right: 1px solid var(--border); position: fixed; top: 0; left: 0; bottom: 0; display: flex; flex-direction: column; padding: 24px 0 0; z-index: 300; }
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

    /* TOPBAR */
    .topbar { height: 64px; background: var(--sidebar-bg); border-bottom: 1px solid var(--border); display: flex; align-items: center; padding: 0 28px 0 268px; gap: 16px; position: fixed; top: 0; left: 0; right: 0; z-index: 200; }
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
    .avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--grad); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; color: #fff; }
    .user-text p:first-child { font-size: 0.85rem; font-weight: 600; }
    .user-text p:last-child  { font-size: 0.72rem; color: var(--muted); }

    /* MAIN */
    .main { margin-left: 240px; margin-top: 64px; padding: 36px 32px; }
    .page-header { display: flex; align-items: center; gap: 14px; margin-bottom: 32px; }
    .back-btn { width: 34px; height: 34px; background: var(--input-bg); border: 1px solid var(--border); border-radius: 8px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: var(--muted); transition: color .2s, border-color .2s; flex-shrink: 0; }
    .back-btn:hover { color: var(--text); border-color: var(--accent-purple); }
    .page-header h2 { font-size: 1.6rem; font-weight: 800; }
    .page-header p  { font-size: 0.82rem; color: var(--muted); margin-top: 2px; }

    .content-grid { display: grid; grid-template-columns: 1fr 320px; gap: 24px; align-items: start; }

    .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 14px; padding: 28px; margin-bottom: 20px; }
    .card h3 { font-size: 1rem; font-weight: 700; margin-bottom: 22px; }

    .field { margin-bottom: 18px; }
    .field label { display: block; font-size: 0.8rem; font-weight: 600; color: var(--text); margin-bottom: 7px; }
    .field label .req { color: var(--accent-pink); }
    .field input, .field select, .field textarea { width: 100%; background: var(--input-bg); border: 1.5px solid var(--border); border-radius: 8px; padding: 11px 14px; font-family: inherit; font-size: 0.875rem; color: var(--text); outline: none; transition: border-color .2s; appearance: none; }
    .field input::placeholder, .field textarea::placeholder { color: var(--muted); }
    .field input:focus, .field select:focus, .field textarea:focus { border-color: var(--accent-purple); }
    .field textarea { resize: vertical; min-height: 110px; }

    .select-wrap { position: relative; }
    .select-wrap svg { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; }

    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

    /* Upload */
    .upload-zone { width: 140px; height: 140px; background: var(--input-bg); border: 2px dashed var(--border); border-radius: 10px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: border-color .2s, color .2s; color: var(--muted); font-size: 0.78rem; position: relative; overflow: hidden; }
    .upload-zone:hover { border-color: var(--accent-purple); color: var(--accent-pink); }
    .upload-zone img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; }
    .upload-hint { font-size: 0.75rem; color: var(--muted); margin-top: 10px; }

    /* Chips */
    .chips { display: flex; gap: 8px; flex-wrap: wrap; }
    .chip { padding: 8px 16px; background: var(--input-bg); border: 1.5px solid var(--border); border-radius: 8px; font-size: 0.83rem; font-weight: 600; color: var(--text); cursor: pointer; transition: all .2s; user-select: none; }
    .chip:hover { border-color: var(--accent-purple); color: var(--accent-pink); }
    .chip.active { background: var(--grad); border-color: transparent; color: #fff; }
    .variant-label { font-size: 0.82rem; font-weight: 600; color: var(--muted); margin-bottom: 10px; }
    .variant-group { margin-bottom: 20px; }

    /* Aperçu */
    .preview-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 14px; padding: 24px; position: sticky; top: 84px; }
    .preview-card h3 { font-size: 1rem; font-weight: 700; margin-bottom: 18px; }
    .preview-img { width: 100%; aspect-ratio: 1; background: var(--input-bg); border: 1px solid var(--border); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--muted); margin-bottom: 20px; overflow: hidden; }
    .preview-img img { width: 100%; height: 100%; object-fit: cover; }
    .preview-rows { display: flex; flex-direction: column; gap: 14px; margin-bottom: 24px; }
    .preview-row p:first-child { font-size: 0.72rem; color: var(--muted); margin-bottom: 3px; }
    .preview-row p:last-child  { font-size: 0.88rem; font-weight: 600; color: var(--text); }
    .preview-row p:last-child.dash { color: var(--muted); font-weight: 400; }
    .divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }

    .btn-create { width: 100%; padding: 13px; background: var(--grad); border: none; border-radius: 10px; color: #fff; font-family: inherit; font-size: 0.9rem; font-weight: 700; cursor: pointer; transition: opacity .2s; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; }
    .btn-create:hover { opacity: .88; }
    .btn-cancel { width: 100%; padding: 13px; background: transparent; border: 1.5px solid var(--border); border-radius: 10px; color: var(--text); font-family: inherit; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: border-color .2s, color .2s; text-decoration: none; display: flex; align-items: center; justify-content: center; }
    .btn-cancel:hover { border-color: var(--accent-purple); color: var(--accent-pink); }

    /* Alerte erreur */
    .alert-errors { background: rgba(239,83,80,.1); border: 1px solid rgba(239,83,80,.3); border-radius: 10px; padding: 14px 18px; margin-bottom: 20px; }
    .alert-errors p { font-size: 0.83rem; color: #ef5350; line-height: 1.8; }

    /* Alerte succès */
    .alert-success { background: rgba(76,175,125,.1); border: 1px solid rgba(76,175,125,.3); border-radius: 10px; padding: 14px 18px; margin-bottom: 20px; }
    .alert-success p { font-size: 0.83rem; color: #4caf7d; }

    @media (max-width: 1100px) { .content-grid { grid-template-columns: 1fr; } .preview-card { position: static; } }
    @media (max-width: 768px)  { .sidebar { display: none; } .topbar { padding-left: 20px; } .main { margin-left: 0; } .row-2 { grid-template-columns: 1fr; } }
  </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="brand"><h1>ShopStyle Admin</h1><p>Panneau de gestion</p></div>
  <nav class="nav-section">
    <a href="dashboard.php" class="nav-link">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="produits.php" class="nav-link active">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
      Produits
    </a>
    <a href="commandes.php" class="nav-link">
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
    <a href="logout.php" class="nav-link danger">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Déconnexion
    </a>
  </div>
</aside>

<!-- TOPBAR -->
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

<!-- MAIN -->
<main class="main">

  <div class="page-header">
    <a href="produits.php" class="back-btn">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
    </a>
    <div>
      <h2>Ajouter un produit</h2>
      <p>Créez un nouveau produit dans votre catalogue</p>
    </div>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="alert-errors">
      <?php foreach ($errors as $e): ?>
        <p>⚠ <?= htmlspecialchars($e) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert-success"><p>✅ Produit créé avec succès !</p></div>
  <?php endif; ?>

  <!-- FORMULAIRE → action creer-produit.php -->
  <form method="POST" action="creer-produit.php" enctype="multipart/form-data">

    <div class="content-grid">

      <!-- GAUCHE -->
      <div>

        <!-- Infos générales -->
        <div class="card">
          <h3>Informations générales</h3>

          <div class="field">
            <label>Nom du produit <span class="req">*</span></label>
            <input type="text" name="nom" placeholder="Ex: T-Shirt Premium"
                   value="<?= htmlspecialchars($old['nom'] ?? '') ?>" />
          </div>

          <div class="row-2">
            <div class="field">
              <label>Catégorie <span class="req">*</span></label>
              <div class="select-wrap">
                <select name="categorie">
                  <option value="Vêtements"  <?= ($old['categorie']??'') === 'Vêtements'  ? 'selected':'' ?>>Vêtements</option>
                  <option value="Accessoires"<?= ($old['categorie']??'') === 'Accessoires' ? 'selected':'' ?>>Accessoires</option>
                  <option value="Chaussures" <?= ($old['categorie']??'') === 'Chaussures'  ? 'selected':'' ?>>Chaussures</option>
                  <option value="Sacs"       <?= ($old['categorie']??'') === 'Sacs'        ? 'selected':'' ?>>Sacs</option>
                </select>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
              </div>
            </div>
            <div class="field">
              <label>Statut</label>
              <div class="select-wrap">
                <select name="statut">
                  <option value="en_stock"  <?= ($old['statut']??'') === 'en_stock'  ? 'selected':'' ?>>En stock</option>
                  <option value="rupture"   <?= ($old['statut']??'') === 'rupture'   ? 'selected':'' ?>>Rupture de stock</option>
                  <option value="desactive" <?= ($old['statut']??'') === 'desactive' ? 'selected':'' ?>>Désactivé</option>
                </select>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
              </div>
            </div>
          </div>

          <div class="row-2">
            <div class="field">
              <label>Prix (€) <span class="req">*</span></label>
              <input type="number" name="prix" placeholder="49.99" step="0.01" min="0"
                     value="<?= htmlspecialchars($old['prix'] ?? '') ?>" />
            </div>
            <div class="field">
              <label>Stock <span class="req">*</span></label>
              <input type="number" name="stock" placeholder="100" min="0"
                     value="<?= htmlspecialchars($old['stock'] ?? '') ?>" />
            </div>
          </div>

          <div class="field">
            <label>Description <span class="req">*</span></label>
            <textarea name="description" placeholder="Décrivez votre produit en détail..."><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
          </div>
        </div>

        <!-- Image -->
        <div class="card">
          <h3>Image du produit</h3>
          <div class="upload-zone" id="uploadZone" onclick="document.getElementById('file-input').click()">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
            <span id="uploadLabel">Ajouter une image</span>
            <input type="file" id="file-input" name="image" accept="image/*" style="display:none" onchange="previewImage(this)" />
          </div>
          <p class="upload-hint">Recommandé : Images carrées 1000×1000px minimum (jpg, png, webp)</p>
        </div>

        <!-- Variantes -->
        <div class="card">
          <h3>Variantes</h3>
          <div class="variant-group">
            <p class="variant-label">Tailles disponibles</p>
            <div class="chips" id="taillesChips">
              <?php foreach (['XS','S','M','L','XL','XXL'] as $t):
                $active = isset($old['tailles']) && in_array($t, $old['tailles']) ? 'active' : '';
              ?>
                <span class="chip <?= $active ?>" onclick="toggleChip(this,'tailles')"
                      data-val="<?= $t ?>"><?= $t ?></span>
              <?php endforeach; ?>
            </div>
            <input type="hidden" name="tailles[]" id="taillesInput" value="" />
          </div>
          <div class="variant-group">
            <p class="variant-label">Couleurs disponibles</p>
            <div class="chips" id="couleursChips">
              <?php foreach (['Noir','Blanc','Gris','Bleu','Rouge','Vert','Rose','Violet'] as $c):
                $active = isset($old['couleurs']) && in_array($c, $old['couleurs']) ? 'active' : '';
              ?>
                <span class="chip <?= $active ?>" onclick="toggleChip(this,'couleurs')"
                      data-val="<?= $c ?>"><?= $c ?></span>
              <?php endforeach; ?>
            </div>
            <input type="hidden" name="couleurs[]" id="couleursInput" value="" />
          </div>
        </div>
      </div>

      <!-- DROITE : Aperçu -->
      <div class="preview-card">
        <h3>Aperçu</h3>
        <div class="preview-img" id="previewImgWrap">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
        </div>
        <div class="preview-rows">
          <div class="preview-row"><p>Catégorie</p><p id="prev-cat">Vêtements</p></div>
          <div class="preview-row"><p>Nom</p><p id="prev-name" class="dash">—</p></div>
          <div class="preview-row"><p>Prix</p><p id="prev-price" class="dash">—</p></div>
          <div class="preview-row"><p>Stock</p><p id="prev-stock" class="dash">—</p></div>
          <div class="preview-row"><p>Tailles</p><p id="prev-sizes" class="dash">—</p></div>
          <div class="preview-row"><p>Couleurs</p><p id="prev-colors" class="dash">—</p></div>
        </div>
        <hr class="divider" />
        <button type="submit" class="btn-create">Créer le produit</button>
        <a href="produits.php" class="btn-cancel">Annuler</a>
      </div>

    </div>
  </form>

</main>

<script>
  // ── Aperçu image ──
  function previewImage(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = e => {
        const wrap = document.getElementById('previewImgWrap');
        wrap.innerHTML = `<img src="${e.target.result}" alt="aperçu" />`;
        document.getElementById('uploadZone').innerHTML =
          `<img src="${e.target.result}" alt="aperçu" /><input type="file" id="file-input" name="image" accept="image/*" style="display:none" onchange="previewImage(this)" />`;
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  // ── Chips variantes → hidden inputs ──
  function toggleChip(el, group) {
    el.classList.toggle('active');
    updateHiddenInputs(group);
    updatePreview();
  }

  function updateHiddenInputs(group) {
    const chips    = document.querySelectorAll(`#${group}Chips .chip.active`);
    const vals     = [...chips].map(c => c.dataset.val);
    // Créer un input par valeur active
    const form = document.querySelector('form');
    // Supprimer anciens inputs cachés de ce groupe
    document.querySelectorAll(`input[name="${group}[]"]`).forEach(i => i.remove());
    vals.forEach(v => {
      const inp = document.createElement('input');
      inp.type  = 'hidden';
      inp.name  = `${group}[]`;
      inp.value = v;
      form.appendChild(inp);
    });
  }

  // ── Aperçu texte ──
  function updatePreview() {
    const name  = document.querySelector('input[name="nom"]').value;
    const price = document.querySelector('input[name="prix"]').value;
    const stock = document.querySelector('input[name="stock"]').value;
    const cat   = document.querySelector('select[name="categorie"]').value;

    const el = (id, val, fallback='—') => {
      const p = document.getElementById(id);
      p.textContent = val || fallback;
      p.className   = val ? '' : 'dash';
    };

    el('prev-name', name);
    el('prev-price', price ? price + '€' : '');
    el('prev-stock', stock);
    document.getElementById('prev-cat').textContent = cat;

    const sizes  = [...document.querySelectorAll('#taillesChips  .chip.active')].map(c => c.dataset.val);
    const colors = [...document.querySelectorAll('#couleursChips .chip.active')].map(c => c.dataset.val);
    el('prev-sizes',  sizes.join(', '));
    el('prev-colors', colors.join(', '));
  }

  document.querySelectorAll('input[name="nom"], input[name="prix"], input[name="stock"], select[name="categorie"]')
    .forEach(el => el.addEventListener('input', updatePreview));

  // Init aperçu si données récupérées (après erreur)
  updatePreview();
</script>
</body>
</html>
