<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si le fichier de config existe
$configPath = __DIR__ . "/config_data.php";
if (!file_exists($configPath)) {
    die("⚠️ Le site n'est pas encore installé. Veuillez exécuter install.php.");
}

// Charger la configuration
$config = include $configPath;

// Vérifier l'authentification
$authenticated = false;
if (isset($_POST['admin_pass'])) {
    if (password_verify($_POST['admin_pass'], $config['admin_pass'])) {
        $_SESSION['admin_authenticated'] = true;
        $authenticated = true;
    } else {
        $error = "Mot de passe incorrect.";
    }
} elseif (isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated']) {
    $authenticated = true;
}

// Traitement de la mise à jour de la configuration
if ($authenticated && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_config'])) {
    $newConfig = [
        "db_host"   => $config["db_host"],
        "db_port"   => $config["db_port"],
        "db_user"   => $config["db_user"],
        "db_pass"   => $config["db_pass"],
        "db_name"   => $config["db_name"],
        "admin_pass"=> $config["admin_pass"], // Garder le même mot de passe admin
        "langue"    => $_POST['langue'] ?? $config['langue'],
        "monnaie"   => $_POST['monnaie'] ?? $config['monnaie'],
        "timezone"  => $_POST['timezone'] ?? $config['timezone']
    ];
    
    $configContent = "<?php\nreturn " . var_export($newConfig, true) . ";\n";
    
    if (file_put_contents($configPath, $configContent) !== false) {
        $success = "Configuration mise à jour avec succès !";
        $config = $newConfig; // Mettre à jour la config en mémoire
    } else {
        $error = "Erreur lors de la mise à jour de la configuration.";
    }
}

// Déconnexion
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_authenticated']);
    $authenticated = false;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Configuration du site</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        body { padding: 20px; }
        .config-form { max-width: 600px; margin: 0 auto; }
        .logout-btn { float: right; }
    </style>
</head>
<body>

<div class="container">
    <div class="config-form">
        <h2>Configuration du site</h2>
        
        <?php if (!$authenticated): ?>
            <!-- Formulaire d'authentification -->
            <div class="card">
                <div class="card-header">
                    <h4>Authentification requise</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="form-group">
                            <label for="admin_pass">Mot de passe administrateur :</label>
                            <input type="password" class="form-control" id="admin_pass" name="admin_pass" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </form>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Interface de configuration -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Paramètres de configuration</h4>
                    <a href="?logout=1" class="btn btn-secondary btn-sm logout-btn">Déconnexion</a>
                </div>
                <div class="card-body">
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="form-group">
                            <label for="langue">Langue :</label>
                            <select class="form-control" id="langue" name="langue">
                                <option value="fr" <?= $config['langue'] === 'fr' ? 'selected' : '' ?>>Français</option>
                                <option value="en" <?= $config['langue'] === 'en' ? 'selected' : '' ?>>English</option>
                                <option value="es" <?= $config['langue'] === 'es' ? 'selected' : '' ?>>Español</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="monnaie">Monnaie :</label>
                            <select class="form-control" id="monnaie" name="monnaie">
                                <option value="CAD" <?= $config['monnaie'] === 'CAD' ? 'selected' : '' ?>>Dollar canadien (CAD)</option>
                                <option value="USD" <?= $config['monnaie'] === 'USD' ? 'selected' : '' ?>>Dollar américain (USD)</option>
                                <option value="EUR" <?= $config['monnaie'] === 'EUR' ? 'selected' : '' ?>>Euro (EUR)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="timezone">Fuseau horaire :</label>
                            <select class="form-control" id="timezone" name="timezone">
                                <option value="America/Toronto" <?= $config['timezone'] === 'America/Toronto' ? 'selected' : '' ?>>Toronto (EST/EDT)</option>
                                <option value="America/Montreal" <?= $config['timezone'] === 'America/Montreal' ? 'selected' : '' ?>>Montréal (EST/EDT)</option>
                                <option value="America/Vancouver" <?= $config['timezone'] === 'America/Vancouver' ? 'selected' : '' ?>>Vancouver (PST/PDT)</option>
                                <option value="America/New_York" <?= $config['timezone'] === 'America/New_York' ? 'selected' : '' ?>>New York (EST/EDT)</option>
                                <option value="Europe/Paris" <?= $config['timezone'] === 'Europe/Paris' ? 'selected' : '' ?>>Paris (CET/CEST)</option>
                            </select>
                        </div>
                        
                        <button type="submit" name="update_config" class="btn btn-success">Mettre à jour la configuration</button>
                        <a href="../index.php" class="btn btn-secondary">Retour au site</a>
                    </form>
                </div>
            </div>
            
            <!-- Informations sur la base de données (lecture seule) -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Informations de la base de données</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Hôte :</strong></td>
                            <td><?= htmlspecialchars($config['db_host']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Port :</strong></td>
                            <td><?= htmlspecialchars($config['db_port']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Utilisateur :</strong></td>
                            <td><?= htmlspecialchars($config['db_user']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Base de données :</strong></td>
                            <td><?= htmlspecialchars($config['db_name']) ?></td>
                        </tr>
                    </table>
                    <small class="text-muted">Pour modifier les paramètres de base de données, vous devez réinstaller le site.</small>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

</body>
</html>