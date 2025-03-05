<?php
require_once("formatTimestamp.php");

function populatePlayerLeaderboard(mysqli $conn, string $query): void
{
  populateLeaderboard($conn, "`PlayerName` LIKE '{$query}%' OR `SteamID` LIKE '{$query}%'");
}

function populateMapLeaderboard(mysqli $conn, string $query): void
{
  populateLeaderboard($conn, "`MapName` = '{$query}'");
}

/**
 * Searches for player records and outputs them as HTML.
 *
 * @param mysqli $conn The database connection.
 * @param string $searchTerm The search term to use for player name or SteamID.
 * @param string|null $customWhereClause Optional custom WHERE clause.
 * @return void Outputs HTML directly.
 */
function populateLeaderboard(mysqli $conn, string $whereClause): void
{
  $sql = "SELECT DISTINCT `SteamID`, `PlayerName`, `FormattedTime`, `UnixStamp`, `TimesFinished` FROM PlayerRecords WHERE ( {$whereClause} )";
  $sql .= " AND `Style` = 0";
  $sql .= " ORDER BY `TimerTicks`";

  $result = $conn->query($sql);
  syslog(LOG_DEBUG, "Query: {$sql}");
  
  $i = 0;

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $i++;
      echo '<a target="_blank" href="https://steamcommunity.com/profiles/' . $row['SteamID'] . '"><div';
      if ($i % 2 == 0) {
        echo ' id="stripped"';
      } else {
        echo "";
      }
      echo ' class="row">';
      echo '<span>' . $i . '</span>';
      echo '<span>' . $row['PlayerName'] . '</span>';
      echo '<span>' . $row['FormattedTime'] . '</span>';
      echo '<span>' . formatTimestamp($row['UnixStamp']) . '</span>';
      echo '<span>' . $row['TimesFinished'] . '</span>';
      echo '</div></a>';
    }
  } else {
    echo "<div id='strangerdanger' class='row'>Player not found.</div>";
  }
}

// Example usage with a custom WHERE clause:
// $searchTerm = "John"; // Example search term
// $customWhere = "`TimesFinished` > 5"; // Example custom WHERE clause

// Assuming $conn is your database connection
// searchPlayerRecords($conn, $searchTerm, $customWhere);

//Example using POST data:
//if(isset($_POST['input']) && isset($_POST['where'])){
//  searchPlayerRecords($conn, $_POST['input'], $_POST['where']);
//}
