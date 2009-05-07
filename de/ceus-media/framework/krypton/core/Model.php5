<?php
import( 'de.ceus-media.database.pdo.TableWriter' );
import( 'de.ceus-media.framework.krypton.core.Registry' );
/**
 *	Abstract Model for Database Structures.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		framework.krypton.core
 *	@extends		Database_PDO_TableWriter
 *	@uses			Core_Registry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			19.02.2007
 *	@version		0.6
 */
/**
 *	Abstract Model for Database Structures.
 *	@package		framework.krypton.core
 *	@extends		Database_PDO_TableWriter
 *	@uses			Core_Registry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			19.02.2007
 *	@version		0.6
 */
class Framework_Krypton_Core_Model extends Database_PDO_TableWriter
{
	/**	@var	string			$prefix			Prefix of Table  */
	private $prefix;
	
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
		$dbc			= Framework_Krypton_Core_Registry::getStatic( 'dbc' );
		$config			= Framework_Krypton_Core_Registry::getStatic( 'config' );
		$this->prefix	= $config['config.table_prefix'];
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
