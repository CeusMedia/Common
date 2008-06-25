<?php
import( 'de.ceus-media.database.TableWriter' );
import( 'de.ceus-media.adt.Reference' );
/**
 *	Generic Model for Database Structures.
 *	@package		framework.argon
 *	@extends		Database_TableWriter
 *	@uses			ADT_Reference
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.11.2005
 *	@version		0.6
 */
/*
 *	Generic Model for Database Structures.
 *	@package		framework.argon
 *	@extends		Database_TableWriter
 *	@uses			ADT_Reference
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.11.2005
 *	@version		0.6
 */
class Framework_Argon_Model extends Database_TableWriter
{
	/**	@var	string			$prefix		Prefix of Table  */
	protected $prefix;
	/**	@var	ADT_Reference	$ref		Reference to Objects */
	protected $ref;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$table			Name of Table
	 *	@param		array		$fields			Fields of Table
	 *	@param		string		$primary_key		Primary Key of Table
	 *	@param		int			$focus			Current focussed primary Key
	 *	@return		void
	 */
	public function __construct( $table, $fields, $primary_key, $focus = false )
	{
		$this->ref		= new ADT_Reference;
		$dbc	=& $this->ref->get( 'dbc' );
		$config	=& $this->ref->get( 'config' );
		$this->prefix	= $config['config']['tableprefix'];
		$this->TableWriter( $dbc, $table, $fields, $primary_key, $focus );
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
			return $this->getPrefix().$this->_table_name;
		else
			return $this->_table_name;
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
	public function add( $data, $prefix = "add_", $strip_tags = false, $debug = 1  )
	{
		if( $prefix )
			array_walk( $data, array( &$this, "__removeRequestPrefix" ), $prefix );
		$this->addData( $data, $strip_tags, $debug );	
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
	public function modify( $data, $prefix = "edit_", $strip_tags = false, $debug = 1 )
	{
		if( $prefix )
			array_walk( $data, array( &$this, "_removeRequestPrefix" ), $prefix );
		$this->addData( $data, $strip_tags, $debug );	
	}
	
	/**
	 *	Indicates whether an Entry is existing.
	 *	@access		public
	 *	@param		int			$id			Primary Id of Entry
	 *	@return 	bool
	 */
	public function exists( $id = 0 )
	{
		if( $id )
		{
			$object	= eval( "return new ".get_class( $this ).";" );
			$object->focusPrimary( $id );
			return (bool)count( $object->getData( true ) );
		}
		return (bool)count( $this->getData( true ) );
	}
	
	/**
	 *	Callback for Prefix Removal.
	 *	@access		protected
	 *	@param		string		$string		String to be cleared of Prefix
	 *	@param		string		$prefix		Prefix to be removed, must not include '°'
	 *	@return		string
	 */
	protected function __removeRequestPrefix( $string, $prefix )
	{
		return preg_replace( "°^".$prefix."°", "", $string );
	}
}
?>