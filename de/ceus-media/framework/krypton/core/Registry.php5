<?php
import( 'de.ceus-media.framework.krypton.exception.IO' );
/**
 *	Registry Singleton to store Objects
 *	@package		mv2.core
 *	@uses			Framework_Krypton_Exception_IO
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2007
 *	@version		0.1
 */
/**
 *	Registry Singleton to store Objects
 *	@package		mv2.core
 *	@uses			Framework_Krypton_Exception_IO
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2007
 *	@version		0.1
 */
class Framework_Krypton_Core_Registry
{
	/**	@var	Framework_Krypton_Core_Registry	$instance		Instance of Registry */
	protected static $instance	= null;
	/**	@var	array							$values			Array of stored Objects */
	protected $values	= array();

	/**
	 *	Constructor.
	 *	@access		protected
	 *	@return		void
	 */
	protected function __construct() {}

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
			self::$instance	= new Framework_Krypton_Core_Registry();
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
		if( !isset( $this->values[$key] ) )
			throw new Framework_Krypton_Exception_IO( 'No Object registered with Key "'.$key.'"' );
		return $this->values[$key];
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
		return array_key_exists( $key, $this->values );
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
		if( isset( $this->values[$key] ) && !$overwrite )
			throw new Exception( 'Element "'.$key.'" is already registered.' );
		$this->values[$key]	=& $value;
	}
}  
?>