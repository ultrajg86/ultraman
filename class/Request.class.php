<?php
class Request {

	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';
	const METHOD_PUT = 'PUT';
	const METHOD_DEL = 'DELETE';
	const OUTPUT_TYPE = 'json';

	private $method;
	private $headerType = array('json', 'xml', 'html');

	public function __construct() {
		$this->method = $_SERVER['REQUEST_METHOD'];
	}

	public function getHeaderType($type) {
		return in_array($type, $this->headerType);
	}

	/* Getter, Setter */
	public function getCurrentMethod() {return $this->method;}

	public function __destruct() {}

}
?>