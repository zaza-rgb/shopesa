





<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopEsa Admin – Connexion</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/connect_admin.css" />
</head>
<body>

  <div class="logo-wrap">
    <div class="logo-icon">
      <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
    </div>
    <span class="logo-title">ShopEsa Admin</span>
    <span class="logo-sub">Espace d'administration sécurisé</span>
  </div>

  <div class="card">
    <h2 class="card-title">Connexion Admin</h2>

    <div class="field">
      <label for="email">Email administrateur</label>
      <div class="input-wrap">
        <span class="icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 7 10-7"/>
          </svg>
        </span>
        <input type="email" id="email" name="email" placeholder="admin@shopstyle.com" />
      </div>
    </div>

    <div class="field">
      <label for="password">Mot de passe</label>
      <div class="input-wrap">
        <span class="icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </span>
        <input type="password" id="password" name="pwd" placeholder="••••••••" />
        <button class="eye-btn" type="button" onclick="togglePwd()">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/>
          </svg>
        </button>
      </div>
    </div>

    <div class="row">
      <label class="remember">
        <input type="checkbox" id="remember" name="rmb"/> Rester connecté
      </label>
      <a href="#" class="forgot">Mot de passe oublié ?</a>
    </div>

    <button class="btn-connect" type="button">Se connecter</button>

    <div class="test-box">
      <strong>Informations de test :</strong><br/>
      Email : <span>admin@shop.com</span><br/>
      Mot de passe : <span>admin123</span>
    </div>
  </div>

  <a href="#" class="back-link">← Retour au site</a>

  <div class="secure-box">
    <div class="secure-icon">
      <svg width="18" height="18" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
    </div>
    <div class="secure-text">
      <h4>Accès sécurisé</h4>
      <p>Cette page est réservée aux administrateurs du site. Toutes les connexions sont enregistrées et surveillées.</p>
    </div>
  </div>

  <script>
    function togglePwd() {
      const input = document.getElementById('password');
      input.type = input.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>