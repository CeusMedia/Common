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
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@since			0.3
 *	@deprecated		use CeusMedia/PHP-Parser (https://packagist.org/packages/ceus-media/php-parser) instead
 *	@todo			to be removed in 8.7
 */
/**
 *	Function/Method Return Data Class.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@since			0.3
 *	@deprecated		use CeusMedia/PHP-Parser (https://packagist.org/packages/ceus-media/php-parser) instead
 *	@todo			to be removed in 8.7
 */
class ADT_PHP_Return
{
	protected $description	= NULL;
	protected $parent		= NULL;
	protected $type			= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$type			Return type
	 *	@param		string		$description	Return description
	 *	@return		void
	 */
	public function __construct( $type = NULL, $description = NULL )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.8.6' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/PHP-Parser"',
			 	'https://packagist.org/packages/ceus-media/php-parser'
			) );
		$this->type			= $type;
		$this->description	= $description;
	}

	/**
	 *	Returns description of return value.
	 *	@access		public
	 *	@return		void		Return description
	 */
	public function getDescription()
	{
		return $this->description;
	}

	public function getParent()
	{
		if( !is_object( $this->parent ) )
			throw new RuntimeException( 'Return has no related function. Parser Error' );
		return $this->parent;
	}

	/**
	 *	Returns type of return value.
	 *	@access		public
	 *	@return		void		Return type
	 */
	public function getType()
	{
		return $this->type;
	}

	public function merge( ADT_PHP_Return $return )
	{
		if( $return->getDescription() )
			$this->setDescription( $return->getDescription() );
		if( $return->getType() )
			$this->setType( $return->getType() );
		if( $return->getParent() )
			$this->setParent( $return->getParent() );
	}

	/**
	 *	Sets description of return value.
	 *	@access		public
	 *	@param		string		$description	Return description
	 *	@return		void
	 */
	public function setDescription( $description )
	{
		$this->description	= $description;
	}

	public function setParent( ADT_PHP_Function $function )
	{
		$this->parent	= $function;
	}

	/**
	 *	Sets type of return value.
	 *	@access		public
	 *	@param		string		$type			Return type
	 *	@return		void
	 */
	public function setType( $type )
	{
		$this->type	= $type;
	}
}
