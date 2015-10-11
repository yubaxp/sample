<?php
require_once 'conoha_common.php';

/**
 * コンテナ情報取得
 * コンテナ内にオブジェクトが存在している場合に情報が取得できる
 *
 * @param unknown $token
 * @param unknown $container
 * @param unknown $options
 */
function getContainerInfo($token, $container, $options = null)
{
	$url = API_OBJECT_STORAGE_SERVICE . "/" . $container;

	$headers = array(
		"Accept: application/json",
		"X-Auth-Token: {$token}"
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$res_json = curl_exec($ch);
	$res_info = curl_getinfo($ch);
	$res_errno = curl_errno($ch);
	$res_error = curl_error($ch);
	curl_close($ch);

	if (CURLE_OK !== $res_errno) {
		var_dump($res_info, $res_errno, $res_error);
		exit();
	}

	$res_data = json_decode($res_json);

	return $res_data;
}

/**
 * コンテナ作成
 *
 * @param unknown $token
 * @param unknown $container
 * @param unknown $options
 * @return mixed
 */
function createContainer($token, $container, $options = null)
{
	$url = API_OBJECT_STORAGE_SERVICE . "/" . $container;

	$headers = array(
		"X-Auth-Token: {$token}"
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$res_json = curl_exec($ch);
	$res_info = curl_getinfo($ch);
	$res_errno = curl_errno($ch);
	$res_error = curl_error($ch);
	curl_close($ch);

	if (CURLE_OK !== $res_errno) {
		var_dump($res_info, $res_errno, $res_error);
		exit();
	}

	$res_data = json_decode($res_json);

	return $res_data;
}

/**
 * オブジェクトアップロード
 *
 * @param unknown $token
 * @param unknown $file_path
 * @param unknown $to_path
 * @param unknown $options
 */
function upload($token, $file_path, $to_path, $options = null)
{
	$url = API_OBJECT_STORAGE_SERVICE . $to_path;

	$fs = filesize($file_path);
	$fp = fopen($file_path, "r");

	$headers = array(
		"Accept: application/json",
		"X-Auth-Token: {$token}"
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_PUT, true);
	curl_setopt($ch, CURLOPT_INFILESIZE, $fs);
	curl_setopt($ch, CURLOPT_INFILE, $fp);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$res_json = curl_exec($ch);
	$res_info = curl_getinfo($ch);
	$res_errno = curl_errno($ch);
	$res_error = curl_error($ch);
	curl_close($ch);

	if (CURLE_OK !== $res_errno) {
		var_dump($res_info, $res_errno, $res_error);
		exit();
	}

	$res_data = json_decode($res_json);

	return $res_data;
}
