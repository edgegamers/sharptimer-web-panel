<?php
function formatTimestamp($timestamp)
{
  $currentTime = time();
  $difference = $currentTime - $timestamp;
  if ($difference < 60) {
    return $difference . " seconds ago";
  } elseif ($difference < 3600) {
    $minutes = floor($difference / 60);
    return $minutes . " minute" . ($minutes > 1 ? "s" : "") . " ago";
  } elseif ($difference < 86400) {
    $hours = floor($difference / 3600);
    $minutes = floor(($difference % 3600) / 60);
    return $hours . " hour" . ($hours > 1 ? "s" : "") . ", " . $minutes . " minute" . ($minutes > 1 ? "s" : "") . " ago";
  } elseif ($difference < 604800) {
    $days = floor($difference / 86400);
    return $days . " day" . ($days > 1 ? "s" : "") . " ago";
  } else {
    return date("m/d/y H:i", $timestamp);
  }
}
