<?php
require_once 'conoha_common.php';

/**
 * トークン取得
 * @param string $options
 */
function getToken($options = null)
{
	$url = API_IDENTITY_SERVICE . "/tokens";

	$headers = array(
		"Accept: application/json"
	);

	$req_data = array(
		"auth" => array(
			"passwordCredentials" => array(
				"username" => API_USERNAME,
				"password" => API_PASSWORD
			),
			"tenantId" => API_TENANT_ID
		)
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($req_data));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$res_json =  curl_exec($ch);
	$res_data = json_decode($res_json);
	curl_close($ch);

	return $res_data;
}

/**
 * ドメイン一覧取得
 * @param unknown $token
 * @param string $options
 * @return mixed
 */
function getDomains($token, $options = null)
{
	$url = API_DNS_SERVICE . "/v1/domains";

	$headers = array(
		"Accept: application/json",
		"X-Auth-Token: {$token}"
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$res_json =  curl_exec($ch);
	$res_data = json_decode($res_json);
	curl_close($ch);

	return $res_data;
}

/**
 * ドメイン詳細情報取得
 * @param unknown $token
 * @param unknown $id
 * @param string $options
 */
function getDomain($token, $id, $options = null)
{
	$url = API_DNS_SERVICE . "/v1/domains/{$id}/records";

	$headers = array(
		"Accept: application/json",
		"X-Auth-Token: {$token}"
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$res_json =  curl_exec($ch);
	$res_data = json_decode($res_json);
	curl_close($ch);

	return $res_data;
}

/**
 * ドメイン生成
 * @param unknown $token
 * @param unknown $domain
 * @param string $options
 * @return mixed
 */
function createDomain($token, $domain, $options = null)
{
	$url = API_DNS_SERVICE . "/v1/domains";

	$headers = array(
		"Accept: application/json",
		"Content-Type: application/json",
		"X-Auth-Token: {$token}"
	);

	$req_data = array(
		"name" => $domain["name"],
		"ttl" => 3600,
		"email" => $domain["email"],
		"gslb" => 0
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($req_data));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$res_json =  curl_exec($ch);
	$res_data = json_decode($res_json);
	curl_close($ch);

	return $res_data;
}

/**
 * ドメインレコード登録
 * @param unknown $token
 * @param unknown $id
 * @param unknown $record
 * @param string $options
 * @return mixed
 */
function createRecord($token, $id, $record, $options = null)
{
	$url = API_DNS_SERVICE . "/v1/domains/{$id}/records";

	$headers = array(
		"Accept: application/json",
		"Content-Type: application/json",
		"X-Auth-Token: {$token}"
	);

	$req_data = array(
		"name" => $record["name"],
		"type" => $record["type"],
		"data" => $record["data"]
	);

	if (isset($record["priority"])) {
		$req_data["priority"] = $record["priority"];
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($req_data));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$res_json =  curl_exec($ch);
	$res_data = json_decode($res_json);
	curl_close($ch);

	return $res_data;
}

////////////////////////////////////////////////////////////////////////////////
// 登録するドメイン
$reg_domain_names = array(
	array("name" => "example.com.", "email" => "dummy@example.com")
);

// 登録するレコード
$reg_records = array(
	array("name" => null, "type" => "MX", "data" => "ASPMX.L.GOOGLE.COM.", "priority" => 1),
	array("name" => null, "type" => "MX", "data" => "ALT1.ASPMX.L.GOOGLE.COM.", "priority" => 5),
	array("name" => null, "type" => "MX", "data" => "ALT2.ASPMX.L.GOOGLE.COM.", "priority" => 5),
	array("name" => null, "type" => "MX", "data" => "ALT3.ASPMX.L.GOOGLE.COM.", "priority" => 10),
	array("name" => null, "type" => "MX", "data" => "ALT4.ASPMX.L.GOOGLE.COM.", "priority" => 10)
);

// トークン取得
$tokens = getToken();
$token = $tokens->access->token->id;

foreach ($reg_domain_names as $reg_domain_name) {
	// ドメイン登録
	$domain = array(
		"name" => $reg_domain_name["name"],
		"email" => $reg_domain_name["email"]
	);
	$res = createDomain($token, $domain);

	$id = $res->id;

	// レコード登録
	foreach ($reg_records as $reg_record) {
		if (!$reg_record["name"]) {
			$reg_record["name"] = $reg_domain_name["name"];
		}
		$res = createRecord($token, $id, $reg_record);
	}
}
