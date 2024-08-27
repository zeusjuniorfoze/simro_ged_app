<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Connexion & Inscription</title>
  <link rel="stylesheet" href="boostrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/inscrip_con.css" />
  <!-- Boxicons -->
  <link rel="stylesheet" href="boxicons/css/boxicons.min.css" />
  <style>
    .validity-message {
      font-size: 0.9em;
      margin-top: 5px;
    }

    .validity-message.red {
      color: red;
    }

    .validity-message.orange {
      color: orange;
    }

    .validity-message.green {
      color: green;
    }
  </style>
</head>

<body>
  <!-- Header -->
  <header class="header">
    <nav class="nav">
      <a href="#" class="nav_logo">Gestion Electronique D'achivage | <span style="color: #555;">By JUNIOR FOZET</span></a>
      <button class="button" id="form-open">Login</button>
    </nav>
  </header>

  <!-- Home -->
  <section class="home">
    <div class="form_container">
      <i class="bx bx-x form_close"></i>
      <!-- Login Form -->
      <div class="form login_form">
        <form action="php/connexion.php" method="POST">
          <h2>Login</h2>
          <!-- Alert pour les erreurs -->
          <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $_SESSION['error'];
              unset($_SESSION['error']); // Suppression de l'erreur après affichage 
              ?>
            </div>
          <?php } ?>
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
            <input type="email" name="email" id="login-email" placeholder="Enter your email" required />
            <i class="bx bx-envelope email"></i>
            <div id="email-message" class="validity-message"></div>
          </div>

          <div class="input_box">
            <input type="password" name="mot_de_passe" id="login-password" placeholder="Enter your password" required />
            <i class="bx bx-lock password"></i>
            <i class="bx bx-low-vision pw_hide"></i>
            <div id="password-message" class="validity-message"></div>
          </div>

          <div class="option_field">
            <span class="checkbox">
              <input type="checkbox" id="check" />
              <label for="check">Remember me</label>
            </span>
            <a href="password_oublier.php" class="forgot_pw">Forgot password?</a>
          </div>

          <button type="submit" class="button">Login Now</button>

          <div class="login_signup">Don't have an account? <a href="#" id="signup">Signup</a></div>
        </form>
      </div>

      <!-- Signup Form -->
      <div class="form signup_form">
        <form action="php/inscription.php" method="POST">
          <h2>Signup</h2>

          <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success" role="alert">
              <?php echo $_SESSION['success'];
              unset($_SESSION['success']); // Suppression du succès après affichage 
              ?>
            </div>
          <?php } ?>

          <div class="input_box">
            <input type="text" name="prenom" placeholder="Enter your first name" required />
            <i class="bx bx-user password"></i>
          </div>

          <div class="input_box">
            <input type="email" name="email" id="signup-email" placeholder="Enter your email" required />
            <i class="bx bx-envelope email"></i>
            <div id="signup-email-message" class="validity-message"></div>
          </div>

          <div class="input_box">
            <input type="password" name="mot_de_passe" id="signup-password" placeholder="Create password" required />
            <i class="bx bx-lock password"></i>
            <i class="bx bx-low-vision pw_hide"></i>
            <div id="signup-password-message" class="validity-message"></div>
          </div>

          <div class="input_box">
            <input type="password" name="confirm_mot_de_passe" id="confirm-password" placeholder="Confirm password" required />
            <i class="bx bx-lock password"></i>
            <i class="bx bx-low-vision pw_hide"></i>
            <div id="confirm-password-message" class="validity-message"></div>
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
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Fonction pour valider l'email
    const validateEmail = (email) => {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailPattern.test(email);
    };

    // Fonction pour évaluer la force du mot de passe
    const evaluatePassword = (password) => {
      const lengthCriteria = password.length >= 8;
      const specialCharCriteria = /[!@#$%^&*(),.?":{}|<>]/.test(password);
      const numberCriteria = /[0-9]/.test(password);

      if (lengthCriteria && specialCharCriteria && numberCriteria) {
        return 'green'; // Strong
      } else if (lengthCriteria) {
        return 'orange'; // Medium
      } else {
        return 'red'; // Weak
      }
    };

    // Fonction pour afficher les messages de validation
    const showValidationMessage = (element, message, className) => {
      const messageElement = document.getElementById(element);
      if (message) {
        messageElement.textContent = message;
        messageElement.className = `validity-message ${className}`;
      } else {
        messageElement.textContent = '';
      }
    };

    // Validation de connexion
    const loginForm = document.querySelector('.login_form form');
    const loginEmail = document.getElementById('login-email');
    const loginPassword = document.getElementById('login-password');

    loginEmail.addEventListener('input', () => {
      if (loginEmail.value) {
        if (validateEmail(loginEmail.value)) {
          showValidationMessage('email-message', 'Email valide.', 'green');
        } else {
          showValidationMessage('email-message', 'Email invalide.', 'red');
        }
      } else {
        showValidationMessage('email-message', '', '');
      }
    });

    loginPassword.addEventListener('input', () => {
      if (loginPassword.value) {
        const strength = evaluatePassword(loginPassword.value);
        if (strength === 'green') {
          showValidationMessage('password-message', 'Mot de passe fort.', 'green');
        } else if (strength === 'orange') {
          showValidationMessage('password-message', 'Mot de passe moyen.', 'orange');
        } else {
          showValidationMessage('password-message', 'Mot de passe trop court.', 'red');
        }
      } else {
        showValidationMessage('password-message', '', '');
      }
    });

    loginForm.addEventListener('submit', (e) => {
      const emailValid = validateEmail(loginEmail.value);
      const passwordStrength = evaluatePassword(loginPassword.value);

      if (!emailValid || passwordStrength === 'red') {
        e.preventDefault(); // Empêche l'envoi du formulaire
        alert('Email invalide ou mot de passe trop court !');
      }
    });

    // Validation d'inscription
    const signupForm = document.querySelector('.signup_form form');
    const signupEmail = document.getElementById('signup-email');
    const signupPassword = document.getElementById('signup-password');
    const confirmPassword = document.getElementById('confirm-password');

    signupEmail.addEventListener('input', () => {
      if (signupEmail.value) {
        if (validateEmail(signupEmail.value)) {
          showValidationMessage('signup-email-message', 'Email valide.', 'green');
        } else {
          showValidationMessage('signup-email-message', 'Email invalide.', 'red');
        }
      } else {
        showValidationMessage('signup-email-message', '', '');
      }
    });

    signupPassword.addEventListener('input', () => {
      if (signupPassword.value) {
        const strength = evaluatePassword(signupPassword.value);
        if (strength === 'green') {
          showValidationMessage('signup-password-message', 'Mot de passe fort.', 'green');
        } else if (strength === 'orange') {
          showValidationMessage('signup-password-message', 'Mot de passe moyen.', 'orange');
        } else {
          showValidationMessage('signup-password-message', 'Mot de passe trop court.', 'red');
        }
      } else {
        showValidationMessage('signup-password-message', '', '');
      }
    });

    confirmPassword.addEventListener('input', () => {
      if (confirmPassword.value) {
        if (confirmPassword.value === signupPassword.value) {
          showValidationMessage('confirm-password-message', 'Les mots de passe correspondent.', 'green');
        } else {
          showValidationMessage('confirm-password-message', 'Les mots de passe ne correspondent pas.', 'red');
        }
      } else {
        showValidationMessage('confirm-password-message', '', '');
      }
    });

    signupForm.addEventListener('submit', (e) => {
      const emailValid = validateEmail(signupEmail.value);
      const passwordStrength = evaluatePassword(signupPassword.value);
      const passwordsMatch = signupPassword.value === confirmPassword.value;

      if (!emailValid || passwordStrength === 'red' || !passwordsMatch) {
        e.preventDefault(); // Empêche l'envoi du formulaire
        alert('Veuillez vérifier votre email, la force de votre mot de passe, ou que les mots de passe correspondent.');
      }
    });
  });
</script>