<?php
  function connect_db(){
  global $connection;
  $host="localhost";
  $user="test";
  $pass="t3st3r123";
  $db="test";
  $connection = mysqli_connect($host, $user, $pass, $db) or die("ei saa ühendust mootoriga- ".mysqli_error());
  mysqli_query($connection, "SET CHARACTER SET UTF8") or die("Ei saanud baasi utf-8-sse - ".mysqli_error($connection));
}

connect_db();

echo "Teretulemast klient/server eksami lehele! <br>";

$ip_client = $_SERVER['REMOTE_ADDR']; //Ip serverilt, salvestad muutujasse
echo "$ip_client";
$sql = "INSERT INTO ksaare_ipaddress (id, ip_address) VALUES (NULL, '$ip_client')"; //sisestad andmebaasi id ja ip

if ($connection->query($sql) === TRUE) { //kontrollib kas salvestas v''rtuse
    echo "<br> Panime su IP kirja! <br> <br>";
} else {
    echo "Viga: " . $sql . "<br>" . $connection->error;
}

$query = "SELECT ip_address, COUNT(*) c FROM ksaare_ipaddress GROUP BY ip_address HAVING c > 1";
$result = mysqli_query($connection, $query) or die("$query - ".mysqli_error($connection));
$results = array();
while ($row = mysqli_fetch_assoc($result)) {
  $results[] = $row;
}

echo "Siin all on kirjas milliselt IP aadressilt ja kui palju sessioone on olnud! <br> <br>";

echo json_encode($results);

$query = "SELECT COUNT(DISTINCT ip_address) AS NumberofIPs FROM ksaare_ipaddress";
$result = mysqli_query($connection, $query) or die("$query - ".mysqli_error($connection));
echo "<br> <br>Ja palju unikaalseid IP aadresse on? Vastus tuleks siia kui aega oleks viimane query result korralikult toimima panna: <br> <br> ";
// $count = mysql_fetch_array($result);


?>
