<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

require_once('class/Request.class.php');
require_once('class/Response.class.php');
require_once('class/Route.class.php');
require_once('class/Rest.class.php');

$rest = new Rest();

$rest->get('/', function(){
	$d = dir(__DIR__ . '/class/');
	echo "Handle: " . $d->handle . "\n";
	echo "Path: " . $d->path . "\n";
	while (false !== ($entry = $d->read())) {
		echo $entry."\n";
	}
	$d->close();});


$rest->get('/test', 'testfn');	//http://localhost/test.xml, test.json. test.html
/*
$rest->get('/get(/:id/user)', 'testfn');
$rest->get('/get/:id/:idx/user', 'testfn');
*/
$rest->get('/get/:id/:user', 'TestCls::fnf');
$rest->post('/get/:id/user', 'TestCls::postf');

$rest->run();

function testfn($id=false, $idx=false) {
	$data = array(
		array('id'=>$id,
		'idx'=>$idx,
		'list'=>array(
			'test1' => 'teswt1',
			'test2' => 'teswt1',
			'test3' => 'teswt1',
			'test4' => 'teswt1',
			'test5' => 'teswt1',
			)
		),

		array('id'=>'aaa',
		'idx'=>'bbb',),

		array('id'=>'vvv',
		'idx'=>'ccc',),
	);
	return $data;
}

class TestCls {
	function fnf($id, $idx=false) {
		$return = array(
			'idx'=>$id,
			'value'=>$idx,
		);
		return $return;
	}
	function postf() {
		//echo '<p>===' . __METHOD__ . '===</p>';
		//return 'aaaaaaaaaaaaaaa';
	}
}
?>
