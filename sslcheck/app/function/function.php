<?php
	function getCert($domain) {
	    $g = stream_context_create (array("ssl" => array("capture_peer_cert" => true)));
	    $r = stream_socket_client("ssl://{$domain}:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $g);
	    $cont = stream_context_get_params($r);
	    return openssl_x509_parse($cont["options"]["ssl"]["peer_certificate"]);
	}

	function is_valid_domain_name($domain_name)
	{
	    return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
	            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
	            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
	}

?>