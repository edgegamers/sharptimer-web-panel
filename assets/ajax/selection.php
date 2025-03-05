<?php
require("../../config.php");
require("../../formatTimestamp.php");
require_once("../../populateLeaderboard.php");

$id = $conn->real_escape_string($_POST['id']);

populateMapLeaderboard($conn, $id);
?>