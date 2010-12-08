<?php
/**
 *	Collects and Manages Request Data.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		Net.HTTP.Request
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			27.03.2006
 *	@version		$Id$
 */
/**
 *	Collects and Manages Request Data.
 *	@category		cmClasses
 *	@package		Net.HTTP.Request
 *	@extends		ADT_List_Dictionary
 *	@uses			Net_HTTP_Header_Section
 *	@uses			Net_HTTP_Header_Field
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			27.03.2006
 *	@version		$Id$
 */
class Net_HTTP_Request_Receiver extends ADT_List_Dictionary
{
	/** @var		Net_HTTP_Header_Section	$headers		Object of collected HTTP Headers */
	protected $headers						= NULL;
	/**	@var		string					$ip				IP of Request */
	protected $ip;
	/** @var		string					$method			HTTP request method */
	protected $method						= NULL;
	/**	@var		array					$sources		Array of Sources of Request Data */
	protected $sources;

	/**
	 *	Constructor, reads and stores Data from Sources to internal Dictionary.
	 *	@access		public
	 *	@param		bool		$useSession		Flag: include Session Values
	 *	@param		bool		$useCookie		Flag: include Cookie Values
	 *	@return		void
	 */
	public function __construct( $useSession = FALSE, $useCookie = FALSE )
	{
		$this->sources	= array(
			"get"	=> &$_GET,
			"post"	=> &$_POST,
			"files"	=> &$_FILES,
		);
		if( $useSession )
			$this->sources['session']	=& $_SESSION;
		if( $useCookie )
			$this->sources['cookie']	=& $_COOKIE;

		$this->ip		= getEnv( 'REMOTE_ADDR' );													//  store IP of requesting client
		$this->method	= strtoupper( getEnv( 'REQUEST_METHOD' ) );									//  store HTTP method
		foreach( $this->sources as $key => $values )
			$this->pairs	= array_merge( $this->pairs, $values );

		/*  --  RETRIEVE HTTP HEADERS  --  */
		$this->headers	= new Net_HTTP_Header_Section;
		foreach( $_SERVER as $key => $value )
		{
			if( strpos( $key, "HTTP_" ) !== 0 )
				continue;
			$key	= preg_replace( '/^HTTP_/', '', $key );											//  strip HTTP prefix
			$key	= preg_replace( '/_/', '-', $key );												//  replace underscore by dash
			$this->headers->addHeader( new Net_HTTP_Header_Field( $key, $value ) );					//
		}
	}
	
	/**
	 *	Reads and returns Data from Sources.
	 *	@access		public
	 *	@param		string		$source		Source key (not case sensitive) (get,post,files[,session,cookie])
	 *	@param		bool		$strict		Flag: throw exception if not set, otherwise return NULL
	 *	@throws		InvalidArgumentException if key is not set in source and strict is on
	 *	@return		array		Pairs in source (or empty array if not set on strict is off)
	 */
	public function getAllFromSource( $source, $strict = FALSE )
	{
		$source	= strtolower( $source );
		if( isset( $this->sources[$source] ) )
			return new ADT_List_Dictionary( $this->sources[$source] );
		if( !$strict )
			return array();
		throw new InvalidArgumentException( 'Invalid source "'.$source.'"' );
	}

	/**
	 *	Returns value or null by its key in a specified source.
	 *	@access		public
	 *	@param		string		$key		...
	 *	@param		string		$source		Source key (not case sensitive) (get,post,files[,session,cookie])
	 *	@param		bool		$strict		Flag: throw exception if not set, otherwise return NULL
	 *	@throws		InvalidArgumentException if key is not set in source and strict is on
	 *	@return		mixed		Value of key in source or NULL if not set
	 */
	public function getFromSource( $key, $source, $strict = FALSE )
	{
		$data	= $this->getAllFromSource( $source );
		if( isset( $data[$key] ) )
			return $data[$key];
		if( !$strict )
			return NULL;
		throw new InvalidArgumentException( 'Invalid key "'.$key.'" in source "'.$source.'"' );
	}

	/**
	 *	Returns List collected HTTP Headers.
	 *	@access		public
	 *	@return		array		List of Header Objects
	 *	@since		0.6.8
	 */
	public function getHeaders()
	{
		return $this->headers->getFields();
	}

	/**
	 *	Returns List of collection HTTP Headers with a specified Header Name.
	 *	@access		public
	 *	@param		string		$name		Header Name
	 *	@return		array		List of collected HTTP Headers with given Header Name
	 *	@since		0.6.8
	 */
	public function getHeadersByName( $name )
	{
		return $this->headers->getFieldsByName( $name );
	}

	public function getMethod()
	{
		return $this->method;
	}

	/**
	 *	Indicates whether atleast one HTTP Header with given Header Name is set.
	 *	@access		public
	 *	@param		string		$name		Header Name
	 *	@return		bool
	 *	@since		0.6.8
	 */
	public function hasHeader( $name )
	{
		return $this->headers->hasField( $name );
	}

	/**
	 *	Returns received raw POST Data.
	 *	@access		public
	 *	@return		string
	 *	@since		0.6.8
	 */
	public function getRawPostData()
	{
		return file_get_contents( "php://input" );
	}

	/**
	 *	Indicates wheter a pair is existing in a request source by its key.
	 *	@access		public
	 *	@param		string		$key		...
	 *	@param		string		$source		Source key (not case sensitive) (get,post,files[,session,cookie])
	 *	@return		bool
	 */
	public function hasInSource( $key, $source )
	{
		$source	= strtolower( $source );
		return isset( $this->sources[$source][$key] );
	}

	/**
	 *	Indicates whether this Request came by AJAX.
	 *	It seems only jQery is supporting this at the moment.
	 *	@access		public
	 *	@return		bool
	 *	@since		0.6.7
	 */
	public function isAjax()
	{
		return $this->headers->hasField( 'X-Requested-With' );
		return getEnv( 'HTTP_X_REQUESTED_WITH' ) == "HTTP_X_REQUESTED_WITH";
	}
}
?>