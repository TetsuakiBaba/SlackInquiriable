<?php
// slack.token.php からWebhook URLを読み込む
include 'settings.php';

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

// Slackに送信するメッセージを構成
$text = $message_to_slack . "\n";
$text .= "Name: {$name}\n";
$text .= "Email: {$email}\n";
$text .= "Inquiry Details: {$message}";

$data = [
    'text' => $text,
    'channel' => $channel,
];

$ch = curl_init($webhook_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$result = curl_exec($ch);
$error = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// cURLエラーまたはSlackからの応答が200以外の場合は失敗とみなす
if ($error || $http_code != 200) {
    header('HTTP/1.1 500 Internal Server Error');
    echo 'Failed to send to Slack';
} else {
    echo $result; // 成功時の結果を返す
}
