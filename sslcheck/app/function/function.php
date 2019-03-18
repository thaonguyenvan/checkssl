<?php
	function getCert($domain) {
	    $g = stream_context_create (array("ssl" => array("capture_peer_cert" => true)));
	    $r = stream_socket_client("ssl://{$domain}:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $g);
	    $cont = stream_context_get_params($r);
	    return openssl_x509_parse($cont["options"]["ssl"]["peer_certificate"]);
	}

	function url_test( $url ) {
		  $timeout = 10;
		  $ch = curl_init();
		  curl_setopt ( $ch, CURLOPT_URL, $url );
		  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		  curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
		  $http_respond = curl_exec($ch);
		  $http_respond = trim( strip_tags( $http_respond ) );
		  $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		  if ( ( $http_code == "200" ) || ( $http_code == "302" ) ) {
		    return true;
		  } else {
		    return false;
		  }
		  curl_close( $ch );
	}
	
	function checkOnline($domain) {
		   $curlInit = curl_init($domain);
		   curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
		   curl_setopt($curlInit,CURLOPT_HEADER,true);
		   curl_setopt($curlInit,CURLOPT_NOBODY,true);
		   curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

		   //get answer
		   $response = curl_exec($curlInit);

		   curl_close($curlInit);
		   if ($response) return true;
		   return false;
		}

?>