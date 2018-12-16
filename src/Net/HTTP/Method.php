<?php
/**
 *	HTTP method data type.
 *
 *	Copyright (c) 2018 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.8.4.7
 */
/**
 *	HTTP method data type.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.8.4.7
 */
class Net_HTTP_Method
{
	/** @var		string					$method			HTTP request method */
	protected $method		= 'GET';

	static public $methods	= array(
		'DELETE',
		'GET',
		'HEAD',
		'OPTIONS',
		'POST',
		'PUT'
	);

	public function __construct( $method = NULL )
	{
		if( $method )
			$this->set( $method );
	}

	public function __toString(){
		return $this->get();
	}

	/**
	 *	Return request method.
	 *	@access		public
	 *	@return		string
	 */
	public function get()
	{
		return $this->method;
	}

	/**
	 *	Indicate whether a specific request method is used.
	 *	Method parameter is not case sensitive.
	 *	@access		public
	 *	@param		string		$method		Request method to check against
	 *	@return		boolean
	 */
	public function is( $method )
	{
		return $this->method === strtoupper( trim( $method ) );
	}

	/**
	 *	Indicates whether request method is GET.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isGet()
	{
		return $this->is( 'GET' );
	}

	/**
	 *	Indicates whether request method is DELETE.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isDelete()
	{
		return $this->is( 'DELETE' );
	}

	/**
	 *	Indicates whether request method is HEAD.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isHead()
	{
		return $this->is( 'HEAD' );
	}

	/**
	 *	Indicates whether request method is OPTIONS.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isOptions()
	{
		return $this->is( 'OPTIONS' );
	}

	/**
	 *	Indicates whether request method is POST.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isPost()
	{
		return $this->is( 'POST' );
	}

	/**
	 *	Indicates whether request method is PUT.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isPut()
	{
		return $this->is( 'PUT' );
	}

	/**
	 *	Set request method.
	 *	@access		public
	 *	@param		string		$method		Request method to set
	 *	@return		self
	 */
	public function set( $method )
	{
		$method		= strtoupper( $method );
		if( !in_array( $method, self::$methods ) )
			throw new BadMethodCallException( 'HTTP method "%s" is not supported' );
		$this->method	= $method;
		return $this;
	}
}
?>
