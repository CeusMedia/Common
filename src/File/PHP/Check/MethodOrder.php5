<?php
/**
 *	Checks order of methods within a PHP File.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@package		File.PHP.Check
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.09.2008
 *	@version		$Id$
 */
/**
 *	Checks order of methods within a PHP File.
 *	@category		cmClasses
 *	@package		File.PHP.Check
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.09.2008
 *	@version		$Id$
 */
class File_PHP_Check_MethodOrder
{
	private $fileName		= "";
	private $originalList	= array();
	private $sortedList		= array();
	private $compared		= FALSE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URL of PHP File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( "File '".$fileName."' is not existing." );
		$this->fileName	= $fileName;
	}

	/**
	 *	Indicates whether all methods are in correct order.
	 *	@access		public
	 *	@return		bool
	 */
	public function compare()
	{
		$this->compared	= TRUE;
		$content	= file_get_contents( $this->fileName );
		$content	= preg_replace( "@/\*.+\*/@sU", "", $content );
		$lines		= explode( "\n", $content );
		foreach( $lines as $line )
		{
			if( preg_match( "@^(#|//)@", trim( $line ) ) )
				continue;
			$matches	= array();
			preg_match_all( "@function\s*([a-z]\S+)\s*\(@i", $line, $matches, PREG_SET_ORDER );
			foreach( $matches as $match )
				$this->originalList[] = $match[1];
		}
		$this->sortedList	= $this->originalList;
		natCaseSort( $this->sortedList );
		return $this->sortedList === $this->originalList;
	}
	
	/**
	 *	Returns List of methods in original order.
	 *	@access		public
	 *	@return		array
	 */
	public function getOriginalList()
	{
		if( !$this->compared )
			throw new Exception( "Not compared yet." );
		return $this->originalList;
	}

	/**
	 *	Returns List of methods in correct order.
	 *	@access		public
	 *	@return		array
	 */
	public function getSortedList()
	{
		if( !$this->compared )
			throw new Exception( "Not compared yet." );
		return $this->sortedList;
	}
}
?>