<html>
<head>
	<title>문제 1</title>
</head>
<body>
<?php


var_dump(mktime());
exit;

$array_val = array(2,9,3,5);

var_dump($array_val);
echo '<hr/>';

for($i=0; $i<count($array_val); $i++){
	for($j=0; $j<count($array_val); $j++){
		if($array_val[$i] > $array_val[$j]){
			$t = $array_val[$i];
			$array_val[$i] = $array_val[$j];
			$array_val[$j] = $t;
		}
	}
}

$a = 0;
$b = 0;
$c = 0;
$d = 0;

/*
가장 큰 시간 만들기
1. 시간의 첫자는 2를 초과할수없다.
2. 가장 큰 시간은 23시 59분이다 24시는 0으로 처리
- 첫번째는 2이하로 측정
- 두번째는 3이하로 측정
- 세번째는 5이하로 측정
- 네번째는 나머지 숫자
*/

var_dump($array_val);
echo '<hr/>';

//첫번째
foreach($array_val as $key=>$value){
	if($value < 3 && $value > $a){
		$a = $value;
		unset($array_val[$key]);	//사용된 값 제거
	}
}

if($a == 0){
	echo 'not posible1 ';	//시간 안됨.
	exit;
}

//두번째
foreach($array_val as $key=>$value){
	$tmp = 4;
	if($a < 2){	//1일경우 9까지 허용
		$tmp = 10;
	}

	if($value < $tmp && $value > $b){
		$b = $value;
		unset($array_val[$key]);	//사용된 값 제거
	}
}

if($b == 0){
	echo 'not posible2 ';	//시간 안됨.
	exit;
}

//세번째
foreach($array_val as $key=>$value){
	if($value < 6 && $value > $c){
		$c = $value;
		unset($array_val[$key]);	//사용된 값 제거
	}
}

if($c == 0){
	echo 'not posible3 ';	//시간 안됨.
	exit;
}

//네번째
foreach($array_val as $key=>$value){
	$d = $value;
}

echo $a . $b . ':' . $c . $d;
?>
</body>
</html>