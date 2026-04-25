<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion - ShopEsa</title>
<link rel="stylesheet" href="../css/connect_client.css">


</head>
<body>

<div class="container">

    <!-- LEFT -->
    <div class="left">
        <div class="left-content">
            <div class="icon">🛍️</div>
            <h1>Bienvenue sur ShopEsa</h1>
            <p>Découvrez les dernières tendances mode et créez votre style unique</p>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="right">
        <div class="form-box">

            <!-- LOGO -->
            <div class="logo">ShopEsa</div>

            <h2>Connexion</h2>
            <p class="subtitle">Bon retour parmi nous !</p>

            <!-- TABS -->
            <div class="tabs">
                <button id="loginTab" class="active">Connexion</button>
                <button id="registerTab">Inscription</button>
            </div>

            <!-- LOGIN -->
            <form id="loginForm">
                <label>Email</label>
                <input type="email" placeholder="email@exemple.com">

                <label>Mot de passe</label>
                <input type="password" placeholder="********">

                <div class="options">
                    <label><input type="checkbox"> Se souvenir de moi</label>
                    <a href="#">Mot de passe oublié ?</a>
                </div>

                <button class="btn">Se connecter</button>
            </form>

            <!-- REGISTER -->
            <form id="registerForm" class="hidden">
                <label>Nom complet</label>
                <input type="text" placeholder="Votre nom">

                <label>Email</label>
                <input type="email" placeholder="email@exemple.com">

                <label>Mot de passe</label>
                <input type="password" placeholder="********">

                <label>Confirmer mot de passe</label>
                <input type="password" placeholder="********">

                <button class="btn">Créer un compte</button>
            </form>

            <div class="divider">Ou continuer avec</div>

            <div class="social">
                <button>Google</button>
                <button>Facebook</button>
            </div>

            <p class="signup">
                Pas encore de compte ? <a href="#">Créer un compte</a>
            </p>

        </div>
    </div>

</div>

<script>

const loginTab = document.getElementById("loginTab");
const registerTab = document.getElementById("registerTab");

const loginForm = document.getElementById("loginForm");
const registerForm = document.getElementById("registerForm");

const title = document.querySelector(".form-box h2");
const subtitle = document.querySelector(".subtitle");

const signupLink = document.querySelector(".signup a");

function showLogin() {
    loginForm.classList.remove("hidden");
    registerForm.classList.add("hidden");

    loginTab.classList.add("active");
    registerTab.classList.remove("active");

    title.textContent = "Connexion";
    subtitle.textContent = "Bon retour parmi nous !";
}

function showRegister() {
    registerForm.classList.remove("hidden");
    loginForm.classList.add("hidden");

    registerTab.classList.add("active");
    loginTab.classList.remove("active");

    title.textContent = "Inscription";
    subtitle.textContent = "Crée ton compte maintenant !";
}

loginTab.addEventListener("click", showLogin);
registerTab.addEventListener("click", showRegister);

signupLink.addEventListener("click", (e) => {
    e.preventDefault();
    showRegister();
});

</script>

</body>
</html>