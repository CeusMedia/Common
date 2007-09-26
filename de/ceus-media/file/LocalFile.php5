<?php
import ("de.ceus-media.file.File");
/**
 *
 *	@package	file
 *	@extends	File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 *	@todo		Code Documentation
 */
/**
 *	@package	file
 *	@extends	File
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class LocalFile extends File
{
	var $_doc_root;
	var $_http_host;
	var $_protocol;

	public function __construct( $filename )
	{
		parent::__construct( $filename );
		$this->_init();
	}

	function _init()
	{
		$this->_doc_root	= strtolower(str_replace ("\\", "/", getEnv ('DOCUMENT_ROOT')));
		$this->_http_host	= strtolower(getEnv ('HTTP_HOST'));
		$this->_protocol	= getEnv ('SERVER_PROTOCOL');
		$this->_protocol	= explode ("/", $this->_protocol);
		$this->_protocol	= strtolower($this->_protocol[0]);
		$this->_protocol	= $this->_protocol.((getEnv ('HTTPS')=='on')?"s":"");
		$this->_protocol	= $this->_protocol."://";
	}

	function uri2url ($uri)
	{
		$uri = strtolower(str_replace ("\\", "/", $uri));
		$url_head = $this->_protocol.$this->_http_host;
		$uri_head = $this->_doc_root;
		if (substr_count ($uri, $uri_head)) $url = str_replace ($uri_head, $url_head, $uri);
		else $url = $url_head.$uri;
		return $url;
	}

	function url2uri ($url)
	{
		$url = strtolower(str_replace ("\\", "/", $url));
		$url_head = $this->_protocol.$this->_http_host;
		$uri_head = $this->_doc_root;
		if (substr_count ($url, $url_head)) $uri = str_replace ($url_head, $uri_head, $url);
		else $uri = $url_head.$url;
		return $uri;
	}
}
?>