<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "oa_zkouska";
//Create Connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
//$conn -> set_charset("utf8mb4");
//Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "connected successfully";



//$sql = "SELECT Nazev, Text, Test FROM testovaci ORDER BY Nazev";

function fetchAssoc($sql, $conn)
{
    $result = mysqli_query($conn, $sql);
    $pole = mysqli_fetch_array($result, MYSQLI_ASSOC);
    //echo $pole[ "Text" ];
    return $pole;
}

function fetch($sql, $conn)
{
    $result = mysqli_query($conn, $sql);
    $pole = mysqli_fetch_array($result, MYSQLI_NUM);
    //echo $pole[1];
    return $pole;
}

function fetchAll($sql, $conn)
{
    $result = mysqli_query($conn, $sql);
    $pole = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //echo $pole[0]["Text"];
    return $pole;
}

/*$sql = "INSERT INTO [tabulka] ( [sloupecA], [sloupecB], [sloupecC] )
VALUES ( [hodnotaA], [hodnotaB], [HodnotaC] )";*/

function akce($sql, $conn)
{
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}