<?php

class Response {

	private $headerType = array(
		'json'	=>	'Content-Type:application/json; charset=utf-8',
		'xml'	=>	'Content-Type:application/xml; charset=utf-8',
		'html'	=>	'Content-Type:text/html; charset=utf-8',
	);

	public function __construct() {
		//echo '<p>' . __METHOD__ . '</p>';
	}

	/*
	 * @tutorial			데이터 출력
	 * @parameter	array	출력할 데이터
	 *				string	출력할 데이터 타입
	 * @return		object	각 타입에 맞게 데이터 출력
	 */
	public function output($data, $type = 'json') {
		header($this->headerType[$type]);	//헤더 선언
		switch($type){
			case 'json':
				$return = $this->outputJson($data);
				break;
			case 'xml':
				$return = $this->outputXml($data);
				break;
			case 'html':
				$return = $this->outputHtml($data);
				break;
			default:
				$return = $data;
		}
		return $return;
	}

	/*
	 * @tutorial			JSON형식으로 데이터 출력
	 * @parameter	array	출력할 데이터
	 * @return		object	JSON데이터
	 */
	private function outputJson($data) {
		if(!is_array($data)){ return false; }
		if(function_exists('json_encode')){
			$result = json_encode($data);
			if($result){
				return $result;
			}
		}
	}

	/*
	 * @tutorial			XML형식으로 데이터 출력인데 기본 틀
	 * @parameter	array	출력할 데이터
	 * @return		object	XML데이터
	 */
	private function outputXml($data) {
		if(!is_array($data)){ return false; }
		$xml = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<resource>';
		$xml .= $this->arrayXml($data);
		$xml .= '</resource>';
		return $xml;
	}

	/*
	 * @tutorial			XML형식으로 데이터 출력
	 * @parameter	array	출력할 데이터
	 * @return		string	XML로 변경된 데이터
	 */
	private function arrayXml($data) {
		$xml = '';
		foreach($data as $key=>$value){
			if(is_string($key)){ $xml .= '<' . $key . '>'; }

			if(is_array($value)){
				$xml .= $this->arrayXml($value);	//값이 배열일 경우 재귀함수
			}else{
				$xml .= $value;
			}

			if(is_string($key)){ $xml .= '</' . $key . '>'; }
		}
		return $xml;
	}

	/*
	 * @tutorial			HTML형식으로 데이터 출력 기본 틀
	 * @parameter	array	출력할 데이터
	 * @return		object	HTML템플릿
	 */
	private function outputHtml($data) {
		if(!is_array($data)){ return false; }
		$html = '<!DOCTYPE html>';
		$html .= '<html>';
		$html .= '<head>';
		$html .= '<title>Result Page</title>';
		$html .= '</head>';
		$html .= '<body>';
		$html .= $data;
		$html .= '</body>';
		$html .= '</html>';
		return $html;
	}

	public function __destruct() {
		//echo '<p>' . __METHOD__ . '</p>';
	}

}

?>