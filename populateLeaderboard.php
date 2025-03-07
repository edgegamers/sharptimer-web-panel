<?php
require_once("formatTimestamp.php");
require_once("steampics.php");

function populatePlayerLeaderboard(mysqli $conn, string $query): void
{
  // $sql = "SELECT DISTINCT `SteamID`, `PlayerName`, `FormattedTime`, `UnixStamp`, `TimesFinished`, `MapName` FROM PlayerRecords WHERE (  `PlayerName` LIKE '{$query}%' OR `SteamID` LIKE '{$query}%' )";
  // $sql .= " AND `Style` = 0";
  // $sql .= " ORDER BY `TimerTicks` LIMIT 100";

  $sql = " 
  SELECT DISTINCT Record.`SteamID`, `PlayerName`, `FormattedTime`, `UnixStamp`, `TimesFinished`, Record.`MapName`, `Rank`.`Rank`
    FROM PlayerRecords Record
    JOIN PlayerRanks `Rank` ON `Rank`.SteamID = Record.SteamID AND `Rank`.MapName = Record.MapName
    WHERE (  (Record.`PlayerName` LIKE '" . $query . "%' OR Record.`SteamID` LIKE '" . $query . "%' ) AND Record.Style = 0 )
    ORDER BY `Rank` ASC
    LIMIT 100
  ";

  $result = $conn->query($sql);
  syslog(LOG_DEBUG, "Query: {$sql}");

  $i = 0;

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $i++;
      $pfp = getProfileImage($row['SteamID']);
      echo '<a target="_blank" href="https://steamcommunity.com/profiles/' . $row['SteamID'] . '"><div';
      if ($i % 2 == 0) {
        echo ' id="stripped"';
      } else {
        echo "";
      }
      echo ' class="row">';
      echo '<span style="background-image: url(\'' . $pfp . '\');">' . $row['Rank'] . '</span>';
      echo '<span><span>' . $row['PlayerName'] . '</span> <span style="margin-left: auto; margin-right: 15px;">' . $row['MapName'] . '</span></span>';
      echo '<span>' . $row['FormattedTime'] . '</span>';
      echo '<span title="' . date(DATE_RFC1036, $row['UnixStamp']) . '">' . formatTimestamp($row['UnixStamp']) . '</span>';
      echo '<span>' . $row['TimesFinished'] . '</span>';
      echo '</div></a>';
    }
  } else {
    echo "<div id='strangerdanger' class='row'>Player not found.</div>";
  }
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
  $sql = "SELECT DISTINCT `SteamID`, `PlayerName`, `FormattedTime`, `UnixStamp`, `TimesFinished`, `MapName` FROM PlayerRecords WHERE ( {$whereClause} )";
  $sql .= " AND `Style` = 0";
  $sql .= " ORDER BY `TimerTicks` LIMIT 100";

  $result = $conn->query($sql);
  syslog(LOG_DEBUG, "Query: {$sql}");

  $i = 0;

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $i++;
      $pfp = getProfileImage($row['SteamID']);
      echo '<a target="_blank" href="https://steamcommunity.com/profiles/' . $row['SteamID'] . '"><div';
      if ($i % 2 == 0) {
        echo ' id="stripped"';
      } else {
        echo "";
      }
      echo ' class="row">';
      echo '<span style="background-image: url(\'' . $pfp . '\');">' . $i . '</span>';
      echo '<span>' . $row['PlayerName'] . '</span>';
      echo '<span>' . $row['FormattedTime'] . '</span>';
      echo '<span title="' . date(DATE_RFC1036, $row['UnixStamp']) . '">' . formatTimestamp($row['UnixStamp']) . '</span>';
      echo '<span>' . $row['TimesFinished'] . '</span>';
      echo '</div></a>';
    }
  } else {
    echo "<div id='strangerdanger' class='row'>Player not found.</div>";
  }
}
