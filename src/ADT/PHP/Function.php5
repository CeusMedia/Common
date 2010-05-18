<?php
/**
 *	File Function Data Class.
 *
 *	Copyright (c) 2008-2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		ADT.PHP
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id$
 *	@since			0.3
 */
/**
 *	File Function Data Class.
 *	@category		cmClasses
 *	@package		ADT.PHP
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id$
 *	@since			0.3
 */
class ADT_PHP_Function
{
	protected $parent		= NULL;

	protected $name			= NULL;

	protected $description	= NULL;
	protected $since		= NULL;
	protected $version		= NULL;
	protected $licenses		= array();
	protected $copyright	= NULL;
	
	protected $authors		= array();
	protected $links		= array();
	protected $sees			= array();
	protected $todos		= array();
	protected $deprecations	= array();
	protected $throws		= array();

	protected $param		= array();
	protected $return		= NULL;
	
	protected $sourceCode	= NULL;
	protected $line			= 0;

	public function __construct( $name )
	{
		$this->setName( $name );
	}

	/**
	 *	Returns list of author data objects.
	 *	@access		public
	 *	@return		array			List of author data objects
	 */
	public function getAuthors()
	{
		return $this->authors;
	}

	/**
	 *	Returns copyright notes.
	 *	@access		public
	 *	@return		mixed			Copyright notes
	 */
	public function getCopyright()
	{
		return $this->description;
	}

	/**
	 *	Returns list of deprecation strings.
	 *	@access		public
	 *	@return		array			List of deprecation strings
	 */
	public function getDeprecations()
	{
		return $this->deprecations;
	}

	/**
	 *	Returns function description.
	 *	@access		public
	 *	@return		void			Function description
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 *	Returns list of licenses.
	 *	@access		public
	 *	@return		array			List of licenses
	 */
	public function getLicenses()
	{
		return $this->licenses;
	}

	/**
	 *	Returns line in code.
	 *	@access		public
	 *	@return		int				Line number in code
	 */
	public function getLine()
	{
		return $this->line;
	}

	/**
	 *	Returns list of links.
	 *	@access		public
	 *	@return		array			List of links
	 */
	public function getLinks()
	{
		return $this->links;
	}

	/**
	 *	Returns function name.
	 *	@access		public
	 *	@return		void			Function name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 *	Returns list of parameter data objects.
	 *	@access		public
	 *	@return		array			List of parameter data objects
	 */
	public function getParameters()
	{
		return $this->param;
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
			throw new Exception( 'Parser Error: Function has no related file' );
		return $this->parent;
	}

	/**
	 *	Returns return type as string or data object.
	 *	@access		public
	 *	@return		mixed			Return type as string or data object
	 */
	public function getReturn()
	{
		return $this->return;
	}

	/**
	 *	Returns list of see-also-references.
	 *	@access		public
	 *	@return		array			List of see-also-references
	 */
	public function getSees()
	{
		return $this->sees;
	}

	/**
	 *	Returns first version function occured.
	 *	@access		public
	 *	@return		mixed			First version function occured
	 */
	public function getSince()
	{
		return $this->since;
	}

	/**
	 *	Returns method source code.
	 *	@access		public
	 *	@return		string			Method source code (multiline string)
	 */
	public function getSourceCode()
	{
		return $this->sourceCode;
	}

	/**
	 *	Returns list of thrown exceptions.
	 *	@access		public
	 *	@return		array			List of thrown exceptions
	 */
	public function getThrows()
	{
		return $this->throws;
	}

	/**
	 *	Returns list of todo strings.
	 *	@access		public
	 *	@return		array			List of todo strings
	 */
	public function getTodos()
	{
		return $this->todos;
	}

	/**
	 *	Returns date of current version.
	 *	@access		public
	 *	@return		mixed			Date of current version
	 */
	public function getVersion()
	{
		return $this->version;
	}

