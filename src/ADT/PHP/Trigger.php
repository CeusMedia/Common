<?php
/**
 *	Function/Method Return Data Class.
 *
 *	Copyright (c) 2008-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2017-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@deprecated		use CeusMedia/PHP-Parser (https://packagist.org/packages/ceus-media/php-parser) instead
 *	@todo			to be removed in 8.7
 */
/**
 *	Function/Method Return Data Class.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2017-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@deprecated		use CeusMedia/PHP-Parser (https://packagist.org/packages/ceus-media/php-parser) instead
 *	@todo			to be removed in 8.7
 */
class ADT_PHP_Trigger
{
	protected $condition	= NULL;
	protected $key			= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$key			Trigger key
	 *	@param		string		$condition		Trigger condition
	 *	@return		void
	 */
	public function __construct( string $key, string $condition = NULL )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.8.6' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/PHP-Parser"',
			 	'https://packagist.org/packages/ceus-media/php-parser'
			) );
		$this->setKey( $key );
		$this->setCondition( $condition );
	}

	/**
	 *	Returns conditions of trigger.
	 *	@access		public
	 *	@return		string		Trigger condition
	 */
	public function getCondition()
	{
		return $this->condition;
	}

	/**
	 *	Returns key of trigger.
	 *	@access		public
	 *	@return		string|NULL		Trigger key
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 *	Sets conditions of trigger.
	 *	@access		public
	 *	@param		string		$condition		Trigger condition
	 *	@return		void
	 */
	public function setCondition( $condition )
	{
		$this->condition	= $condition;
	}

	/**
	 *	Sets key of trigger.
	 *	@access		public
	 *	@param		string		$key			Trigger key
	 *	@return		void
	 */
	public function setKey( $key )
	{
		$this->key	= $key;
	}
}
