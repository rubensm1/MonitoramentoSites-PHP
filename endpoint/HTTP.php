<?php

/*
 *  simple HttpRequest example using PHP
 *  tom slankard
 */

class HttpRequest {

  public $url = null;
  public $method = 'GET';
  public $body = null;
  public $headers = Array();
  public $allow_redirect = true;

  private $url_info = null;
  private $host_name = null; 
  private $host_ip = null;
  private $response_body = null;
  private $response_headers = Array();
  private $response_code = null;
  private $response_message = null;
  private $port = null;
  private $verbose = true;

  public function __construct($url, $method = 'GET') {

    $this->url = $url;
    $this->method = $method;

    //  parse url
    $this->url_info = parse_url($url);
    $this->host_name = $this->url_info['host'];
    $this->host_ip = gethostbyname($this->host_name);

    //  get port number given the scheme
    if(!isset($this->url_info['port'])) {
      if($this->url_info['scheme'] == "http")
        $this->port = 80;
      else if($this->url_info['scheme'] == "https")
        $this->port = 443;
    } else {
      $this->port = $this->url_info['port'];
    }

    //  add default headers
    $this->headers["Host"] = "$this->host_name";
    $this->headers["Connection"] = "close";
	$this->headers["User-Agent"] = "PHP/" . phpversion();
	$this->headers["Accept"] = "*/*";
	$this->headers["Access-Control-Allow-Origin"] = "*";

  }

  private function constructRequest() {
    $path = "/";
    if(isset($this->url_info['path']))
      $path = $this->url_info['path'];

    $req = "$this->method $path HTTP/1.1\r\n";
    foreach($this->headers as $header => $value) {
      $req .= "$header: $value\r\n";
    }
  
    return "$req\r\n";
  }

  ///  reads a line from a file
  function readLine($fp)
  {
    $line = "";
    while (!feof($fp)) {
      $line .= fgets($fp, 2048);
      if (substr($line, -1) == "\n") {
        return rtrim($line, "\r\n");
      }
    }
    return $line;
  }

  public function send() {

    $fp = @fsockopen(($this->port == 443 ? "ssl://" : "") . $this->host_ip, $this->port, $errno, $errstr);
    
	if (gettype($fp) != "resource") {
		//echo "ERRO: $errno - $errstr<br />\n";
		$this->connectionError($errno);
		return TRUE;
	}
	
    //  construct request
    $request = $this->constructRequest();

    //  write request to socket
    fwrite($fp, $request);
    //  read the status line
    $line = $this->readline($fp);
    $status = explode(" ", $line);

    //  make sure the HTTP version is valid
    if(!isset($status[0]) || !preg_match("/^HTTP\/\d+\.?\d*/", $status[0]))
      die("Couldn't get HTTP version from response.");

    //  get the response code
    if(!isset($status[1]))
      die("Couldn't get HTTP response code from response.");
    else $this->response_code = $status[1];
    
    //  get the reason, e.g. "not found"
    if(!isset($status[2]))
      die("Couldn't get HTTP response reason from response.");
    else {
      $this->response_message .= $status[2];
      for ($i = 3; $i < count($status); $i++)
        $this->response_message .= " " . $status[$i];
    }
	
    //  read the headers
    do {
      $line = $this->readLine($fp);
      if($line != "") { 
        $header = preg_split("/: /", $line);

        $this->response_headers[$header[0]] = ltrim($header[1]);
      }
    } while(!feof($fp) && $line != "");

    //  read the body
    $this->response_body = "";
    do {
      $line = $this->readLine($fp); {
      if($line)
        $this->response_body .= "$line\n";
      }
    } while(!feof($fp));

    //  close the connection
    fclose($fp);

    return TRUE;
  }
  
  public function connectionError($code) {
	  
    switch ($code) {
      case 10060:
        $this->response_message = "Connection timed out";
		break;
	  case 10061:
	    $this->response_message = "Connection refused";
		break;
      default:
	    echo "ERRO: $errno - $errstr<br />\n";
    }
  }
  
  public function getStatus() {
    return $this->response_code;
  }

  public function getHeaders() {
    return $this->response_headers;
  }

  public function getResponseBody() {
    return $this->response_body;
  }
  
  public function getResponseCode() {
    return $this->response_code;
  }
	
  public function getResponseMessage() {
    return $this->response_message;
  }


}

