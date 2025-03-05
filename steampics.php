<?php
$cacheDuration = 3600 * 3; // Cache duration in seconds (1 hour)

// In-memory cache (you can store this in a session or a global array for persistent cache if needed)
$cache = [];

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
function getProfileImage($steamId, $cache, $cacheDuration)
{
  // Check if the cache exists and is still valid
  if (isset($cache[$steamId]) && (time() - $cache[$steamId]['timestamp']) < $cacheDuration) {
    // Return cached data
    return $cache[$steamId]['image_link'];
  }

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
      // Cache the result in memory (timestamp included)
      $cache[$steamId] = [
        'timestamp' => time(),
        'image_link' => trim($avatarFull)
      ];

      return trim($avatarFull); // Full-size avatar image
    }

    return 'Profile image not found.';
  } catch (Exception $e) {
    return 'Error parsing profile XML: ' . $e->getMessage();
  }
}