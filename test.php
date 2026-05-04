<?php

header('Content-Type: text/html; charset=utf-8');
$apiKey = '3af9fea10037a0d3bc7de800df308adb';
$url = 'https://api.themoviedb.org/3/tv/popular?api_key=' . $apiKey . '&language=fr-FR';

$response = file_get_contents($url);
$data = json_decode($response, true);

foreach($data['results'] as $series) {
    echo $series['name'] . ' — ' . $series['first_air_date'] . '<br>';
}