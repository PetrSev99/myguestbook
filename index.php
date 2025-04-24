<?php
include "conn.php";

// Zpracování odeslaného formuláře
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jmeno = mysqli_real_escape_string($conn, $_POST['Jmeno']);
    $vzkaz = mysqli_real_escape_string($conn, $_POST['Vzkaz']);

    if (!empty($jmeno) && !empty($vzkaz)) {
        $sql = "INSERT INTO vzkazy (Jmeno, Vzkaz, Datum) VALUES ('$jmeno', '$vzkaz', NOW())";
        if (mysqli_query($conn, $sql)) {
            $zprava = "Vzkaz byl úspěšně uložen.";
        } else {
            $zprava = "Chyba: " . mysqli_error($conn);
        }
    } else {
        $zprava = "Vyplňte prosím všechna pole.";
    }
}

//Like funkce
if (isset($_POST['like_id'])) {
    $like_id = (int)$_POST['like_id'];
    $cookie_name = 'liked_' . $like_id;

    // Pokud uživatel ještě nedal like na tento vzkaz
    if (!isset($_COOKIE[$cookie_name])) {
        // Zvýší počet lajků v databázi
        mysqli_query($conn, "UPDATE vzkazy SET Likes = Likes + 1 WHERE id = $like_id");
        // Nastaví cookie na 1 rok
        setcookie($cookie_name, '1', time() + 365*24*60*60);
    }
    // Po kliknutí na like přesměruje zpět, aby se formulář neodeslal znovu při refreshi
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kniha návštěv</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="" method="post" class="guestbook-form">
        <h1>Kniha návštěv</h1>
        <?php
        if (isset($zprava)) {
            echo "<p class='zprava'>$zprava</p>";
        }
        ?>
        <label for="Jmeno">Jméno:</label>
        <input type="text" id="Jmeno" name="Jmeno" placeholder="Vaše jméno/přezdívka" required><br><br>
        
        <label for="Vzkaz">Vzkaz:</label><br>
        <textarea id="Vzkaz" name="Vzkaz" placeholder="Napište zprávu" rows="4" cols="50" required></textarea><br><br>
        
        <input type="submit" value="Odeslat">
    </form>

    <h2 class="vzkazy-nadpis">Seznam vzkazů:</h2>
    <div class="guestbook-messages">
    <?php
   $vysledek = mysqli_query($conn, "SELECT id, Jmeno, Vzkaz, Datum, likes FROM vzkazy WHERE Jmeno <> '' AND Vzkaz <> '' ORDER BY Datum DESC");

   if ($vysledek && mysqli_num_rows($vysledek) > 0) {
       while ($radek = mysqli_fetch_assoc($vysledek)) {
           $like_cookie = 'liked_' . $radek['id'];
           echo "<div class='guestbook-message'>";
           echo "<strong>" . htmlspecialchars($radek['Jmeno']) . "</strong> ";
           echo "<small>(" . date('d.m.Y H:i', strtotime($radek['Datum'])) . ")</small>";
           echo "<p>" . nl2br(htmlspecialchars($radek['Vzkaz'])) . "</p>";
           if (!isset($_COOKIE[$like_cookie])) {
               echo "<form method='post' style='display:inline;'>";
               echo "<input type='hidden' name='like_id' value='" . $radek['id'] . "'>";
               echo "<button type='submit' class='like-btn'>❤️ {$radek['likes']}</button>";
               echo "</form>";
           } else {
               echo "<span class='like-count'>❤️ {$radek['likes']}</span>";
           }
           echo "</div>";
}
    } else {
        echo "<p class='zadne-vzkazy'>Zatím žádné vzkazy.</p>";
    }
    ?>
    </div>
</body>
</html>