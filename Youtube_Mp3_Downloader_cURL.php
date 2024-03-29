<?php
$url = 'https://www.youtube.com/watch?v=video_id'; // Replace video_id

// Initialize a cURL session
$ch = curl_init();

// Set the URL to fetch
curl_setopt($ch, CURLOPT_URL, "http://youtube.com/get_video_info?video_id=" . parse_url($url, PHP_URL_QUERY));

// Return the response data instead of printing it
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the cURL request
$videoData = curl_exec($ch);

// Close the cURL session
curl_close($ch);

// Extract the MP3 download URL from the video data
parse_str($videoData, $videoDataArray);
$streams = $videoDataArray['url_encoded_fmt_stream_map'];
$streams = explode(',', $streams);
$mp3Stream = '';

foreach ($streams as $stream) {
  parse_str($stream, $streamData);
  if (strpos($streamData['type'], 'audio/mp4;') !== false) {
    $mp3Stream = $streamData['url'];
    break;
  }
}

// Download the MP3 file
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $mp3Stream);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$mp3Data = curl_exec($ch);
curl_close($ch);
file_put_contents('video.mp3', $mp3Data);

echo 'Done';

?>
