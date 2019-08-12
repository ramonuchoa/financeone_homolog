<?php
	function sendMessage($message){
		$content = array(
			"en" => $message
			);
		
		$fields = [
			'app_id' => "9f7367bf-7c76-4c7b-9080-14a3eddb8435",
			'filters' => [["field" => "tag", "key" => "recebeAlertaLiberacao", "relation" => "=", "value" => 'true']],
			'data' => ["foo" => "bar"],
			'contents' => $content
		];
		
		$fields = json_encode($fields);
    	print("\nJSON sent:\n");
    	print($fields);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8' 
                            'Authorization: Basic OTFjZTBjNDEtYTE1Mi00NDM5LWIyMTMtYjFjZTQ5MmRhNzBl'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}

	$dates = [
		'17/07/2017',
		'15/08/2017',
		'15/09/2017',
		'16/10/2017',
		'16/11/2017',
		'15/12/2017'
	];
	$now = date('d/m/Y', strtotime(' +2 days')); //Queremos enviar o alerta com 2 dias de antecedência
	
	$message = null;
	foreach($dates as $key => $date){
		if ($date == $now){
			$message = 'Olá! Um novo lote de restituição do IRPF 2017 será liberado em ' . $date;
		}
	}

	if ($message != null){
		$response = sendMessage($message);
		$return["allresponses"] = $response;
		$return = json_encode( $return);
		
		print("\n\nJSON received:\n");
		print($return);
		print("\n");
	}
?>
