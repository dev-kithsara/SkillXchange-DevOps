<?php
$conn = new mysqli('db', 'root', 'root', 'skillxchange', 3306);
$res = $conn->query("SELECT email, username, role, password FROM users");

$users = [];
while($r = $res->fetch_assoc()) {
    $users[] = $r;
}

$names = [
    'Ayesh', 'ayesh', 'Dilini', 'dilini', 'Ravindu', 'ravindu', 
    'Tharushi', 'tharushi', 'Nimesh', 'nimesh', 'Kithsara', 'kithsara',
    'CodeCraft', 'codecraft', 'CodeCraftLabs', 'codecraftlabs', 'BlueWave', 'bluewave',
    'Devinda', 'devinda'
];

$suffixes = [
    '@123', '123!', '@1234', '1234', '12345', '123', '@1', '1', '2', '3', '4', '5',
    '98', '21', '01', '02', '@98', '@21', '@01', '@02', '2024', '2025', '2026',
    '@2024', '@2025', '@2026', 'Password@123', 'Pass@123'
];

$cands = [];
foreach ($names as $n) {
    foreach ($suffixes as $s) {
        $cand = $n . $s;
        if (strlen($cand) >= 8) $cands[] = $cand;
    }
}

// Also test digit repeats 8 to 12 chars
for ($i = 0; $i <= 9; $i++) {
    for ($len = 8; $len <= 12; $len++) {
        $cands[] = str_repeat((string)$i, $len);
    }
}

$cands = array_unique($cands);

foreach ($users as $u) {
    foreach ($cands as $pwd) {
        if (password_verify($pwd, $u['password'])) {
            echo "MATCH: [{$u['role']}] {$u['email']} ({$u['username']}) => '{$pwd}'\n";
            break;
        }
    }
}
