<?php
session_start();
require_once '../PHPMailer/PHPMailerAutoload.php';

// Si déjà connecté en client → rediriger
if (isset($_SESSION['role']) && $_SESSION['role'] === 'client') {
    header('Location: ../index.php');
    exit;
}

require_once '../liaison.php';

$error_login    = '';
$error_register = '';
$success        = '';
$active_tab     = 'login'; // quel onglet afficher par défaut

/* ══════════════════════════════════════
   TRAITEMENT CONNEXION
══════════════════════════════════════ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error_login = 'Veuillez remplir tous les champs.';
        $active_tab  = 'login';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ? AND role = 'client' LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['ref_uti'] = $user['ref_uti'];
            $_SESSION['nom']     = $user['nom'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role'];
            header('Location: ../index.php');
            exit;
        } else {
            $error_login = 'Email ou mot de passe incorrect.';
            $active_tab  = 'login';
        }
    }
}

/* ══════════════════════════════════════
   TRAITEMENT INSCRIPTION
══════════════════════════════════════ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $nom      = trim($_POST['nom']       ?? '');
    $email    = trim($_POST['email_reg'] ?? '');
    $password = trim($_POST['pwd_reg']   ?? '');
    $confirm  = trim($_POST['pwd_confirm']?? '');
    $active_tab = 'register';

    if (empty($nom) || empty($email) || empty($password) || empty($confirm)) {
        $error_register = 'Veuillez remplir tous les champs.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_register = 'Adresse email invalide.';
    } elseif ($password !== $confirm) {
        $error_register = 'Les mots de passe ne correspondent pas.';
    } elseif (strlen($password) < 6) {
        $error_register = 'Le mot de passe doit contenir au moins 6 caractères.';
    } else {
        // Vérifier si email existe déjà
        $stmt = $pdo->prepare("SELECT ref_uti FROM utilisateur WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error_register = 'Cet email est déjà utilisé.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO utilisateur (nom, email, password, role) VALUES (?, ?, ?, 'client')");


           if ( $stmt->execute([$nom, $email, $hash])){
                
                    // Connexion automatique après inscription
                    $id = $pdo->lastInsertId();
                    $_SESSION['ref_uti'] = $id;
                    $_SESSION['nom']     = $nom;
                    $_SESSION['email']   = $email;
                    $_SESSION['role']    = 'client';

        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'contact.brouhanoudine@gmail.com';                 // SMTP username
        $mail->Password = 'ysnu rrrr qxxy ppgw';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->setFrom('contact.brouhanoudine@gmail.com', 'ShopEsa');
        $mail->addAddress($email, $nom);     // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Here is the subject';
        $mail->Body    = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Document</title>
        </head>
        <body>
          <h1>Bienvenue sur ShopEsa, ' . htmlspecialchars($nom) . ' !</h1>
          <p>Merci de vous être inscrit. Nous sommes ravis de vous compter parmi nos clients.</p>
          <p>Vous pouvez dès à présent explorer notre boutique et découvrir nos produits exclusifs.</p>
          <p>Si vous avez des questions ou besoin d\'assistance, n\'hésitez pas à nous contacter.</p>
          <p>Bonne shopping !</p>
        </body>
        </html>
        ';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
           echo 'Message could not be sent.';
           echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            header('Location: ../index.php');
            exit;
        }

                   
                }
            }
        }
                
           }
        ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopStyle – Connexion</title>
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
      --red:          #ef5350;
      --green:        #4caf7d;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Sora', sans-serif;
      min-height: 100vh;
      display: flex;
    }

    /* ── CÔTÉ GAUCHE ── */
    .left {
      flex: 1;
      background: linear-gradient(135deg, #e040c8 0%, #9c27b0 50%, #4a0080 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 40px;
      position: relative;
      overflow: hidden;
    }

    .left::before {
      content: '';
      position: absolute;
      width: 400px; height: 400px;
      border-radius: 50%;
      background: rgba(255,255,255,.06);
      top: -100px; left: -100px;
    }

    .left::after {
      content: '';
      position: absolute;
      width: 300px; height: 300px;
      border-radius: 50%;
      background: rgba(255,255,255,.04);
      bottom: -80px; right: -80px;
    }

    .left-content {
      text-align: center;
      z-index: 1;
      position: relative;
    }

    .left-icon {
      font-size: 4rem;
      margin-bottom: 24px;
      display: block;
    }

    .left-content h1 {
      font-size: 2.2rem;
      font-weight: 800;
      color: #fff;
      line-height: 1.2;
      margin-bottom: 16px;
    }

    .left-content p {
      font-size: 1rem;
      color: rgba(255,255,255,.8);
      line-height: 1.6;
      max-width: 320px;
    }

    .left-features {
      margin-top: 36px;
      display: flex;
      flex-direction: column;
      gap: 14px;
      text-align: left;
    }

    .left-feature {
      display: flex;
      align-items: center;
      gap: 12px;
      color: rgba(255,255,255,.9);
      font-size: 0.88rem;
    }

    .left-feature span {
      width: 28px; height: 28px;
      border-radius: 8px;
      background: rgba(255,255,255,.15);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      flex-shrink: 0;
    }

    /* ── CÔTÉ DROIT ── */
    .right {
      width: 520px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 48px 40px;
      overflow-y: auto;
    }

    .form-box {
      width: 100%;
      max-width: 420px;
    }

    .logo {
      font-size: 1.6rem;
      font-weight: 800;
      background: var(--grad);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 32px;
      display: inline-block;
    }

    .form-box h2 {
      font-size: 1.6rem;
      font-weight: 800;
      margin-bottom: 4px;
    }

    .subtitle {
      font-size: 0.88rem;
      color: var(--muted);
      margin-bottom: 28px;
    }

    /* TABS */
    .tabs {
      display: flex;
      background: var(--input-bg);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 4px;
      margin-bottom: 28px;
    }

    .tabs button {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: 8px;
      font-family: inherit;
      font-size: 0.88rem;
      font-weight: 600;
      cursor: pointer;
      background: transparent;
      color: var(--muted);
      transition: all .2s;
    }

    .tabs button.active {
      background: var(--grad);
      color: #fff;
    }

    /* FORM FIELDS */
    .field { margin-bottom: 18px; }

    .field label {
      display: block;
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 7px;
    }

    .input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-wrap .ico {
      position: absolute;
      left: 13px;
      color: var(--muted);
      display: flex;
    }

    .input-wrap input {
      width: 100%;
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: 10px;
      padding: 12px 14px 12px 40px;
      font-family: inherit;
      font-size: 0.88rem;
      color: var(--text);
      outline: none;
      transition: border-color .2s;
    }

    .input-wrap input::placeholder { color: var(--muted); }
    .input-wrap input:focus { border-color: var(--accent-purple); }

    .eye-btn {
      position: absolute;
      right: 13px;
      background: none;
      border: none;
      cursor: pointer;
      color: var(--muted);
      display: flex;
      padding: 0;
    }

    .options {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 22px;
      flex-wrap: wrap;
      gap: 8px;
    }

    .options label {
      display: flex;
      align-items: center;
      gap: 7px;
      font-size: 0.83rem;
      color: var(--muted);
      cursor: pointer;
    }

    .options input[type="checkbox"] {
      width: 15px; height: 15px;
      accent-color: var(--accent-purple);
    }

    .options a {
      font-size: 0.83rem;
      color: var(--accent-pink);
      text-decoration: none;
      font-weight: 500;
    }

    .options a:hover { text-decoration: underline; }

    /* BOUTON PRINCIPAL */
    .btn-main {
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 10px;
      background: var(--grad);
      color: #fff;
      font-family: inherit;
      font-size: 0.95rem;
      font-weight: 700;
      cursor: pointer;
      transition: opacity .2s, transform .1s;
    }

    .btn-main:hover  { opacity: .9; }
    .btn-main:active { transform: scale(.98); }

    /* ERREUR / SUCCÈS */
    .alert {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      padding: 13px 16px;
      border-radius: 10px;
      font-size: 0.83rem;
      margin-bottom: 18px;
    }

    .alert.error {
      background: rgba(239,83,80,.1);
      border: 1px solid rgba(239,83,80,.3);
      color: #ef5350;
    }

    .alert.success {
      background: rgba(76,175,125,.1);
      border: 1px solid rgba(76,175,125,.3);
      color: #4caf7d;
    }

    /* DIVIDER */
    .divider-line {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 24px 0;
      color: var(--muted);
      font-size: 0.8rem;
    }

    .divider-line::before,
    .divider-line::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--border);
    }

    /* SOCIAL */
    .social {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-bottom: 20px;
    }

    .social-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 11px;
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: 10px;
      color: var(--text);
      font-family: inherit;
      font-size: 0.83rem;
      font-weight: 600;
      cursor: pointer;
      transition: border-color .2s;
    }

    .social-btn:hover { border-color: var(--accent-purple); }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      font-size: 0.83rem;
      color: var(--muted);
      text-decoration: none;
      transition: color .2s;
    }

    .back-link:hover { color: var(--text); }

    .hidden { display: none; }

    /* RESPONSIVE */
    @media (max-width: 900px) {
      .left { display: none; }
      .right { width: 100%; }
    }
  </style>
