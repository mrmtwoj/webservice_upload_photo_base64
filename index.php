<?php
function _die($status,$msg,$server_status,$server_msg){
    header("HTTP/1.1 ".$server_status.$server_msg."");
    die(json_encode(array('status'=>$status, 'msg'=>$msg),JSON_UNESCAPED_UNICODE));
}

header('Content-Type: application/json; charset=utf-8');

$headers = apache_request_headers();

($_SERVER['REQUEST_METHOD'] !== 'POST')? _die(100,'It is not possible to process this request',503,'Method Option Error'):true;
empty($headers['authorization'])? _die(102,'The authentication code cannot be processed',503,'Authorization Empty'):true;
($headers['authorization'] !== "aw8c61321ca6w7sdaACww41@#")? _die(103,'The authentication code is not correct',503,'Authorization Failed'):true;

$url = $_FILES['upload']['tmp_name'];
$pathinfo = pathinfo($url);
$data = file_get_contents($url);
$size = $headers["Content-Length"];
$getimagesize = getimagesize($url);
$base64 = base64_encode($data);

$array = array(
	"upload_file"=>array(
		"data"=>array(
			"size"=>$headers["Content-Length"]/1024,
			"width"=>$getimagesize[0],
			"height"=>$getimagesize[1],
			"format"=>$getimagesize['mime'],
			"binary"=>$getimagesize['bits'],
		),
		"hash"=>array(
			"MD5"=>hash('MD5',$getimagesize['mime'].$headers["Content-Length"].$getimagesize['bits'].$getimagesize[0]),
			"SHA1"=>hash('sha1',$getimagesize['mime'].$headers["Content-Length"].$getimagesize['bits'].$getimagesize[0])
		),
		"base64"=>$base64,
	),
);

echo json_encode($array);
?>
