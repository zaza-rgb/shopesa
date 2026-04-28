<?php
session_start();
session_destroy();
header('Location: ../Authentification/connect_admin.php');
exit;
