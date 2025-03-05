<?php
$cacheDuration = 3600 * 3; // Cache duration in seconds (3 hours)

// Cache file path
$cacheDir = __DIR__ . "/cache";
$cacheFile = $cacheDir . "/steam_profile_cache.json";

// Ensure the cache directory exists
if (!is_dir($cacheDir)) {
  mkdir($cacheDir, 0777, true); // Create the directory with appropriate permissions
}

// Function to fetch the Steam profile XML
function fetchSteamProfileXML($steamId)
{
  $url = "https://steamcommunity.com/profiles/{$steamId}/?xml=1";
  $response = file_get_contents($url);

  if ($response === false) {
    return null;
  }

  return $response;
}

// Function to get profile image from the Steam profile XML or from cache
function getProfileImage($steamId)
{
  global $cacheDuration, $cacheFile;

  // Check if the cache exists and is still valid
  if (file_exists($cacheFile)) {
    $cache = json_decode(file_get_contents($cacheFile), true);
    if (isset($cache[$steamId]) && (time() - $cache[$steamId]['timestamp']) < $cacheDuration) {
      // Return cached data
      return $cache[$steamId]['image_link'];
    }
  }

  error_log("Fetching profile pic for {$steamId}");

  // Fetch from Steam profile XML
  $xmlContent = fetchSteamProfileXML($steamId);

  if ($xmlContent === null) {
    return 'Error fetching Steam profile.';
  }

  // Parse XML to extract the avatarFull URL
  try {
    $xml = simplexml_load_string($xmlContent);
    if ($xml === false) {
      throw new Exception('Error parsing XML');
    }

    $avatarFull = (string) $xml->avatarFull;

    if (!empty($avatarFull)) {
      // Load the cache from file, or initialize it if it doesn't exist
      $cache = file_exists($cacheFile) ? json_decode(file_get_contents($cacheFile), true) : [];

      // Update the cache
      $cache[$steamId] = [
        'timestamp' => time(),
        'image_link' => trim($avatarFull)
      ];

      // Save the updated cache back to the file
      file_put_contents($cacheFile, json_encode($cache));

      return trim($avatarFull); // Full-size avatar image
    }

    return 'Profile image not found.';
  } catch (Exception $e) {
    return 'Error parsing profile XML: ' . $e->getMessage();
  }
}
