<?php
// Database connection:
// Database connection:
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'database';
mysqli_report(MYSQLI_REPORT_OFF); // Disable automatic error reporting

try {
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_errno) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    error_log($e->getMessage()); // Log the real error securely
    die("Connection failed. Please try again later."); // Show a safe message
}


// PAGE CONFIG:
#Page title:
$pagetitle = "SharpTimer Web Panel";

#Hyperlinks for navigation | If you don't need it you can just delete all the contents or variable.
$links = '
<li><a href="https://discord.gg/edgegamers"><i class="fa-brands fa-discord"></i></a></li>
';

#Default map for leaderboard which should load when joining a website
$defaultmap = "surf_utopia_njv";

// Map sections => true (on) or false (off)
#It's creates a map sections for each mode (kz, surf, bunnyhop) in map list. If it's turned off there won't be any sections.
#It works by looking for a maps that starts with kz_, surf_, bh_ prefix,
#so if map doesn't have it before its name it's gonna be showed in uncategorized category at the end of maplist
$mapdivision = false; 

#Which tab with map should be opened as a default - Only works if $mapdivision = true
#(can be surf, bh, kz, other)
$tabopened = "other";

// How many records should be displayed in leaderboard:
$limit = 100;

#Footer description:
$footerdesc = 'EdgeGamers.com | Beginner Surf | connect surf.edgm.rs';

// GameQ integration - Creates poorish serverlist at index page.
#GameQ (serverlist) true (on) or false (off)
$serverlist = false;

#Server list:
#Fakename can be omitted or empty if you don't want it.
#IP has to be numeric not domain. If you prefer to display domain than real ip use 'fakeip'.
$serverq = array(
    0 => array(
        'type' => 'csgo',
        'host' => '66.118.246.21:27015',
        'fakename' => '',
        'fakeip' => 'surf.edgm.rs'
    )
);
?>
