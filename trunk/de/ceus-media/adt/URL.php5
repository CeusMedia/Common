<?php
/**
 *	@package		adt
 *	@see			http://www.w3.org/Addressing/URL/url-spec.html
 */
class ADT_URL
{
	public $scheme;
	public $address;
	
	public function __construct( $scheme, $address = NULL )
	{
		$scheme	= preg_replace( "/^url:/i", "", $scheme );
		if( $address === NULL && preg_match( "/^\S+:\S+$/", $scheme ) )
		{
			$parts		= explode( ":", $scheme );
			$scheme		= array_shift( $parts );
			$address	= preg_replace( "@^/*@", "", implode( ":", $parts ) );
		}
		$this->setScheme( $scheme );
		$this->setAddress( $address );
	}

	public function getScheme()
	{
		return $this->scheme;
	}
	
	public function getAddress()
	{
		return $this->address;
	}
	
	public function getUrl( $withPrefix = FALSE )
	{
		$url	= (string) $this;
		if( $withPrefix )
			$url	= "url:".$url;
		return $url;
	}
	
	public function setScheme( $scheme )
	{
		if( !preg_match( '/^[a-z0-9][a-z0-9-]{1,31}$/i', $scheme ) )
			throw new InvalidArgumentException( 'Scheme "'.$scheme.'" is invalid.' );
		$this->scheme	= $scheme;
	}
	
	public function setAddress( $address )
	{
		$alpha		= 'a-z0-9';
		$others		= '()+,-.:=@;$_!*\\';
		$reserved	= '%\/?#';
		$trans		= '(['.$alpha.$others.$reserved.'])';
		$hex		= '(%[0-9a-f]{2})';
		if( !preg_match( '/^('.$trans.'|'.$hex.')+$/i', $address ) )
			throw new InvalidArgumentException( 'Address "'.$address.'" is invalid.' );
		$this->address	= $address;
	}
	
	public function __toString()
	{
		return $this->scheme.":".$this->address;
	}
}
?>