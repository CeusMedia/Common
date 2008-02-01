<?php
import( 'de.ceus-media.framework.krypton.core.database.TableWriter' );
import( 'de.ceus-media.framework.krypton.core.Registry' );
/**
 *	Abstract Model for Database Structures.
 *	@package		framework.krypton.core
 *	@extends		Core_Database_TableWriter
 *	@uses			Core_Registry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.02.2007
 *	@version		0.6
 */
/**
 *	Abstract Model for Database Structures.
 *	@package		framework.krypton.core
 *	@extends		Core_Database_TableWriter
 *	@uses			Core_Registry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.02.2007
 *	@version		0.6
 */
class Framework_Krypton_Core_Model extends Framework_Krypton_Core_Database_TableWriter
{
	/**	@var	string			$prefix			Prefix of Table  */
	private $prefix;
	/**	@var	Core_Registry	$registry		Registry for Objects */
	private $registry;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$table			Name of Table
	 *	@param		array		$columns		Columnss of Table
	 *	@param		string		$primaryKey		Primary Key of Table
	 *	@param		int			$focus			Current focussed primary Key
	 *	@return		void
	 */
	public function __construct( $table, $columns, $primaryKey, $focus = false )
	{
		$this->registry	= Framework_Krypton_Core_Registry::getInstance();
		$dbc			= $this->registry->get( 'dbc' );
		$config			=& $this->registry->get( 'config' );
		$this->prefix	= $config['config']['table_prefix'];
		parent::__construct( $dbc, $table, $columns, $primaryKey, $focus );
	}
	
	/**
	 *	Returns Prefix of Table.
	 *	@access		public
	 *	@return		string
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}
	
	public function getError()
	{
		$dbc	= Framework_Krypton_Core_Registry::getStatic( 'dbc' );
		return $dbc->errorInfo();
	}
	
	/**
	 *	Returns (prefixed) Name of Table.
	 *	@access		public
	 *	@param		bool		$prefixed		Flag: use also Prefix of Table
	 *	@return		string
	 */
	public function getTableName( $prefixed = true )
	{
		if( $prefixed )
			return $this->getPrefix().parent::getTableName();
		else
			return parent::getTableName();
	}
		
	/**
	 *	Indicates whether an Entry exists.
	 *	@access		public
	 *	@param		int			$id				Primary Key
	 *	@return		bool
	 */
	public function exists( $id = NULL )
	{
		if( $id === NULL )
			return (bool)count( $this->get( true ) );
		if( (int) $id > 0 )
		{
			$clone	= clone( $this );
			$clone->defocus();
			$clone->focusPrimary( $id );
			return (bool)count( $clone->get( true ) );
		}
	}
}
?>
