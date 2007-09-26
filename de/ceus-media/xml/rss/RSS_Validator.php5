<?php
import( "de.ceus-media.protocol.cURL" );
/**
 *	Requests RSS Feed against validator.
 *	@package		xml
 *	@subpackage		dom
 * 	@uses			cURL
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@version		0.4
 */
/**
 *	Requests RSS Feed against validator.
 *	@package		xml
 *	@subpackage		dom
 * 	@uses			cURL
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@version		0.4
 */
class RSS_Validator
{
	/**	@var	string		_error		Last error message */
	var $_error;
	/**	@var	string		_result		Last result message */
	var $_result;
	/**	@var	bool			_status		Last validation status */
	var $_status	=  false;
	/**	@var	string		_validator_url	URL of Validator */
	var $_validator_url;
	
	/**
	 *	Constructor.
	 *	@access		public
	 */
	public function __construct()
	{
//		$this->_validator_url	= "http://rss.scripting.com/?url=[url]";
		$this->_validator_url	= "http://feedvalidator.org/check?url=[url]";
		$this->_validator_url	= "http://validator.w3.org/feed/check.cgi?url=[url]";
	}

	/**
	 *	Requests for validation of RSS Feed URL.
	 *	@access		public
	 *	@param		string		url		RSS Feed URL
	 *	@return		bool
	 */
	function validateRSS( $url )
	{
		$validator_url = str_replace( "[url]", urlencode( $url ), $this->_validator_url );
		$c = new cURL ($validator_url);
		$c->setopt(CURLOPT_FOLLOWLOCATION, true) ;
		$c->setopt(CURLOPT_HEADER, false) ;
		$c->setopt(CURLOPT_SSL_VERIFYPEER, 0);
		$this->_result = $c->exec();
		if ($this->_error = $c->hasError())
			trigger_error( $this->_error, E_USER_WARNING );
		$c->close() ;
		if( substr_count( $this->_result, "This is a valid RSS feed." ) )
			return $this->_status = true;
		return $this->_status = false;
	}
	
	/**
	 *	Returns last error.
	 *	@access		public
	 *	@return		string
	 */
	function getError()
	{
		return $this->_error;
	}
	
	/**
	 *	Returns result validation request.
	 *	@access		public
	 *	@return		string
	 */
	function getResult()
	{
		$result = "";
		if ( !$this->_status )
		{
			$result = $this->_result;
			$result = substr( $result, strpos( $result, "<ul>" ) );
			$result = substr( $result, 0, strpos( $result, "</ul>" )+5 );
		}
		return $result;
	}
	
	/**
	 *	Returns XML from validation request.
	 *	@access		public
	 *	@return		string
	 */
	function getXML()
	{
		$result = "";
		if ( !$this->_status )
		{
			$result = $this->_result;
			$result = substr( $result, strpos( $result, "<ol" ) );
			$result = substr( $result, 0, strpos( $result, "</ol>" )+5 );
		}
		return $result;
	}
	
	/**
	 *	Sets URL for validation request.
	 *	@access		public
	 *	@param		string		url		URL for validation request
	 *	@return		void
	 */
	function setValidatorUrl( $url )
	{
		$this->_validator_url = $url;
	}
}
?>