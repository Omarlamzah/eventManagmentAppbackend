<?php
$mysqli = new mysqli("shareddb-t.hosting.stackcp.net", "associationamal_v2-313331eec6", "OOxIog%#0b?C", "associationamal_v2-313331eec6");
//$mysqli = new mysqli("localhost", "root", "",  "associationamal-313637a1dd");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

?>