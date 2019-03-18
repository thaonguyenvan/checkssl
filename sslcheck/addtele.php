<?php
    function getUpdate($offset){
    	$bottoken = 'xxxx';
	    $result=file_get_contents('https://api.telegram.org/bot'.$bottoken.'/getUpdates?offset='.$offset);
	    $result = json_decode($result, true);

	    return $result;
    }

    function sendMessage($chatID, $messaggio) {
	    $token = 'xxxx';

	    $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
	    $url = $url . "&text=" . urlencode($messaggio);
	    $ch = curl_init();
	    $optArray = array(
	            CURLOPT_URL => $url,
	            CURLOPT_RETURNTRANSFER => true
	    );
	    curl_setopt_array($ch, $optArray);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    return $result;
	}
    // }
    $conn = @mysqli_connect("localhost","root","xxx","sslcheck") or die("Lỗi kết nối");
    $offset = xxx;
    while (true) {
	  	$result = getUpdate($offset);
	 	// $a = $result['result'];
		// $num_result = count($a);
	    if ($result['result']) {
	    	// echo '<pre>';
	    	// print_r($result);
	    	// echo '</pre>';

	    	if($result['result'][0]['message']['text']){
	    		$text = $result['result'][0]['message']['text'];
	    		$chat_id = $result['result'][0]['message']['chat']['id'];
	    		if (strpos($text, '/start') !== false) {
			    	$t = explode(" ", $text);
					$status_code = $t[1];
					$sqlSelect = "SELECT chat_id FROM tele_noti WHERE status_code=".$status_code;
					$r = mysqli_query($conn,$sqlSelect) or die("Lỗi câu truy vấn");

					if(mysqli_num_rows($r) > 0){
						$row = mysqli_fetch_array($r);
						if($row['chat_id'] == ""){
							$sqlUpdate = "UPDATE tele_noti SET chat_id = ".$chat_id.", status = 1 WHERE status_code=".$status_code;
							mysqli_query($conn,$sqlUpdate) or die("Lỗi câu truy vấn");
							sendMessage($chat_id, "Bạn đã đăng kí nhận thông báo thành công cho tài khoản này!");
						}
						else{
							sendMessage($chat_id, "Tài khoản này đã được xác thực");
						}
					} else {
						sendMessage($chat_id, "Tài khoản này đã được xác thực");
					}
				}

	    	}

	    	$offset = $offset + 1;
	    	// $file = fopen("thao.txt","a");
      //       fwrite($file,$text.PHP_EOL);
      //       fclose($file);
	    }
	    sleep(1);
    }
?>
