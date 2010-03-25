<?php
/**
 *	Checks visibility of methods in a PHP file.
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
 *	@package		file.php
 *	@uses			File_Reader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.12.2009
 *	@version		$Id$
 */
import( 'de.ceus-media.file.Reader' );
/**
 *	Checks visibility of methods in a PHP file.
 *	@category		cmClasses
 *	@package		file.php
 *	@uses			File_Reader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.12.2009
 *	@version		$Id$
 */
class File_PHP_MethodVisibilityCheck
{
	protected $fileName		= "";
	protected $methods		= array();
	protected $checked		= FALSE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URL of PHP File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new RuntimeException( "File '".$fileName."' is not existing." );
		$this->fileName	= $fileName;
		$this->checked	= FALSE;
	}

	/**
	 *	Indicates whether all methods have a defined visibility.
	 *	@access		public
	 *	@return		bool
	 */
	public function check()
	{
		$this->checked	= TRUE;
		$this->methods	= array();
		$matches		= array();
		$content		= File_Reader::load( $this->fileName );
		if( preg_match( "@class @i", $content ) )
			if( preg_match_all( "@\tfunction (& *)?([a-z][a-z0-9]+)@i", $content, $matches ) )
				foreach( $matches[2] as $match )
					$this->methods[]	= $match;
		return empty( $this->methods );
	}
	
	/**
	 *	Returns List of methods without defined visibility.
	 *	@access		public
	 *	@return		array
	 */
	public function getMethods()
	{
		if( !$this->checked )
			throw new Exception( "Not checked yet." );
		return $this->methods;
	}
}
?>