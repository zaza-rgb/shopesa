<?php
session_start();

// Si déjà connecté en admin → rediriger
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
   header('Location: ../produits_admin/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['pwd']   ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        // Connexion BDD
       require_once '../liaison.php';

        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ? AND role = 'admin' LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['ref_uti']  = $user['ref_uti'];
            $_SESSION['nom']      = $user['nom'];
            $_SESSION['email']    = $user['email'];
            $_SESSION['role']     = $user['role'];

            // Rester connecté (30 jours)
            if (isset($_POST['rmb'])) {
                setcookie('admin_email', $email, time() + (86400 * 30), '/');
            }
             header('Location: ../produits_admin/dashboard.php');
            exit;
        } else {
            $error = 'Email ou mot de passe incorrect.';
        }
    }
}
?>
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

    <?php if ($error): ?>
      <div class="error-box">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">

      <div class="field">
        <label for="email">Email administrateur</label>
        <div class="input-wrap">
          <span class="icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 7 10-7"/>
            </svg>
          </span>
          <input type="email" id="email" name="email"
                 placeholder="admin@shopstyle.com"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                 required />
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
          <input type="password" id="password" name="pwd" placeholder="••••••••" required />
          <button class="eye-btn" type="button" onclick="togglePwd()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="row">
        <label class="remember">
          <input type="checkbox" name="rmb" id="remember" /> Rester connecté
        </label>
        <a href="mot-de-passe-oublie.php" class="forgot">Mot de passe oublié ?</a>
      </div>

      <button type="submit" class="btn-connect">Se connecter</button>

    </form>

    <div class="test-box">
      <strong>Informations de test :</strong><br/>
      Email : <span>admin@shop.com</span><br/>
      Mot de passe : <span>admin123</span>
    </div>
  </div>

  <a href="../index.php" class="back-link">← Retour au site</a>

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