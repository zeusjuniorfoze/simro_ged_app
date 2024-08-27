<!DOCTYPE html>
<!-- Source de ce formulaire de connexion -->
<!-- Coding by CodingLab || www.codinglabweb.com -->
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Connexion & Inscription</title>
  <link rel="stylesheet" href="css/inscrip_con.css" />
  <!-- Boxicons -->
  <link rel="stylesheet" href="boxicons/css/boxicons.min.css" />
</head>

<body>
  <!-- Header -->
  <header class="header">
    <nav class="nav">
      <a href="#" class="nav_logo">Gestion Electronique D'achivage | <span style="color: #555;">By JUNIOR FOZET </span></a>
      <button class="button" id="form-open">Login</button>
    </nav>
  </header>

  <!-- Home -->
  <section class="home">
    <div class="form_container">
      <i class="bx bx-x form_close"></i>
      <!-- Login From -->
      <div class="form login_form">
        <form action="php/connexion.php" method="POST">
          <h2>Login</h2>
          <!-- Liste déroulante -->
          <div class="input_box">
            <select name="role" class="dropdown">
              <option value="" disabled selected>Choix du type de compte</option>
              <option value="admin">Administrateur</option>
              <option value="gest">Gestionnaire</option>
              <option value="user">Utilisateur</option>
            </select>
            <i class="bx bx-list-ul dropdown_icon"></i>
          </div>
          <div class="input_box">
            <input type="email" name="email" placeholder="Enter your email" required />
            <i class="bx bx-envelope email"></i>
          </div>

          <div class="input_box">
            <input type="password" name="mot_de_passe" placeholder="Enter your password" required />
            <i class="bx bx-lock password"></i>
            <i class="bx bx-low-vision pw_hide"></i>
          </div>

          <div class="option_field">
            <span class="checkbox">
              <input type="checkbox" id="check" />
              <label for="check">Remember me</label>
            </span>
            <a href="password_oublier.php" class="forgot_pw">Forgot password?</a>
          </div>
          <!-- Alert pour les erreurs -->
          <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $_SESSION['error'];
              unset($_SESSION['error']); // Suppression de l'erreur après affichage 
              ?>
            </div>
          <?php } ?>
          <button type="submit" class="button">Login Now</button>

          <div class="login_signup">Don't have an account? <a href="#" id="signup">Signup</a></div>
        </form>
      </div>


      <!-- Signup Form -->
      <div class="form signup_form">
        <form action="php/inscription.php" method="POST">
          <h2>Signup</h2>

          <div class="input_box">
            <input type="text" name="prenom" placeholder="Enter your first name" required />
            <i class="bx bx-user password"></i>
          </div>

          <div class="input_box">
            <input type="email" name="email" placeholder="Enter your email" required />
            <i class="bx bx-envelope email"></i>
          </div>

          <div class="input_box">
            <input type="password" name="mot_de_passe" placeholder="Create password" required />
            <i class="bx bx-lock password"></i>
            <i class="bx bx-low-vision pw_hide"></i>
          </div>

          <div class="input_box">
            <input type="password" name="confirm_mot_de_passe" placeholder="Confirm password" required />
            <i class="bx bx-lock password"></i>
            <i class="bx bx-low-vision pw_hide"></i>
          </div>

          <button type="submit" class="button">Signup Now</button>

          <div class="login_signup">Already have an account? <a href="#" id="login">Login</a></div>
        </form>
      </div>
    </div>
  </section>

  <script src="js/inscrip_conm.js"></script>
</body>

</html>

<?php
if (isset($_GET['erreur']) && $_GET['erreur'] == 1) {
  echo "<p style='color: red;'>Identifiants incorrects. Veuillez réessayer.</p>";
}
?>
