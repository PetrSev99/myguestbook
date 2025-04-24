<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "guestbook";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function fetchAssoc($sql, $conn)
{
    $result = mysqli_query($conn, $sql);
    $pole = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $pole;
}

function fetch($sql, $conn)
{
    $result = mysqli_query($conn, $sql);
    $pole = mysqli_fetch_array($result, MYSQLI_NUM);
    return $pole;
}

function fetchAll($sql, $conn)
{
    $result = mysqli_query($conn, $sql);
    $pole = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $pole;
}

function akce($sql, $conn)
{
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}
?>