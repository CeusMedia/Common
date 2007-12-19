<?php
import( 'de.ceus-media.framework.krypton.interface.core.Request' );
/**
 *	Singleton Handler for HTTP Requests.
 *	@package		framework.krypton.core.http
 *	@implements		Framework_Krypton_Core_Interface_Request
 *	@implements		ArrayAccess
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2007
 *	@version		0.6
 */
/**
 *	Singleton Handler for HTTP Requests.
 *	@package		framework.krypton.core.http
 *	@implements		Framework_Krypton_Core_Interface_Request
 *	@implements		ArrayAccess
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2007
 *	@version		0.6
 */
class Framework_Krypton_Core_HTTP_Request implements Framework_Krypton_Interface_Core_Request, ArrayAccess
{
	/**	@var	Framework_Krypton_Core_HTTP_Request	$instance		Instance of Registry */
	protected static $instance	= null;
	/** @var	array								$parameters		Associative Array of Request Parameters */
	private $values;

	/**
	 *	Constructur.
	 *	@access		protected
	 *	@return		void
	 */
	protected function __construct()
	{
		$this->values	= $_REQUEST;
	}
	
	/**
	 *	Denies to clone Registry.
	 *	@access		private
	 *	@return		void
	 */
	private function __clone() {}

	/**
	 *	Returns Instance of Registry.
	 *	@access		public
	 *	@return		Registry
	 */
	public static function getInstance()
	{
		if( self::$instance == null )
		{
			self::$instance	= new Framework_Krypton_Core_HTTP_Request();
		}
		return self::$instance;		
	}

	/**
	 *	Returns Values by its Key.
	 *	@access		public
	 *	@param		string		$key		Key of Request Value
	 *	@return		mixed
	 */
	public function get( $key )
	{
		if( $this->has( $key ) )
			return $this->values[$key];
		return null;
	}
	
	/**
	 *	Returns Array of Keys and Values.
	 *	@access		public
	 *	@return		array
	 */
	public function getAll()
	{
		return $this->values;
	}
	
	/**
	 *	Indicates whether a Key is registered.
	 *	@access		public
	 *	@param		string		$key		Key to be checked
	 *	@return		bool
	 */
	public function has( $key )
	{
		return isset( $this->values[$key] );
	}
	
	/**
	 *	Indicates whether a Key is registered.
	 *	@access		public
	 *	@param		string		$key		Key to be checked
	 *	@return		bool
	 */
	public function offsetExists( $key )
	{
		return $this->has( $key );
	}
	
	/**
	 *	Sets Value by its Key.
	 *	@access		public
	 *	@param		string		$key		Key of Request Value
	 *	@param		mixed		$value 		Value to be set for Key
	 *	@return		bool
	 */
	public function offsetSet( $key, $value )
	{
		return $this->set( $key, $value );
	}
	
	/**
	 *	Returns a Value by its Key.
	 *	@access		public
	 *	@param		string		$key		Key of Request Value
	 *	@return		mixed
	 */
	public function offsetGet( $key )
	{
		return $this->get( $key );
	}
	
	/**
	 *	Removes a Value by its Key.
	 *	@access		public
	 *	@param		string		$key		Key of Request Value
	 *	@return		bool
	 */
	public function offsetUnset( $key )
	{
		return $this->remove( $key );
	}
	
	/**
	 *	Sets Value by its Key.
	 *	@access		public
	 *	@param		string		$key		Key of Request Value
	 *	@param		mixed		$value 		Value to be set for Key
	 *	@return		bool
	 */
	public function set( $key, $value )
	{
		$this->values[$key]	= $value;
	}
	
	/**
	 *	Removes a Value by its Key.
	 *	@access		public
	 *	@param		string		$key		Key of Request Value
	 *	@return		bool
	 */
	public function remove( $key )
	{
		if( $this->has( $key ) )
			unset( $this->values[$key] );
	}
}
?>
