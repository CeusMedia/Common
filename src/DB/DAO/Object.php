<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2018 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_DB_DAO
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_DB_DAO
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class DB_DAO_Object
{
	protected $table		= NULL;
	protected $primaryKey	= NULL;

	/**
	 *	Constructor.
	 *	@deprecated	Please use CeusMedia/Database (https://packagist.org/packages/ceus-media/database) instead
	 *	@todo		remove in version 1.0
	 */
	public function __construct( DB_DAO_Table $table )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Database"',
			 	'https://packagist.org/packages/ceus-media/database'
			) );
		$this->table	= $table;
	}

	public function __get( $key )
	{
		throw new Exception( get_class( $this ).': Field "'.$key.'" is not available' );
	}

	public function __set( $key, $value )
	{
		if( !in_array( $key, $this->table->getFieldNames() ) )
			throw new Exception( get_class( $this ).': Field "'.$key.'" is not available' );
		$this->$key	= $value;
	}

	public function getFieldNames()
	{
		return $this->table->getFieldNames();
	}

	/**
	 *	@todo		Should this be public?
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 *	Set Data Access Table.
	 *	@access		public
	 *	@param		DataAccess_Table	$table		Database Access Table
	 *	@return		void
	 */
	public function setTable( DB_DAO_Table $table )
	{
		$this->table	= $table;
	}

	public function updateField( $key, $value )
	{
		return $this->table->updateById( $this->table->getPrimaryKey(), array( $key => $value ) );
	}

	public function updateFields( $fields )
	{
		return $this->table->updateById( $this->table->getPrimaryKey(), $fields );
	}
}
?>