	public function merge( ADT_PHP_Function $function )
	{
		if( $this->name != $function->getName() )
			throw new Exception( 'Not mergable' );
		if( $function->getDescription() )
			$this->setDescription( $function->getDescription() );
		if( $function->getSince() )
			$this->setSince( $function->getSince() );
		if( $function->getVersion() )
			$this->setVersion( $function->getVersion() );
		if( $function->getCopyright() )
			$this->setCopyright( $function->getCopyright() );
		if( $function->getReturn() )
			$this->setReturn( $function->getReturn() );

		foreach( $function->getAuthors() as $author )
			$this->setAuthor( $author );
		foreach( $function->getLinks() as $link )
			$this->setLink( $link );
		foreach( $function->getSees() as $see )
			$this->setSee( $see );
		foreach( $function->getTodos() as $todo )
			$this->setTodo( $todo );
		foreach( $function->getDeprecations() as $deprecation )
			$this->setDeprecation( $deprecation );
		foreach( $function->getThrows() as $throws )
			$this->setThrows( $throws );
		foreach( $function->getLicenses() as $license )
			$this->setLicense( $license );

		//	@todo		parameters are missing
	}

	/**
	 *	Sets am author.
	 *	@access		public
	 *	@param		ADT_PHP_Author	$author		Author data object
	 */
	public function setAuthor( ADT_PHP_Author $author )
	{
		$this->authors[]	= $author;
	}

	/**
	 *	Sets copyright notes.
	 *	@access		public
	 *	@param		param			$string		Copyright notes
	 *	@return		void
	 */
	public function setCopyright( $string )
	{
		$this->copyright	= $string;
	}

	/**
	 *	Sets function deprecation.
	 *	@access		public
	 *	@param		string			$string		Function deprecation
	 *	@return		void
	 */
	public function setDeprecation( $string )
	{
		$this->deprecations[]	= $string;
	}

	/**
	 *	Sets function description.
	 *	@access		public
	 *	@param		string			$string		Function description
	 *	@return		void
	 */
	public function setDescription( $string )
	{
		$this->description	= $string;
	}

	/**
	 *	Sets function license.
	 *	@access		public
	 *	@param		string			$string		Function license
	 *	@return		void
	 */
	public function setLicense( $string )
	{
		$this->licenses[]	= $string;
	}

	/**
	 *	Sets line in code.
	 *	@access		public
	 *	@param		int				Line number in code
	 *	@return		void
	 */
	public function setLine( $number )
	{
		$this->line	= $number;
	}

	/**
	 *	Sets function link.
	 *	@access		public
	 *	@param		string			$string		Function link
	 *	@return		void
	 */
	public function setLink( $string )
	{
		$this->links[]	= $string;
	}

	/**
	 *	Sets function name.
	 *	@access		public
	 *	@param		string		$string			Function name
	 *	@return		void
	 */
	public function setName( $string )
	{
		$this->name	= $string;
	}

	/**
	 *	Sets function link.
	 *	@access		public
	 *	@param		ADT_PHP_Parameter	$parameter	Parameter data object
	 *	@return		void
	 */
	public function setParameter( ADT_PHP_Parameter $parameter )
	{
		$this->param[$parameter->getName()]	= $parameter;
	}

	/**
	 *	Sets functions parent file.
	 *	@access		public
	 *	@param		ADT_PHP_File		$file		Function's parent file data object
	 *	@return		void
	 */
	public function setParent( ADT_PHP_File $file )
	{
		$this->parent	= $file;
	}

	/**
	 *	Sets functions return data object.
	 *	@access		public
	 *	@param		ADT_PHP_Return	$return		Function's return data object
	 *	@return		void
	 */
	public function setReturn( ADT_PHP_Return $return )
	{
		$this->return	= $return;
	}

	/**
	 *	Sets function see-also-link.
	 *	@access		public
	 *	@param		string			$string		Function see-also-link
	 *	@return		void
	 */
	public function setSee( $string )
	{
		$this->sees[]	= $string;
	}

	/**
	 *	Sets first version function occured.
	 *	@access		public
	 *	@param		string			$string		First version function occured
	 *	@return		void
	 */
	public function setSince( $string )
	{
		$this->since	= $string;
	}

	/**
	 *	Sets method source code.
	 *	@access		public
	 *	@param		string			Method source code (multiline string)
	 *	@return		void
	 */
	public function setSourceCode( $string )
	{
		$this->sourceCode	= $string;
	}

	public function setThrows( ADT_PHP_Throws $throws )
	{
		$this->throws[]	= $throws;
	}

	/**
	 *	Sets function todo.
	 *	@access		public
	 *	@param		string			$string		Function todo string
	 *	@return		void
	 */
	public function setTodo( $string )
	{
		$this->todos[]	= $string;
	}

	/**
	 *	Sets date of current version.
	 *	@access		public
	 *	@param		string			$string		Date of current version
	 *	@return		void
	 */
	public function setVersion( $string )
	{
		$this->version	= $string;
	}
}
?>