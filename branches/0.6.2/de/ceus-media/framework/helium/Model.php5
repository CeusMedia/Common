<?php
import( 'de.ceus-media.database.TableWriter' );
import( 'de.ceus-media.adt.Reference' );
/**
 *	Generic Model for Database Structures.
 *	@package		framework.helium
 *	@extends		Database_TableWriter
 *	@uses			ADT_Reference
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.11.2005
 *	@version		0.6
 */
/**
 *	Generic Model for Database Structures.
 *	@package		framework.helium
 *	@extends		Database_TableWriter
 *	@uses			ADT_Reference
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.11.2005
 *	@version		0.6
 */
class Framework_Helium_Model extends Database_TableWriter
{
	/**	@var	string			$prefix			Prefix of Table  */
	protected $prefix;
	/**	@var	ADT_Reference	$ref			Reference to Objects */
	protected $ref;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$table			Name of Table
	 *	@param		array		$fields			Fields of Table
	 *	@param		string		$primaryKey		Primary Key of Table
	 *	@param		int			$focus			Current focussed primary Key
	 *	@return		void
	 */
	public function __construct( $tableName, $fields, $primaryKey, $focus = false )
	{
		$this->ref		= new ADT_Reference;
		$dbc			= $this->ref->get( 'dbc' );
		$config			= $this->ref->get( 'config' );
		$this->prefix	= $config['config']['table_prefix'];
		parent::__construct( $dbc, $tableName, $fields, $primaryKey, $focus );
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
	
	/**
	 *	Returns (prefixed) Name of Table.
	 *	@access		public
	 *	@param		bool		$prefixed			Flag: an Prefix of Table
	 *	@return		string
	 */
	public function getTableName( $prefixed = true )
	{
		if( $prefixed )
			return $this->getPrefix().$this->tableName;
		else
			return $this->tableName;
	}
	
	/**
	 *	Adds Data to Table.
	 *	@access		public
	 *	@param		array		$data		Data to add
	 *	@param		string		$prefix		Prefix of Request Data
	 *	@param		bool		$stripTags	Flag: strip HTML Tags
	 *	@param		int			$debug		Debug Mode
	 *	@return 	void
	 */
	public function add( $data, $prefix = "add_", $stripTags = false, $debug = 1  )
	{
		if( $prefix )
			array_walk( $data, array( &$this, "removeRequestPrefix" ), $prefix );
		$this->addData( $data, $stripTags, $debug );	
	}
	
	/**
	 *	Modifies Data in Table.
	 *	@access		public
	 *	@param		array		$data		Data to modify
	 *	@param		string		$prefix		Prefix of Request Data
	 *	@param		bool		$stripTags	Flag: strip HTML Tags
	 *	@param		int			$debug		Debug Mode
	 *	@return 	void
	 */
	public function modify( $data, $prefix = "edit_", $stripTags = false, $debug = 1 )
	{
		if( $prefix )
			array_walk( $data, array( &$this, "removeRequestPrefix" ), $prefix );
		$this->modifyData( $data, $stripTags, $debug );	
	}
	
	/**
	 *	Callback for Prefix Removal.
	 *	@access		private
	 *	@param		string		$string		String to be cleared of Prefix
	 *	@param		string		$prefix		Prefix to be removed, must not include '�'
	 *	@return		string
	 */
	private function removeRequestPrefix( $string, $prefix )
	{
		return preg_replace( "�^".$prefix."�", "", $string );
	}
}
?>