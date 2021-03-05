<?php
/**
 *	Class Variable Data Class.
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
 *	Class Variable Data Class.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@since			0.3
 *	@deprecated		use CeusMedia/PHP-Parser (https://packagist.org/packages/ceus-media/php-parser) instead
 *	@todo			to be removed in 8.7
 */
class ADT_PHP_Variable
{
	protected $parent			= NULL;

	protected $name				= NULL;
	protected $type				= NULL;

	protected $description		= NULL;
	protected $since			= NULL;
	protected $version			= NULL;

	protected $authors			= array();
	protected $links			= array();
	protected $sees				= array();
	protected $todos			= array();
	protected $deprecations		= array();
	protected $line				= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name			Variable name
	 *	@param		mixed		$type			Variable type string or data object
	 *	@param		string		$description	Variable description
	 *	@return		void
	 */
	public function __construct( string $name, $type = NULL, string $description = NULL )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.8.6' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/PHP-Parser"',
			 	'https://packagist.org/packages/ceus-media/php-parser'
			) );
		$this->setName( $name );
		if( !is_null( $type ) )
			$this->setType( $type );
		if( !is_null( $description ) )
			$this->setDescription( $description );
	}

	/**
	 *	Returns list of author data objects.
	 *	@access		public
	 *	@return		array			List of author data objects
	 */
	public function getAuthors(): array
	{
		return $this->authors;
	}

	/**
	 *	Returns list of deprecation strings.
	 *	@access		public
	 *	@return		array			List of deprecation strings
	 */
	public function getDeprecations(): array
	{
		return $this->deprecations;
	}

	/**
	 *	Returns variable description.
	 *	@access		public
	 *	@return		string|NULL		Variable description
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 *	Returns line in code.
	 *	@access		public
	 *	@return		int				Line number in code
	 */
	public function getLine(): int
	{
		return $this->line;
	}

	/**
	 *	Returns list of links.
	 *	@access		public
	 *	@return		array			List of links
	 */
	public function getLinks(): array
	{
		return $this->links;
	}

	/**
	 *	Returns name of parameter.
	 *	@access		public
	 *	@return		string			Variable name
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 *	Returns parent File Data Object.
	 *	@access		public
	 *	@return		ADT_PHP_File		Parent File Data Object
	 *	@throws		Exception		if not parent is set
	 */
	public function getParent()
	{
		if( !is_object( $this->parent ) )
			throw new Exception( 'Parser Error: variable has no related file' );
		return $this->parent;
	}

	/**
	 *	Returns list of see-also-references.
	 *	@access		public
	 *	@return		array		List of see-also-references
	 */
	public function getSees(): array
	{
		return $this->sees;
	}

	/**
	 *	Returns first version of variable.
	 *	@access		public
	 *	@return		string		First version of variable
	 */
	public function getSince()
	{
		return $this->type;
	}

	/**
	 *	Returns list of todos.
	 *	@access		public
	 *	@return		array		List of todos
	 */
	public function getTodos(): array
	{
		return $this->todos;
	}

	/**
	 *	Returns type of parameter.
	 *	@access		public
	 *	@return		mixed		Type string or
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 *	Returns version of variable.
	 *	@access		public
	 *	@return		string		Latest version of variable
	 */
	public function getVersion()
	{
		return $this->version;
	}

	public function merge( ADT_PHP_Variable $variable )
	{
#		remark( 'merging variable: '.$variable->getName() );
		if( $this->name != $variable->getName() )
			throw new Exception( 'Not mergable' );
		if( NULL !== $variable->getType() )
			$this->setType( $variable->getType() );
		if( NULL !== $variable->getDescription() )
			$this->setDescription( $variable->getDescription() );
		if( NULL !== $variable->getSince() )
			$this->setSince( $variable->getSince() );
		if( NULL !== $variable->getVersion() )
			$this->setVersion( $variable->getVersion() );

		foreach( $variable->getAuthors() as $author )
			$this->setAuthor( $author );
		foreach( $variable->getLinks() as $link )
			$this->setLink( $link );
		foreach( $variable->getSees() as $see )
			$this->setSee( $see );
		foreach( $variable->getTodos() as $todo )
			$this->setTodo( $todo );
		foreach( $variable->getDeprecations() as $deprecation )
			$this->setDeprecation( $deprecation );
	}

	/**
	 *	Sets am author.
	 *	@access		public
	 *	@param		ADT_PHP_Author	$author		Author data object
	 */
	public function setAuthor( ADT_PHP_Author $author )
	{
		$this->authors[$author->getName()]	= $author;
	}

	/**
	 *	Sets variable deprecation.
	 *	@access		public
	 *	@param		string			$string		Variable deprecation
	 *	@return		void
	 */
	public function setDeprecation( string $string )
	{
		$this->deprecations[]	= $string;
	}

	/**
	 *	Sets variable description.
	 *	@access		public
	 *	@param		string		$string			Variable description
	 *	@return		void
	 */
	public function setDescription( string $string )
	{
		$this->description	= $string;
	}

	/**
	 *	Sets line in code.
	 *	@access		public
	 *	@param		int			$number			Line number in code
	 *	@return		void
	 */
	public function setLine( int $number )
	{
		$this->line	= $number;
	}

	/**
	 *	Sets function link.
	 *	@access		public
	 *	@param		string		$string			Function link
	 *	@return		void
	 */
	public function setLink( string $string )
	{
		$this->links[]	= $string;
	}

	/**
	 *	Sets variable name.
	 *	@access		public
	 *	@param		string		$string			Variable name
	 *	@return		void
	 */
	public function setName( string $string )
	{
		$this->name	= $string;
	}

	/**
	 *	Sets parent File Data Object.
	 *	@access		public
	 *	@param		ADT_PHP_File		$parent		Parent File Data Object
	 *	@return		void
	 */
	public function setParent( $parent )
	{
		$this->parent	= $parent;
	}

	/**
	 *	Sets see-also-reference of variable.
	 *	@access		public
	 *	@param		string		$string			See-also-reference
	 *	@return		void
	 */
	public function setSee( string $string )
	{
		$this->sees[]	= $string;
	}

	/**
	 *	Sets first version of variable.
	 *	@access		public
	 *	@param		string		$string			First version of variable
	 *	@return		void
	 */
	public function setSince( string $string )
	{
		$this->since	= $string;
	}

	/**
	 *	Sets todo.
	 *	@access		public
	 *	@param		string		$string			Todo string
	 *	@return		void
	 */
	public function setTodo( string $string )
	{
		$this->todos[]	= $string;
	}

	/**
	 *	Sets parameter type.
	 *	@access		public
	 *	@param		mixed		$type			Type string or data object
	 *	@return		void
	 */
	public function setType( $type )
	{
		$this->type	= $type;
	}

	/**
	 *	Sets latest version of variable.
	 *	@access		public
	 *	@param		string		$string			Latest version of variable
	 *	@return		void
	 */
	public function setVersion( string $string )
	{
		$this->version	= $string;
	}
}
