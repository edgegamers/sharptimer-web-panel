<?php

require("../../config.php");
require("../../formatTimestamp.php");
require_once("../../populateLeaderboard.php");

$i = 0;
if (!isset($_POST['input']))
  return;
$input = $conn->real_escape_string($_POST['input']);
populatePlayerLeaderboard($conn, $input);
