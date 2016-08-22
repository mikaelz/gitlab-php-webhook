<?php

$logPath = str_replace('.php', '.log', __FILE__);

error_reporting(-1);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', $logPath);

$logFile = fopen($logPath, 'w+');
fwrite($logFile, 'Request on ['.date("Y-m-d H:i:s").'] from '.$_SERVER['REMOTE_ADDR'].PHP_EOL);

$client_token = isset($_SERVER['HTTP_X_GITLAB_TOKEN']) ? $_SERVER['HTTP_X_GITLAB_TOKEN'] : '';
if ($client_token != md5('MY_SECRET')) {
    echo "Error 403";
    fwrite($logFile, 'Invalid token: '.var_export($_SERVER, true).PHP_EOL);
    return;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

file_put_contents($logPath, var_export($data, true));
fclose($logFile);
