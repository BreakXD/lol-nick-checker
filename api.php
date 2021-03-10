<?php

$nick = $_GET['conta'];
$region = $_GET['region'];

$nick = strtolower($nick);
$newAccountAvailable;
$isAvailable = false; 
$daysToAvailable = 100; 
$invalidNick = false;
//$nick = str_replace(' ', '',$nick);

$header = array(
	"Cookie: _cfduid=d3bdd34090e5b14897c63a2efddfdc6eb1614576345; csrftoken=TiWNp5rjcYcE4c97QagehtW7HwbM32shO4d8rzOkbyiLuG4VymCJQbH17pzr406F; lols_region=BR; _ym_d=1614607822; _ym_uid=1614607822422875461; _ym_isad=2; _ym_visorc=w",
	"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.182 Safari/537.36 OPR/74.0.3911.203",
	"Authority: lols.gg"


);

$api = 'https://lols.gg/en/name/checker/'.$region.'/'.$nick.'/';

$ch = curl_init($api);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);
curl_close($ch);

$response = strtolower($response);


if(strpos($response, $nick.' is available!</h4>') || strpos($response, $nick.' is available!</h2>')){
	$isAvailable = true;
	if(strpos($response, 'it is available for new accounts')){
		$newAccountAvailable = true;
	}else{
		$newAccountAvailable = false;
	}

}else{
	$isAvailable = false;
	if(!strpos($response, 'error: ')){
		if(!strpos($response, "if this name can't be taken now the account is almost certainly banned and the name will never expire")){

			for($i = 0; $i < 1000; $i++){
				if(strpos($response, 'is available in '.$i.' days.')){
					$daysToAvailable = $i;
					$i = 1000;
				}
			}
		}else{
			$provavel = true;
		}	
	}else{
		$invalidNick = true;
	}
}
/* RETORNAR INFO */

if($isAvailable || $daysToAvailable < 31){
	if($daysToAvailable < 31){
		echo $nick.' está disponível! [ '.$daysToAvailable.' dias para liberar ]';
	}else{
		if($newAccountAvailable){
			echo $nick.' está disponível para novas contas!';
		}else{
			echo $nick.' está disponível para alteração de nick!';

		}

	} //Conta nova?

}else{ //Indisponivel faltando 31 dias +
	if($invalidNick){
		echo $nick.' é um nick inválido !';
	}elseif(isset($provavel)){
		echo $nick.' está disponível para novas contas! [Provável]';
	}else{
		echo $nick.' está indisponível por '.$daysToAvailable.' dias!';
	}
} 


?>
