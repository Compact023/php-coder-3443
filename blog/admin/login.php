<?php 
    require_once '../config.php';
    
    // Überprüfen ob der Benutzer bereits angemeldet ist
    if (isSignedIn()) {
        // Weiterleitung auf index.php
        header('Location: ' . BASE_URL . 'admin/index.php');
        die();
    }

    $validationErrors = [];
    $email = '';
    $passwort = '';
    
    // ist der Request ein POST Request?
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // trim($variable) entfernt Leerzeichen vorne und hinten vom übergebenen Wert und gibt den bereinigten Wert dann zurück
        //$variable = (Bedingung ? Then-Wert : Else-Wert);
        $email = (isset($_POST['email']) ? trim($_POST['email']) : '');
        $passwort = (isset($_POST['passwort']) ? trim($_POST['passwort']) : '');
        
        // Validierung der Eingaben
        // Wenn die Variable $email leer (kein Inhalt oder ein leerer Text) ist
        if (empty($email)) {
            $validationErrors['email'] = 'Bitte geben Sie Ihre Email Adresse ein.';
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Wenn die Variable $email keine gültige Email Adresse enthält
            $validationErrors['email'] = 'Bitte geben Sie eine gültige Email Adresse ein.';
        }
        // Wenn die Variable $passwort leer (kein Inhalt oder ein leerer Text) ist
        if (empty($passwort)) {
            $validationErrors['passwort'] = 'Bitte geben Sie Ihr Passwort ein.';
        }
        
        // Wenn alle Validierungen erfolgreich waren -> dann ist das $validationErrors Array leer -> hat 0 Elemente
        if (count($validationErrors) == 0) {
            $user = null;

            // Verbindung zur DB herstellen
            $conn = connectToDB();
            
            $query = "SELECT * FROM user WHERE email LIKE ?";
            
            // Prepared Statement erstellen
            $stmt = mysqli_prepare($conn, $query);
            
            // ? durch Werte ersetzen
            mysqli_stmt_bind_param($stmt, "s", $email);
            
            // Prepared Statement ausführen
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            
            if ($result) {
                $user = mysqli_fetch_assoc($result);
            }
            
            // Überprüfen ob Benutzer exitiert und ob das Passwort übereinstimmt
            if ($user && password_verify($passwort, $user['passwort'])) {
                // Datenbankverbindung schließen
                closeDB($conn);
                
                // Email im Session-Element User ablegen
                $_SESSION['user'] = $user;

                // Weiterleitung auf index.php
                header('Location: ' . BASE_URL . 'admin/index.php');
                die();
            } else {
                $validationErrors['allgemein'] = 'Die Email-Adresse oder das Passwort sind nicht korrekt.';
            }
            
            // Datenbankverbindung schließen
            closeDB($conn);
        }
    }    
    
    $pageTitle = 'Login'; 
?>

<?php include_once BASE_PATH . '/inc/template/head.php'; ?>

<div class="container">
    <h1>Login</h1>
    
    <form action="<?= BASE_URL ?>admin/login.php" method="POST" enctype="multipart/form-data">
        <?php if (isset($validationErrors['allgemein'])): ?>
            <div class="error-message"><?= $validationErrors['allgemein'] ?></div>
        <?php endif; ?>

        <div class="form-field">
            <label for="email">Email</label>
            <input type="email" id="email" class="form-control" name="email" value="<?= @$email ?>" required>
            <?php if (isset($validationErrors['email'])): ?>
                <div class="error-message"><?= $validationErrors['email'] ?></div>
            <?php endif; ?>
        </div>

        <div class="form-field">
            <label for="passwort">Passwort</label>
            <input type="password" id="passwort" class="form-control" name="passwort" value="<?= @$passwort ?>" required>
            <?php if (isset($validationErrors['passwort'])): ?>
                <div class="error-message"><?= $validationErrors['passwort'] ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-outline-primary">Anmelden</button>
    </form>
    
</div>

<?php include_once BASE_PATH . '/inc/template/foot.php'; ?>