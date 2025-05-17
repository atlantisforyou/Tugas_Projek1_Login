<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$db = new mysqli("localhost", "root", "", "negara");
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$countryData = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['country'])) {
    $country = $db->real_escape_string($_POST['country']);

    $sql = "SELECT name, capital, language, description FROM countries WHERE name LIKE '%$country%' LIMIT 1";
    $result = $db->query($sql);
    if ($result && $result->num_rows > 0) {
        $countryData = $result->fetch_assoc();
    } else {
        $countryData = ['name' => '', 'capital' => '', 'language' => '', 'description' => 'Country not found.'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Menu</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f4f4;
        margin: 0;
        padding: 20px;
        background-image: url('negara.png');
    }
    .menu-container {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        max-width: 600px;
        margin: auto;
    }
    h1 {
        text-align: center;
        color: #4a90e2;
    }
    form {
        margin-bottom: 1.5rem;
    }
    input[type="text"] {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #4a90e2;
        border-radius: 6px;
        font-size: 1rem;
        margin-bottom: 1rem;
    }
    button {
        padding: 0.75rem;
        background: #4a90e2;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
    }
    button:hover {
        background: #50e3c2;
    }
    .country-info {
        margin-top: 1rem;
        padding: 1rem;
        background: #e9f7ef;
        border-left: 5px solid #4caf50;
    }
    .country-info h2 {
        margin-top: 0;
        color: #388e3c;
    }
    .country-info dl {
        display: grid;
        grid-template-columns: max-content 1fr;
        gap: 0.25rem 1rem;
    }
    .country-info dt {
        font-weight: bold;
        color: #2e7d32;
    }
    .country-info dd {
        margin: 0;
        color: #1b5e20;
    }
    a.logout-link {
        display: inline-block;
        margin-top: 1.5rem;
        color: #d32f2f;
        font-weight: bold;
        text-decoration: none;
    }
    a.logout-link:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
    <div class="menu-container">
        <h1>Welcome, <?=htmlspecialchars($_SESSION['username'])?></h1>
        <form method="post" action="index.php" aria-label="Search Country Form">
        <input type="text" name="country" placeholder="Search for a country" required />
        <button type="submit">Search</button>
        </form>

        <?php if ($countryData): ?>
            <div class="country-info" role="region" aria-live="polite">
                <?php if ($countryData['description'] === 'Country not found.'): ?>
                <p><?=htmlspecialchars($countryData['description'])?></p>
                <?php else: ?>
                <h2><?=htmlspecialchars($countryData['name'])?></h2>
                <dl>
                    <dt>Capital:</dt>
                    <dd><?=htmlspecialchars($countryData['capital'])?></dd>

                    <dt>Language:</dt>
                    <dd><?=htmlspecialchars($countryData['language'])?></dd>

                    <dt>Description:</dt>
                    <dd><?=htmlspecialchars($countryData['description'])?></dd>
                </dl>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <a class="logout-link" href="logout.php">Logout</a>
    </div>
</body>
</html>