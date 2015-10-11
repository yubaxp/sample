<?php
define("API_USERNAME", "ユーザ名");
define("API_PASSWORD", "パスワード");
define("API_TENANT_ID", "テナントID");

define("API_IDENTITY_SERVICE", "https://identity.tyo1.conoha.io/v2.0");
define("API_DNS_SERVICE", "https://dns-service.tyo1.conoha.io");
define("API_OBJECT_STORAGE_SERVICE", "https://object-storage.tyo1.conoha.io/v1/nc_" . API_TENANT_ID);

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
