<?php

# Requester

/**
 * 
 * Light Sec Toolkit 17/06/2023
 * 
 * $file = 'index';
 * include __DIR__ . '/requester.php';
 * include dirname(__DIR__, 1) . '/requester.php';
 * 
 * Daniel Eskelsen (31) 99175 5993 <eskelsen@yahoo.com>
 *
 * © Microframeworks <dev@microframeworks.com>
 *
 * Use fora do contexto de criação por sua conta e risco.
 * 
 */

# Timezone
date_default_timezone_set('America/Sao_Paulo');

$file = empty($file) ? 'lightsec' : $file;
$time = date('Y-m-d H:i:s');
$ip   = empty($_SERVER['REMOTE_ADDR']) ? 'unknow ip' : $_SERVER['REMOTE_ADDR'];
$uri  = empty($_SERVER['REQUEST_URI']) ? '[command line]' : trim($_SERVER['REQUEST_URI'], '/');
$info = $time . ': ' . $ip . ' :: ' . $uri;

# Pure Request (GET)
file_put_contents(__DIR__ . '/' . $file . '_requests.txt', $info . "\r\n", FILE_APPEND);

# Global Request (REQUEST)
if (!empty($_REQUEST)) {
	$data = $_REQUEST;
	$data['headers'] = getallheaders();
	$json = json_encode($data, JSON_PRETTY_PRINT);
	$vars = "\r\n================\r\n" . $info . "\r\n" . $json . "\r\n================\r\n";
	file_put_contents(__DIR__ . '/' . $file . '_global.txt', $vars . "\r\n", FILE_APPEND);
}

# Cli Request
if (!empty($argv)) {
	file_put_contents(__DIR__ . '/' . $file . '_cli_argv.txt', $info . ': ' . json_encode($argv) . "\r\n", FILE_APPEND);
}

# Body Request (php://input)
$content = file_get_contents('php://input');
$content = trim($content);

if (empty($content)) {
	return;
}

$rawdata = json_decode($content, 1);

if ($rawdata) {
	$data['headers'] = getallheaders();
	$data['data']    = $rawdata;
	$json = json_encode($data, JSON_PRETTY_PRINT);
	$body = "\r\n================\r\n" . $info . "\r\n" . $json . "\r\n================\r\n";
	file_put_contents(__DIR__ . '/' . $file . '_body.txt', $body, FILE_APPEND);
} else {
	$data['headers'] = getallheaders();
	$data['content'] = $content;
	$json = json_encode($data, JSON_PRETTY_PRINT);
	$body = "\r\n================\r\n" . $info . "\r\n" . $json . "\r\n================\r\n";
	file_put_contents(__DIR__ . '/' . $file . '_body.txt', $body, FILE_APPEND);
}