</head>
<body>

  <!-- GAUCHE -->
  <div class="left">
    <div class="left-content">
      <span class="left-icon">🛍️</span>
      <h1>Bienvenue sur ShopStyle</h1>
      <p>Découvrez les dernières tendances mode et créez votre style unique</p>
      <div class="left-features">
        <div class="left-feature">
          <span>🚀</span>
          <p>Livraison rapide en 24-48h</p>
        </div>
        <div class="left-feature">
          <span>🔒</span>
          <p>Paiement 100% sécurisé</p>
        </div>
        <div class="left-feature">
          <span>↩️</span>
          <p>Retour gratuit sous 30 jours</p>
        </div>
      </div>
    </div>
  </div>

  <!-- DROITE -->
  <div class="right">
    <div class="form-box">
      <div class="logo">ShopStyle</div>

      <h2 id="formTitle"><?= $active_tab === 'register' ? 'Inscription' : 'Connexion' ?></h2>
      <p class="subtitle" id="formSubtitle"><?= $active_tab === 'register' ? 'Créez votre compte !' : 'Bon retour parmi nous !' ?></p>

      <!-- TABS -->
      <div class="tabs">
        <button id="loginTab" class="<?= $active_tab === 'login' ? 'active' : '' ?>" onclick="showTab('login')">Connexion</button>
        <button id="registerTab" class="<?= $active_tab === 'register' ? 'active' : '' ?>" onclick="showTab('register')">Inscription</button>
      </div>

      <!-- ══ FORMULAIRE CONNEXION ══ -->
      <form id="loginForm" method="POST" action="" class="<?= $active_tab === 'register' ? 'hidden' : '' ?>">
        <input type="hidden" name="action" value="login" />

        <?php if ($error_login): ?>
          <div class="alert error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <?= htmlspecialchars($error_login) ?>
          </div>
        <?php endif; ?>

        <div class="field">
          <label for="email_login">Email</label>
          <div class="input-wrap">
            <span class="ico">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 7 10-7"/></svg>
            </span>
            <input type="email" id="email_login" name="email" placeholder="email@exemple.com"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required />
          </div>
        </div>

        <div class="field">
          <label for="pwd_login">Mot de passe</label>
          <div class="input-wrap">
            <span class="ico">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input type="password" id="pwd_login" name="password" placeholder="••••••••" required />
            <button class="eye-btn" type="button" onclick="toggleEye('pwd_login', this)">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <div class="options">
          <label><input type="checkbox" name="remember" /> Se souvenir de moi</label>
          <a href="#">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn-main">Se connecter</button>

        <div class="divider-line">Ou continuer avec</div>

        <div class="social">
          <button type="button" class="social-btn">
            <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
            Google
          </button>
          <button type="button" class="social-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            Facebook
          </button>
        </div>

      </form>

      <!-- ══ FORMULAIRE INSCRIPTION ══ -->
      <form id="registerForm" method="POST" action="" class="<?= $active_tab === 'register' ? '' : 'hidden' ?>">
        <input type="hidden" name="action" value="register" />

        <?php if ($error_register): ?>
          <div class="alert error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <?= htmlspecialchars($error_register) ?>
          </div>
        <?php endif; ?>

        <div class="field">
          <label>Nom complet</label>
          <div class="input-wrap">
            <span class="ico">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            <input type="text" name="nom" placeholder="Votre nom complet"
                   value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required />
          </div>
        </div>

        <div class="field">
          <label>Email</label>
          <div class="input-wrap">
            <span class="ico">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 7 10-7"/></svg>
            </span>
            <input type="email" name="email_reg" placeholder="email@exemple.com"
                   value="<?= htmlspecialchars($_POST['email_reg'] ?? '') ?>" required />
          </div>
        </div>

        <div class="field">
          <label>Mot de passe</label>
          <div class="input-wrap">
            <span class="ico">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input type="password" id="pwd_reg" name="pwd_reg" placeholder="Min. 6 caractères" required />
            <button class="eye-btn" type="button" onclick="toggleEye('pwd_reg', this)">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <div class="field">
          <label>Confirmer le mot de passe</label>
          <div class="input-wrap">
            <span class="ico">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input type="password" id="pwd_confirm" name="pwd_confirm" placeholder="Répétez le mot de passe" required />
          </div>
        </div>

        <button type="submit" class="btn-main" style="margin-top:6px">Créer mon compte</button>

      </form>

      <a href="../index.php" class="back-link">← Retour au site</a>
    </div>
  </div>

  <script>
    function showTab(tab) {
      const loginForm    = document.getElementById('loginForm');
      const registerForm = document.getElementById('registerForm');
      const loginTab     = document.getElementById('loginTab');
      const registerTab  = document.getElementById('registerTab');
      const title        = document.getElementById('formTitle');
      const subtitle     = document.getElementById('formSubtitle');

      if (tab === 'login') {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
        title.textContent    = 'Connexion';
        subtitle.textContent = 'Bon retour parmi nous !';
      } else {
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
        registerTab.classList.add('active');
        loginTab.classList.remove('active');
        title.textContent    = 'Inscription';
        subtitle.textContent = 'Créez votre compte !';
      }
    }

    function toggleEye(inputId, btn) {
      const input = document.getElementById(inputId);
      input.type = input.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>