<?php
/*
 * Route 클래스는 URL패턴을 저장하는 객체로써, URL에 1:1 매칭되는 클래스
 */

class Route {

	private $pattern;
	private $callable;
	private $method;
	private $paramNames = array();
	private $params = array();
	private $paramNamesPath = array();

	public function __construct($method, $pattern, $callable) {
		/*
		$this->setMethod($method);
		$this->setPattern($pattern);
		$this->setCallable($callable);
		*/
		$this->method = $method;
		$this->pattern = $pattern;
		$this->callable = $callable;
	}

	/*
	 * @tutorial			URL패턴과 접속한 URL의 정규식 검사를 통해 현재 URL에서 값을 추출 및 패턴검사
	 * @parameter	array	현재 접속한 URL
	 * @return		boolean	패턴검사로 URL패턴과 맞는지 체크하여 맞으면 true, 틀리면 false
	 */
	public function matches($resourceUri) {
		$patternAsRegex = preg_replace_callback(
			'#:([\w]+)\+?#',
			array($this, 'matchesCallback'),
			str_replace(')', ')?', (string) $this->pattern)
		);

		$regex = '#^' . $patternAsRegex . '$#';

		//정규식 검사를 통해 현재 URL과 URL패턴이 맞는지 검사
		if(!preg_match($regex, $resourceUri, $paramValues)){
			return false;
		}

		//URL패턴에서 변수를 전역변수 배열에 저장
		foreach($this->paramNames as $name){
			if(isset($this->paramNamesPath[$name])){
				$this->params[$name] = explode('/', urldecode($paramValues[$name]));
			}else{
				$this->params[$name] = urldecode($paramValues[$name]);
			}
		}
		return true;
	}

	/*
	 * @tutorial			URL패턴중에서 변수로 사용(:key)되는 값을 현재URL에서 추출하기 위한 변형작업 함수
	 *							ex : /get/:id/user => /get/(?P<id>[^/]+)/user
	 * @parameter	array	URL패턴중에서 정규식을 이용하여 추출된 배열( array(0=>':id', 1=>'id') )
	 * @return		string	변경된 정규식패턴
	 */
	public function matchesCallback($m) {
		$this->paramNames[] = $m[1];
		if(substr($m[0], -1) === '+'){
			$this->paramNamesPath[$m[1]] = 1;
			return '(?P<' . $m[1] . '>.+)';
		}
		return '(?P<' . $m[1] . '>[^/]+)';
	}

	/*
	 * @tutorial			저장된 URL의 REQUEST_METHOD 체크
	 * @parameter	string	현재URL의 REQUEST_METHOD
	 * @return		boolean	맞으면 true, 틀리면 false
	 */
	public function isMatchedMethod($currentMethod) {
		return strcmp($this->method, $currentMethod) === 0 ? true : false;
	}

	/*
	 * @tutorial				URL패턴이 같은시에 연결된 함수 실행
	 * @parameter	void
	 * @return		function	연결된 함수 실행
	 */
	public function call() {
		$callable = $this->callable;
		$matches = array();
		if(is_string($callable) && preg_match('!^([^\:]+)\::([a-zA-Z_\x7f-\xff][a-zA-z0-9_\x7f-\x7ff]*)$!', $callable, $matches)){
			$class = $matches[1];
			$method = $matches[2];
			$obj = new $class;
			$callable = array($obj, $method);
		}
		return call_user_func_array($callable, $this->params);
	}

	/* Getter, Setter */
	public function getParams() {return $this->params;}
	public function getMethod() {return $this->method;}
	public function setMethod($method) {$this->method = $method;}
	public function getPattern() {return $this->pattern;}
	public function setPattern($pattern) {$this->pattern = $pattern;}
	public function getCallable() {return $this->callable;}
	public function setCallable($callable) {$this->callable = $callable;}

	public function __destruct() {}

}

?>