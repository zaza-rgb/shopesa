
<a href="../noctification.php" class="cart-btn" title="Mon panier">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        <span class="notif-dot"><?php
        require '../../liaison.php';
        try {
    $stmt = $com->prepare("SELECT COUNT(idnotif) AS nb FROM notification WHERE ref_uti = 5");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
          echo $row['nb'];

      } catch (PDOException $e) {
          echo "Erreur : " . $e->getMessage();
      }
      ?></span>
      </a>