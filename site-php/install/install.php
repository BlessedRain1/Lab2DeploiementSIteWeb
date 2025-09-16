<?php 
// Vérifie si le fichier de config existe déjà et est non vide
$existingConfigPath = __DIR__ . "/../config/config_data.php";
if (file_exists($existingConfigPath) && filesize($existingConfigPath) > 0) {
    die("⚠️ Installation déjà effectuée !");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des infos
    $dbHost = $_POST['db_host'] ?? "localhost";
    $dbPort = $_POST['db_port'] ?? "3306";
    $dbUser = $_POST['db_user'] ?? "root";
    $dbPass = $_POST['db_pass'] ?? "";
    $dbName = $_POST['db_name'] ?? "";
    $adminPass = password_hash($_POST['admin_pass'], PASSWORD_DEFAULT);
    $langue = $_POST['langue'] ?? "fr";
    $monnaie = $_POST['monnaie'] ?? "CAD";
    $timezone = $_POST['timezone'] ?? "America/Toronto";

    // Création du dossier config si nécessaire
    $configDir = __DIR__ . "/../config";
    if (!is_dir($configDir)) {
        if (!mkdir($configDir, 0755, true)) {
            die("❌ Impossible de créer le dossier config. Vérifiez les permissions.");
        }
    }

    // Validation minimale côté serveur
    if (!isset($_POST['db_name']) || trim($_POST['db_name']) === '') {
        die("❌ Le nom de la base de données est requis.");
    }

    // Préparation de la config (sera écrite APRÈS succès SQL)
    $config = [
        "db_host"   => $dbHost,
        "db_port"   => $dbPort,
        "db_user"   => $dbUser,
        "db_pass"   => $dbPass,
        "db_name"   => $dbName,
        "admin_pass"=> $adminPass,
        "langue"    => $langue,
        "monnaie"   => $monnaie,
        "timezone"  => $timezone
    ];

    // Connexion à MySQL
    try {
        $pdo = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Exécute les scripts SQL fournis
        $pdo->exec(file_get_contents(__DIR__ . "/../sql/exercice_categories.sql"));
        $pdo->exec(file_get_contents(__DIR__ . "/../sql/exercice_products.sql"));

        // Écriture de la configuration seulement après succès SQL
        $configFile = $configDir . "/config_data.php";
        $configContent = "<?php\nreturn " . var_export($config, true) . ";\n";
        if (file_put_contents($configFile, $configContent) === false) {
            die("❌ Impossible de créer le fichier config_data.php. Vérifiez les permissions du dossier config.");
        }
        chmod($configFile, 0644);

        echo "<p>✅ Installation réussie !</p>";
        echo "<a href='../index.php'>Aller au site</a>";
        exit;
    } catch (PDOException $e) {
        // Nettoyage si une config vide avait été laissée
        if (file_exists($existingConfigPath) && filesize($existingConfigPath) === 0) {
            @unlink($existingConfigPath);
        }

        $msg = $e->getMessage();
        if (stripos($msg, 'Unknown database') !== false) {
            die("❌ Erreur SQL : base de données inconnue. Créez la base '<strong>" . htmlspecialchars($dbName) . "</strong>' puis relancez l'installation.");
        }
        die("❌ Erreur SQL : " . htmlspecialchars($msg));
    }
}
?>

<h2>Installation du site</h2>
<form method="post">
    <label>Adresse de la base :</label><br>
    <input type="text" name="db_host" value="localhost" required><br><br>

    <label>Port :</label><br>
    <input type="text" name="db_port" value="3306" required><br><br>

    <label>Utilisateur :</label><br>
    <input type="text" name="db_user" value="root" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="db_pass" value=""><br><br>

    <label>Nom de la base de données :</label><br>
    <input type="text" name="db_name" required><br><br>

    <label>Mot de passe admin (pour la config) :</label><br>
    <input type="password" name="admin_pass" required><br><br>

    <label>Langue :</label><br>
    <input type="text" name="langue" value="fr"><br><br>

    <label>Monnaie :</label><br>
    <input type="text" name="monnaie" value="CAD"><br><br>

    <label>Fuseau horaire :</label><br>
    <input type="text" name="timezone" value="America/Toronto"><br><br>

    <button type="submit">Installer</button>
</form>
