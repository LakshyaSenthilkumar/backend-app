<?php
require 'vendor/autoload.php';

use Aws\SecretsManager\SecretsManagerClient;

$client = new SecretsManagerClient([
    'region' => 'us-east-1',
    'version' => 'latest'
]);

$secretName = 'orderdb-rds-secret';

$result = $client->getSecretValue([
    'SecretId' => $secretName,
]);

$secret = json_decode($result['SecretString'], true);

$db_host = $secret['host'];
$db_user = $secret['username'];
$db_pass = $secret['password'];
$db_name = 'orderdb';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("DB connection failed");
}
?>
