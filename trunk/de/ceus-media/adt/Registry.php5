<?php
/**
 *	Registry Pattern Implementation to store Objects.
 *	@package		adt
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2007
 *	@version		0.6
 */
/**
 *	Registry Pattern Implementation to store Objects.
 *	@package		framework.krypton.core
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2007
 *	@version		0.6
 */
class ADT_Registry
{
	protected static $instance	= NULL;
	protected $poolKey	= "REFERENCES";

	/**
	 *	Constructor.
	 *	@access		protected
	 *	@return		void
	 */
	protected function __construct( $poolKey )
	{
		$this->poolKey = $poolKey;
		if( !( isset( $GLOBALS[$this->poolKey] ) && is_array( $GLOBALS[$this->poolKey] ) ) )
			$GLOBALS[$this->poolKey]	= array();
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
	public static function getInstance( $poolKey = "REFERENCES" )
	{
		if( self::$instance == null )
		{
			self::$instance	= new self( $poolKey );
		}
		return self::$instance;		
	}

	/**
	 *	Returns registered Object.
	 *	@access		public
	 *	@param		string		$key		Registry Key of registered Object
	 *	@return		mixed
	 */
	public function & get( $key )
	{
		if( !isset( $GLOBALS[$this->poolKey][$key] ) )
			throw new InvalidArgumentException( 'No Object registered with Key "'.$key.'"' );
		return $GLOBALS[$this->poolKey][$key];
	}
	
	/**
	 *	Returns registered Object statically.
	 *	@access		public
	 *	@param		string		$key		Registry Key of registered Object
	 *	@return		mixed
	 */
	public static function & getStatic( $key )
	{
		return self::getInstance()->get( $key );
	}
	
	/**
	 *	Indicates whether a Key is registered.
	 *	@access		public
	 *	@param		string		$key		Registry Key to be checked
	 *	@return		bool
	 */
	public function has( $key )
	{
		return array_key_exists( $key, $GLOBALS[$this->poolKey] );
	}

	/**
	 *	Registers Object.
	 *	@access		public
	 *	@param		string		$key		Registry Key of registered Object
	 *	@param		mixed		$value		Object to register
	 *	@param		bool		$overwrite	Flag: overwrite already registered Objects
	 *	@return		void
	 */
	public function set( $key, &$value, $overwrite = false )
	{
		if( isset( $GLOBALS[$this->poolKey][$key] ) && !$overwrite )
			throw new InvalidArgumentException( 'Element "'.$key.'" is already registered.' );
		$GLOBALS[$this->poolKey][$key]	=& $value;
	}

	/**
	 *	Removes a registered Object.
	 *	@access		public
	 *	@param		string		$key		Registry Key of registered Object
	 *	@return		bool
	 */
	public function remove( $key )
	{
		if( !isset( $GLOBALS[$this->poolKey][$key] ) )
			return false;
		unset( $GLOBALS[$this->poolKey][$key] );
		return true;	
	}
}  
?>