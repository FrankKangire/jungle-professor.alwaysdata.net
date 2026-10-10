<?php
// This pings your Node.js server so Render wakes it up from sleep
$node_url = "https://jungle-professor-game-server.onrender.com"; 

echo "<html><body style='font-family:sans-serif; text-align:center; padding-top:50px;'>";
echo "<h1>System Warm-up</h1>";

$ch = curl_init($node_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$res = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($status > 0) {
    echo "<p style='color:green;'>✔️ Node Game Server is Waking Up/Online.</p>";
} else {
    echo "<p style='color:orange;'>⏳ Sending wake-up signal... please refresh in 30 seconds.</p>";
}

echo "<br><a href='index.php' style='padding:10px; background:blue; color:white; text-decoration:none; border-radius:5px;'>Enter Website</a>";
echo "</body></html>";
?>
