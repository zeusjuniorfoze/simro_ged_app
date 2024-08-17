<?php
session_start();

try {
    // Options de connexion supplémentaires
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Mode d'erreur pour lancer des exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération par défaut
        PDO::ATTR_EMULATE_PREPARES => false, // Désactiver l'émulation des requêtes préparées pour plus de sécurité
    ];

    // Connexion à la base de données
    $con = new PDO('mysql:host=localhost;dbname=ged;charset=utf8', 'root', '', $options);
} catch (PDOException $e) {
    // Message d'erreur personnalisé
    echo '<div style="text-align: center; color: orange; font-size: 40px;">Erreur de connexion à la base de données. Veuillez démarrer MySQL.</div>';
    // Log de l'erreur pour un debug plus facile
    error_log($e->getMessage());
    die(); // Arrêter l'exécution du script
}
