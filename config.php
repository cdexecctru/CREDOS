<?php
session_start();

define('SUPABASE_URL', 'https://zotbveknazkneymhxyaj.supabase.co');
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InpvdGJ2ZWtuYXprbmV5bWh4eWFqIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjE2OTEwNDAsImV4cCI6MjA3NzI2NzA0MH0.mIx-h6qKcI3IcvIUN8s2W5g8H5jfWx-kgGq4wFloOm4');

define('SERVER_NAME', 'Evurt Craft');
define('SERVER_IP', 'EvurtCraft.enderman.cloud');
define('DISCORD_URL', 'https://discord.gg/zbFEneQX6p');

function supabaseRequest($endpoint, $method = 'GET', $data = null) {
    $url = SUPABASE_URL . '/rest/v1/' . $endpoint;

    $headers = [
        'apikey: ' . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
        'Content-Type: application/json',
        'Prefer: return=representation'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'code' => $httpCode,
        'data' => json_decode($response, true)
    ];
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUser() {
    if (!isLoggedIn()) {
        return null;
    }

    $response = supabaseRequest('users?id=eq.' . $_SESSION['user_id'] . '&select=*');

    if ($response['code'] === 200 && !empty($response['data'])) {
        return $response['data'][0];
    }

    return null;
}
