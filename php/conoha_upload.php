<?php
require_once 'conoha_objectstorage.php';

$file_path = $argv[1];
$to_path = $argv[2];

// トークン取得
$tokens = getToken();
$token = $tokens->access->token->id;

// アップロード
upload($token, $file_path, $to_path);

exit(0);
