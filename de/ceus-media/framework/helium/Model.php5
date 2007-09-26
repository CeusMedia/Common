<?php
import( 'de.ceus-media.database.TableWriter' );
import( 'de.ceus-media.Reference' );
/**
 *	Generic Model for Database Structures.
 *	@package		framework
 *	@subpackage		helium
 *	@extends		TableWriter
 *	@uses			Reference
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.11.2005
 *	@version		0.1
 */
/*
 *	Generic Model for Database Structures.
 *	@package		framework
 *	@subpackage		helium
 *	@extends		TableWriter
 *	@uses			Reference
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.11.2005
 *	@version		0.1
 */
class Model extends TableWriter
{
	/**	@var	string		$prefix			Prefix of Table  */
	var $prefix;
	/**	@var	Reference	$ref			Reference to Objects */
	var $ref;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$table			Name of Table
	 *	@param		array	$fields			Fields of Table
	 *	@param		string	$primaryKey		Primary Key of Table
	 *	@param		int		$focus			Current focussed primary Key
	 *	@return		void
	 */
	public function __construct( $tableName, $fields, $primaryKey, $focus = false )
	{
		$this->ref		= new Reference;
		$dbc	=& $this->ref->get( 'dbc' );
		$config	=& $this->ref->get( 'config' );
		$this->prefix	= $config['config']['table_prefix'];
		parent::__construct( $dbc, $tableName, $fields, $primaryKey, $focus );
	}
	
	/**
	 *	Returns Prefix of Table.
	 *	@access		public
	 *	@return		string
	 */
	function getPrefix()
	{
		return $this->prefix;
	}
	
	/**
	 *	Returns (prefixed) Name of Table.
	 *	@access		public
	 *	@param		bool		$prefixed			Flag: an Prefix of Table
	 *	@return		string
	 */
	function getTableName( $prefixed = true )
	{
		if( $prefixed )
			return $this->getPrefix().$this->tableName;
		else
			return $this->tableName;
	}
	
	function add( $data, $prefix = "add_", $stripTags = false, $debug = 1  )
	{
		if( $prefix )
			array_walk( $data, array( &$this, "removeRequestPrefix" ), $prefix );
		$this->addData( $data, $stripTags, $debug );	
	}
	
	function modify( $data, $prefix = "edit_", $stripTags = false, $debug = 1 )
	{
		if( $prefix )
			array_walk( $data, array( &$this, "removeRequestPrefix" ), $prefix );
		$this->addData( $data, $stripTags, $debug );	
	}
	
	/**
	 *	Callback for Prefix Removal.
	 *	@access		private
	 *	@param		string		$string		String to be cleared of Prefix
	 *	@param		string		$prefix		Prefix to be removed, must not include '°'
	 *	@return		string
	 */
	private function removeRequestPrefix( $string, $prefix )
	{
		return preg_replace( "°^".$prefix."°", "", $string );
	}
}
?>