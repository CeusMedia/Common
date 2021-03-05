<?php
/**
 *	Function/Method Throws Data Class.
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
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@since			0.3
 *	@deprecated		use CeusMedia/PHP-Parser (https://packagist.org/packages/ceus-media/php-parser) instead
 *	@todo			to be removed in 8.7
 */
/**
 *	Function/Method Throws Data Class.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@since			0.3
 *	@deprecated		use CeusMedia/PHP-Parser (https://packagist.org/packages/ceus-media/php-parser) instead
 *	@todo			to be removed in 8.7
 */
class ADT_PHP_Throws
{
	protected $name		= NULL;
	protected $reason	= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name		Exception name
	 *	@param		string		$reason		Exception reason
	 *	@return		void
	 */
	public function __construct( $name = NULL, $reason = NULL )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.8.6' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/PHP-Parser"',
			 	'https://packagist.org/packages/ceus-media/php-parser'
			) );
		$this->name		= $name;
		$this->reason	= $reason;
	}

	/**
	 *	Returns exception name.
	 *	@access		public
	 *	@return		string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 *	Returns exception reason.
	 *	@access		public
	 *	@return		string
	 */
	public function getReason()
	{
		return $this->reason;
	}

	public function merge( ADT_PHP_Throws $throws )
	{
		if( $this->name != $throws->getName() )
			throw new Exception( 'Not mergable' );
		if( NULL !== $throws->getReason() )
			$this->setReason( $throws->getReason() );
	}

	/**
	 *	Sets exception name.
	 *	@access		public
	 *	@param		string		$name		Exception name
	 *	@return		void
	 */
	public function setName( $name )
	{
		$this->name	= $name;
	}

	/**
	 *	Sets exception reason.
	 *	@access		public
	 *	@param		string		$reason		Exception reason
	 *	@return		void
	 */
	public function setReason( $reason )
	{
		$this->reason	= $reason;
	}
}
