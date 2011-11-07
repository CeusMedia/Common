<?php
/**
 *	File permission data object and handler.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		File
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	File permission data object and handler.
 *	@category		cmClasses
 *	@package		File
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class File_Permissions
{
	/**	@var	string		$pathName			Path name of current file */
	protected $pathName		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$pathName		Path name of file to get permissions for
	 *	@return		void
	 *	@throws		InvalidArgumentException if file is not existing
	 */
	public function __construct( $pathName )
	{
		if( !file_exists( $pathName ) )
			throw new InvalidArgumentException( 'File "'.$pathName.'" is not existing' );
		$this->pathName	= $pathName;
	}

	/**
	 *	Returns permissions as integer value.
	 *	@access		public
	 *	@return		integer		Integer value of permissions of current file
	 *	@throws		RuntimeException if no valid file is set
	 */
	public function getAsInteger()
	{
		$permissions	= @fileperms( $this->pathName );
		if( FALSE === $permissions )
			throw new InvalidArgumentException( 'Could not get permissions of file "'.$this->pathName.'"' );
		return octdec( substr( sprintf( '%o', $permissions ), -4 ) );
	}

	/**
	 *	Returns permissions as octal string value.
	 *	@access		public
	 *	@return		integer		Octal string value of permissions of current file
	 *	@throws		RuntimeException if no valid file is set
	 */
	public function getAsOctal()
	{
		$permissions	= @fileperms( $this->pathName );
		if( FALSE === $permissions )
			throw new InvalidArgumentException( 'Could not get permissions of file "'.$this->pathName.'"' );
		return substr( sprintf( '%o', $permissions ), -4 );
	}

	/**
	 *	Returns permissions as string value.
	 *	@access		public
	 *	@return		string		String value of permissions of current file
	 *	@throws		RuntimeException if no valid file is set
	 */
	public function getAsString()
	{
		$permissions	= @fileperms( $this->pathName );
		if( FALSE === $permissions )
			throw new InvalidArgumentException( 'Could not get permissions of file "'.$this->pathName.'"' );
		return self::getStringFromOctal( $permissions );
	}

	public static function getIntegerFromFile( $pathName )
	{
		$object	= new File_Permissions( $pathName );
		return $object->getAsInteger();
	}

	public static function getIntegerFromOctal( $permissions )
	{
		if( is_string( $permissions ) )
			$permissions	= octdec( $permissions );
		if( !is_integer( $permissions ) )
			throw new InvalidArgumentException( 'Must be octal, string or integer' );
		return $permissions;
	}

	public static function getIntegerFromString( $permissions )
	{
		if( !is_string( $permissions ) )
			throw new InvalidArgumentException( 'Must be string' );
		return octdec( self::getOctalFromString( $permissions ) );
	}

	public static function getOctalFromFile( $pathName )
	{
		$object	= new File_Permissions( $pathName );
		return $object->getAsOctal();
	}

	public static function getOctalFromInteger( $permissions )
	{
		if( !is_integer( $permissions ) )
			throw new InvalidArgumentException( 'Must be integer' );
		return sprintf( '0%o', $permissions );
	}

	public static function getOctalFromString( $permissions )
	{
		if( !is_string( $permissions ) )
			throw new InvalidArgumentException( 'Must be string' );
		if( strlen( $permissions ) != 9 )
			throw new InvalidArgumentException( 'String must contain 9 characters' );

		$octal	= 0;
		if( $permissions[0] == 'r' ) $octal += 0400;
		if( $permissions[1] == 'w' ) $octal += 0200;
		if( $permissions[2] == 'x' ) $octal += 0100;
		else if( $permissions[2] == 's' ) $octal += 04100;
		else if( $permissions[2] == 'S' ) $octal += 04000;

		if( $permissions[3] == 'r' ) $octal += 040;
		if( $permissions[4] == 'w' ) $octal += 020;
		if( $permissions[5] == 'x' ) $octal += 010;
		else if( $permissions[5] == 's' ) $octal += 02010;
		else if( $permissions[5] == 'S' ) $octal += 02000;

		if( $permissions[6] == 'r' ) $octal += 04;
		if( $permissions[7] == 'w' ) $octal += 02;
		if( $permissions[8] == 'x' ) $octal += 01;
		else if( $permissions[8] == 't' ) $octal += 01001;
		else if( $permissions[8] == 'T' ) $octal += 01000;
		return sprintf( '0%o', $octal );
	}

	public static function getStringFromFile( $pathName )
	{
		$object	= new File_Permissions( $pathName );
		return $object->getAsString();
	}

	public static function getStringFromInteger( $permissions )
	{
		if( !is_integer( $permissions ) )
			throw new InvalidArgumentException( 'Must be integer' );
		return self::getStringFromOctal( $permissions );
	}

	public static function getStringFromOctal( $permissions )
	{
		if( is_string( $permissions ) )
			$permissions	= octdec( $permissions );
		if( !is_integer( $permissions ) )
			throw new InvalidArgumentException( 'Must be octal, string or integer' );

		$info	= "";
		// Owner
		$info .= ( ( $permissions & 0x0100 ) ? 'r' : '-' );
		$info .= ( ( $permissions & 0x0080 ) ? 'w' : '-' );
		$info .= ( ( $permissions & 0x0040 ) ?
					( ( $permissions & 0x0800 ) ? 's' : 'x' ) :
					( ( $permissions & 0x0800 ) ? 'S' : '-' ) );

		// Group
		$info .= ( ( $permissions & 0x0020) ? 'r' : '-');
		$info .= ( ( $permissions & 0x0010) ? 'w' : '-');
		$info .= ( ( $permissions & 0x0008) ?
					( ( $permissions & 0x0400) ? 's' : 'x' ) :
					( ( $permissions & 0x0400) ? 'S' : '-' ) );

		// World
		$info .= ( ( $permissions & 0x0004 ) ? 'r' : '-' );
		$info .= ( ( $permissions & 0x0002 ) ? 'w' : '-' );
		$info .= ( ( $permissions & 0x0001 ) ?
					( ( $permissions & 0x0200 ) ? 't' : 'x' ) :
					( ( $permissions & 0x0200 ) ? 'T' : '-' ) );

		return $info;
	}

	public function setByOctal( $permissions )
	{
		$permissions	= self::getIntegerFromOctal( $permissions );
		$result	= @chmod( $this->pathName, $permissions );
		if( FALSE === $result )
			throw new RuntimeException( 'Cannot change permissions for "'.$this->pathName.'"' );
		return $result;
	}

	public function setByString( $permissions )
	{
		$permissions	= self::getIntegerFromString( $permissions );
		$result	= @chmod( $this->pathName, $permissions );
		if( FALSE === $result )
			throw new RuntimeException( 'Cannot change permissions for "'.$this->pathName.'"' );
		return $result;
	}
}
?>