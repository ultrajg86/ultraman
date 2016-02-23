<?php

class Rest {

	private $route;	//Class : Route Class
	private $request;	//Class : Request Class
	private $response;	//Class : Response Class

	private $resourceUri;	//string : 현재주소
	private $contentType;	//string : 반환형식(xml, json) json기본
	private $map = array();	//array : 주소저장소

	public function __construct() {

		$this->request = new Request();
		$this->response = new Response();

		$scriptName = $_SERVER['SCRIPT_NAME'];
		$requestUri = $_SERVER['REQUEST_URI'];
		$queryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

		if(strpos($requestUri, $scriptName) !== false){
			$physicalPath = $scriptName;
		}else{
			$physicalPath = str_replace('\\', '', dirname($scriptName));
		}

		$this->resourceUri = substr_replace($requestUri, '', 0, strlen($physicalPath));
		$this->resourceUri = str_replace('?'. $queryString, '', $this->resourceUri);
		$this->resourceUri = '/' . ltrim($this->resourceUri, '/');

		$this->resourceType();
	}

	/*
	 * @tutorial		접속한 URL에서 마지막 값의 확장자(json OR xml)추출 후 삭제
	 * @param	void
	 * @return	void
	 */
	private function resourceType() {
		$resource = explode('/', $this->resourceUri);
		$type =  $resource[count($resource) - 1];
		$type = explode('.', $type);
		if(count($type) < 2){
			$this->contentType = Request::OUTPUT_TYPE;
		}else{
			$this->contentType = $type[1];
			$this->resourceUri = substr($this->resourceUri, 0, (-strlen($this->contentType)) - 1);
		}

		if(!$this->request->getHeaderType($this->contentType)){
			$this->contentType = 'json';
		}
	}

	/*
	 * @tutorial		선언된 REQUEST_METHOD를 Route클래스를 통해 객체로 선언하고 저장
	 * @param	string	REQUEST_METHOD => GET, POST 현재는 두가지만
	 *			array	선언된 Pattern과 실행할 함수
	 * @return	void
	 */
	private function mapRoute($method, $args) {
		$pattern = array_shift($args);
		$callable = array_pop($args);
		$route = new Route($method, $pattern, $callable);
		$this->setMap($route);
	}

	/*
	 * @tutorial		GET 선언
	 * @param	void
	 * @return	void
	 */
	public function get() {
		$args = func_get_args();
		$this->mapRoute(Request::METHOD_GET, $args);
	}

	/*
	 * @tutorial		POST 선언
	 * @param	void
	 * @return	void
	 */
	public function post() {
		$args = func_get_args();
		$this->mapRoute(Request::METHOD_POST, $args);
	}

	/*
	 * @tutorial		선언된 REQUEST_METHOD를 저장
	 * @param	object	Route클래스
	 * @return	void
	 */
	private function setMap($route) {
		$this->map[] = $route;
	}

	/*
	 * @tutorial		RESTful 서비스 실행
	 * @param	void
	 * @return	void
	 */
	public function run() {
		foreach($this->map as $route){
			if($route->isMatchedMethod($this->request->getCurrentMethod())){
				if($route->matches($this->resourceUri)){
					$result = $route->call();
					if($result !== false && !is_null($result)) {
						echo $this->response->output($result, $this->contentType);
					}
				}//if : $route->matches()
			}//if : $route->isMatchedMethod()
		}//foreach : $this->map
	}//end : function

	public function __destruct() {
		//echo '<p>' . __METHOD__ . '</p>';
	}

}

?>