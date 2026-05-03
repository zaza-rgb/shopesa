<?php
session_start();
       require '../liaison.php';
       if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header('Location: ../Authentification/connect_client.php');
    exit;
}
        $ref_uti=$_SESSION['ref_uti'];
       $total=0;
      $qte=1;
      if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['total'])){
        $date    = date('Y-m-d H:i:s');
        $total = (int)($_GET['total']);
        $stmt = $com->prepare("SELECT stock, seuil FROM produit JOIN panier ON produit.idprod=panier.idprod WHERE panier.ref_uti = ? AND stock<=seuil LIMIT 1");
        $stmt->execute([$ref_uti]);
        if ($stmt->fetch()) {
            $statut='en_attente';
            try{
        $stmtProd = $com->query("SELECT * FROM produit");
        $insertNotif = $com->prepare("INSERT INTO notification (ref_uti, message, lu) VALUES (?, ?, ?)");
        while ($row = $stmtProd->fetch(PDO::FETCH_ASSOC)) {
            if ($row['seuil'] >= $row['stock']) {
                $description = 'seuil atteint pour le produit '.$row['nomprod'].', seuil :'.$row['seuil'].', quantite :'.$row['stock'];
                $insertNotif->execute([5, $description, 'non_lu']);
            }
        }

            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
        } else {
          $statut='validee';
          $stmt = $com->prepare("SELECT idprod, quantite FROM panier WHERE ref_uti = ?");
          $stmt->execute([$ref_uti]);

          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $idprod   = $row['idprod'];
              $quantite = $row['quantite'];
              $update = $com->prepare("UPDATE produit SET stock = stock - ? WHERE idprod = ?");
              $update->execute([$quantite, $idprod]);
          }
        }
        $stmt = $com->prepare("INSERT INTO commande (ref_uti, date_commande, total ,statut) VALUES (?, ?, ?, ?)");
        $stmt->execute([$ref_uti, $date,$total,$statut]);
        $id_commande = $com->lastInsertId();

        $stmt = $com->prepare("SELECT idprod, quantite FROM panier WHERE ref_uti = ?");
        $stmt->execute([$ref_uti]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $insert = $com->prepare("INSERT INTO commande_produit (id_commande, idprod, quantite, prix_unitaire) 
                                    VALUES (?, ?, ?, (SELECT prix FROM produit WHERE idprod = ?))");
            $insert->execute([$id_commande, $row['idprod'], $row['quantite'], $row['idprod']]);

        }


        $com->prepare("DELETE FROM panier WHERE ref_uti = ?")->execute([$ref_uti]);
        $mesg='Achat en traitement, veuillez verifier "vos commandes"';
        header("Location: affiche_panier.php");
            exit;
      }else
       if (isset($_POST['delete'])) {
          $idpanier=$_POST['idpanier'];
            $stmt = $com->prepare("DELETE FROM panier WHERE idpanier=?");
            $stmt->execute([$idpanier]);
            header("Location: affiche_panier.php");
            exit;
            }else if (isset($_POST['refresh'])) {
                  $idpanier=$_POST['idpanier'];
                  $qte=$_POST['qte'];
                    $stmt = $com->prepare("UPDATE panier SET quantite = ? WHERE idpanier = ?");
                    $stmt->execute([$qte, $idpanier]);
                    header("Location: affiche_panier.php");
                    exit;
                    }else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idprod'])) {
                        $id_prod=$_POST['idprod'];
                        $quantite=$_POST['qte'];
                          $sql = "INSERT INTO panier (ref_uti, idprod, quantite) VALUES(?,?,?)";
                          $stmt = $com->prepare($sql);
                          $stmt->execute([$ref_uti,$id_prod,$quantite]);
                          header("Location: ../index.php");
                          exit;
                          }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShopStyle – Panier</title>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
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
      --green:        #4caf7d;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Sora', sans-serif;
      min-height: 100vh;
    }

    /* ── TOP BAR ── */
    .topbar {
      background: var(--card-bg);
      border-bottom: 1px solid var(--border);
      padding: 0 48px;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: relative;
    }

    .back-link {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.88rem;
      color: var(--text);
      text-decoration: none;
      transition: color .2s;
    }

    .back-link:hover { color: var(--accent-pink); }

    .topbar-logo {
      font-size: 1.4rem;
      font-weight: 700;
      background: var(--grad);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      text-decoration: none;
    }

    /* ── LAYOUT ── */
    .page {
      max-width: 1280px;
      margin: 0 auto;
      padding: 36px 48px 64px;
    }

    .cart-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 6px; }
    .cart-count { font-size: 0.85rem; color: var(--muted); margin-bottom: 28px; }

    .layout {
      display: grid;
      grid-template-columns: 1fr 360px;
      gap: 28px;
      align-items: start;
    }

    /* ── CART ITEMS ── */
    .items { display: flex; flex-direction: column; gap: 16px; }

    .item-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 20px;
      display: flex;
      align-items: flex-start;
      gap: 18px;
    }

    .item-img {
      width: 90px;
      height: 90px;
      border-radius: 10px;
      background: var(--input-bg);
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-size: 0.7rem;
      color: var(--muted);
      text-decoration: none;
    }

    .item-info { flex: 1; }
    .item-info h3 { font-size: 1rem; font-weight: 600; margin-bottom: 3px; }
    .item-cat { font-size: 0.78rem; color: var(--muted); margin-bottom: 8px; }
    .item-meta { font-size: 0.82rem; color: var(--muted); margin-bottom: 14px; }
    .item-meta strong { color: var(--text); font-weight: 600; }

    .item-bottom {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    /* ── QUANTITE ── */
    .qty {
      display: flex;
      align-items: center;
      background: var(--input-bg);
      border: 1px solid var(--border);
      border-radius: 8px;
      overflow: hidden;
    }

    /* Les boutons +/- restent des <button> car ils déclenchent du JS, pas une navigation */
    .qty-btn {
      width: 34px;
      height: 34px;
      background: none;
      border: none;
      color: var(--text);
      font-size: 1.1rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background .2s;
    }

    .qty-btn:hover { background: var(--border); }

    .qty-val {
      width: 34px;
      text-align: center;
      font-size: 0.9rem;
      font-weight: 600;
      border-left: 1px solid var(--border);
      border-right: 1px solid var(--border);
      line-height: 34px;
    }

    .item-price { font-size: 1rem; font-weight: 700; color: var(--accent-pink); }
    .item-unit  { font-size: 0.75rem; color: var(--muted); text-align: right; margin-top: 2px; }

    /* ── SUPPRIMER → lien ── */
    /* Style commun aux deux boutons */
.updatelink, .delete-link {
  background: none;          /* pas de fond par défaut */
  border: none;              /* pas de bordure */
  cursor: pointer;           /* curseur main */
  padding: 6px;              /* espace autour de l’icône */
  border-radius: 4px;        /* coins arrondis */
  transition: background 0.2s, transform 0.2s;
}

/* Icônes SVG héritent de la couleur */
.updatelink svg, .delete-link svg {
  stroke: #333;              /* couleur par défaut */
  width: 20px;
  height: 20px;
}

/* Bouton Mettre à jour */
.updatelink:hover {
  background: #e0f7e9;       /* vert pâle au survol */
  transform: scale(1.1);     /* petit zoom */
}
.updatelink svg {
  stroke: #28a745;           /* vert */
}

/* Bouton Supprimer */
.delete-link:hover {
  background: #fde0e0;       /* rouge pâle au survol */
  transform: scale(1.1);
}
.delete-link svg {
  stroke: #dc3545;           /* rouge */
}

    .delete-link {
      color: var(--muted);
      display: flex;
      align-items: center;
      padding: 4px;
      transition: color .2s;
      flex-shrink: 0;
      text-decoration: none;
    }

    .delete-link:hover { color: #ef5350; }

    /* ── SUMMARY ── */
    .summary {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 24px;
      position: sticky;
      top: 20px;
    }

    .summary h2 { font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; }

    .summary-row {
      display: flex;
      justify-content: space-between;
      font-size: 0.88rem;
      color: var(--muted);
      margin-bottom: 12px;
    }

    .summary-row span:last-child { color: var(--text); }
    .summary-row .green { color: var(--green); font-weight: 600; }

    .divider { border: none; border-top: 1px solid var(--border); margin: 16px 0; }

    .summary-total {
      display: flex;
      justify-content: space-between;
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .summary-total span:last-child { color: var(--accent-pink); }

    .promo-label { font-size: 0.82rem; font-weight: 500; margin-bottom: 10px; }

    .promo-row { display: flex; gap: 8px; margin-bottom: 20px; }

    .promo-row input {
      flex: 1;
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: 8px;
      padding: 10px 14px;
      font-family: inherit;
      font-size: 0.85rem;
      color: var(--muted);
      outline: none;
      transition: border-color .2s;
    }

    .promo-row input:focus { border-color: var(--accent-purple); color: var(--text); }

    /* Bouton promo → lien */
    .promo-link {
      width: 42px;
      height: 42px;
      background: var(--input-bg);
      border: 1.5px solid var(--border);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      color: var(--muted);
      transition: border-color .2s, color .2s;
      flex-shrink: 0;
    }

    .promo-link:hover { border-color: var(--accent-purple); color: var(--accent-pink); }

    /* Bouton paiement → lien */
    .btn-pay {
      width: 100%;
      padding: 15px;
      background: var(--grad);
      border-radius: 10px;
      color: #fff;
      font-family: inherit;
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: opacity .2s;
      margin-bottom: 16px;
      text-decoration: none;
    }

    .btn-pay:hover { opacity: .88; }

    .info-row {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.78rem;
      color: var(--muted);
      margin-bottom: 8px;
    }

    /* ── MOYENS DE PAIEMENT → liens ── */
    .payments { margin-top: 48px; }
    .payments h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; }

    .payment-list { display: flex; gap: 12px; flex-wrap: wrap; }

    .payment-link {
      padding: 12px 24px;
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 10px;
      font-size: 0.88rem;
      font-weight: 500;
      color: var(--text);
      text-decoration: none;
      transition: border-color .2s, color .2s;
    }

    .payment-link:hover {
      border-color: var(--accent-purple);
      color: var(--accent-pink);
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
      .topbar { padding: 0 20px; }
      .page   { padding: 24px 20px 48px; }
      .layout { grid-template-columns: 1fr; }
      .summary { position: static; }
    }
  </style>
</head>
<body>
  <!-- TOP BAR -->
  <header class="topbar">
    <a href="http://localhost/shopesa/index.php" class="back-link">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M15 18l-6-6 6-6"/>
      </svg>
      Continuer mes achats
    </a>
    <a href="index.php" class="topbar-logo">ShopEsa</a>
  </header>

  <!-- PAGE -->
  <main class="page">
    <h1 class="cart-title">Mon Panier</h1>
    <p class="cart-count"><?php
        require '../liaison.php';
        try {
    $stmt = $com->prepare("SELECT COUNT(idpanier) AS nb FROM panier WHERE ref_uti = ?");
    $stmt->execute([$ref_uti]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
          echo $row['nb'];

      } catch (PDOException $e) {
          echo "Erreur : " . $e->getMessage();
      }
      ?> articles dans votre panier</p>
    <p><?php if (isset($mesg)){
          echo $mesg;
    } 
    ?></p>
    <div class="layout">

      <!-- LEFT: Items -->
      <div class="items">
      <?php
      try{
         $stmt = $com->prepare("SELECT * FROM panier JOIN produit ON panier.idprod = produit.idprod JOIN categorie ON categorie.id_cat = produit.id_cat WHERE ref_uti = ? ");
          $stmt->execute([$ref_uti]);  
         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              ?>
              <form method="POST" action="affiche_panier.php">
                 <div class="item-card">
          <a href="detai.php?id=<?php echo htmlspecialchars($row['idprod']); ?>" class="item-img">
            <img src="../image/<?php echo htmlspecialchars($row['image']); ?>" alt="" width="100%">
          </a>
          <div class="item-info">
            <h3><?php echo htmlspecialchars($row['nomprod']); ?></h3>
            <p class="item-cat"><?php echo htmlspecialchars($row['nom']); ?></p>
          
            <div class="item-bottom">
              <div class="qty">
                <button type ="button" class="qty-btn" onclick="changeQty(this,-1)">−</button>
                <span class="qty-val"><?php echo htmlspecialchars($row['quantite']); ?></span>
                <input type="hidden" class="qte-input" name="qte" value="<?php echo htmlspecialchars($row['quantite']); ?>">
                <button type="button" class="qty-btn" onclick="changeQty(this,1)">+</button>
              </div>
              <div>
                <p class="item-price"><?php echo htmlspecialchars($row['prix']); ?>FCFA</p>
                <?php 
                  $tot = $row['prix'] * $row['quantite'];
                  $total += $tot;
                ?>
              </div>
            </div>
          </div>
          
            <input type="hidden" name="idpanier" value="<?php echo htmlspecialchars($row['idpanier']); ?>">
            <input type="hidden" name="idprod" value="<?php echo htmlspecialchars($row['idprod']); ?>">

          <button class="updatelink" name="refresh" title="Mettre à jour" >
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="23 4 23 10 17 10"/>
            <polyline points="1 20 1 14 7 14"/>
            <path d="M3.51 9a9 9 0 0 1 14.13-3.36L23 10"/>
            <path d="M20.49 15a9 9 0 0 1-14.13 3.36L1 14"/>
          </svg>
          </button>
          <button class="delete-link" name="delete" title="Supprimer">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
              <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
            </svg>
          </button>
         
        </div>
         </form>
        <?php
                }
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
            ?> 

        <!-- Moyens de paiement -->
        <div class="payments">
          <h3>Moyens de paiement acceptés</h3>
          <div class="payment-list">
            <a href="paiement.php?method=visa"       class="payment-link">Visa</a>
            <a href="paiement.php?method=mastercard" class="payment-link">Mastercard</a>
            <a href="paiement.php?method=paypal"     class="payment-link">PayPal</a>
            <a href="paiement.php?method=applepay"   class="payment-link">Apple Pay</a>
            <a href="paiement.php?method=googlepay"  class="payment-link">Google Pay</a>
          </div>
        </div>

      </div>

      <!-- RIGHT: Summary -->
      <aside class="summary">
        <h2>Résumé de la commande</h2>

        <div class="summary-row">
          <span>Sous-total</span>
          <span><?php echo $total;?></span>
        </div>
        <div class="summary-row">
          <span>Livraison</span>
          <span class="green">Gratuite</span>
        </div>

        <hr class="divider" />

        <div class="summary-total">
          <span>Total</span>
          <span><?php echo $total;?></span>
        </div>

        <p class="promo-label">Code promo</p>
        <div class="promo-row">
          <input type="text" placeholder="PROMO10" />
          <a href="appliquer-promo.php" class="promo-link" title="Appliquer">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
              <line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
          </a>
        </div>

        <a href="affiche_panier.php?total=<?php echo $total;?>" class="btn-pay">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>
          </svg>
          Procéder au paiement
        </a>

        <div class="info-row">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 5v3h-7V8z"/>
            <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
          </svg>
          Livraison sous 2-5 jours ouvrés
        </div>
        <div class="info-row">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/>
          </svg>
          Paiement 100% sécurisé
        </div>
      </aside>

    </div>
  </main>

  <script>
   function changeQty(btn, delta) {
  const wrap = btn.closest('.qty');
  const val  = wrap.querySelector('.qty-val');
  const input = wrap.querySelector('.qte-input');
  let n = parseInt(val.textContent) + delta;
  if (n < 1) n = 1;
  val.textContent = n;
  input.value = n;
}
  </script>
</body>
</html>