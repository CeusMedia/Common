<?php
/**
 *	Checks order of methods in a several PHP Files within a Folder.
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
 *	@package		folder
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.09.2008
 *	@version		$Id$
 */
import( 'de.ceus-media.file.php.MethodSortCheck' );
import( 'de.ceus-media.file.RecursiveRegexFilter' );
/**
 *	Checks order of methods in a several PHP Files within a Folder.
 *	@category		cmClasses
 *	@package		folder
 *	@uses			File_PHP_MethodSortCheck
 *	@uses			File_RecursiveRegexFilter
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.09.2008
 *	@version		$Id$
 */
class Folder_MethodSortCheck
{
	protected $count	= 0;
	protected $found	= 0;
	protected $files	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path			Path to Folder
	 *	@param		array		$extensions		List of allowed Extensions
	 *	@return		void
	 */
	public function __construct( $path, $extensions = array( "php5", "php" ) )
	{
		$this->path			= $path;
		$this->extensions	= $extensions;
	}

	/**
	 *	Indicates whether all Methods in all Files are ordered correctly.
	 *	@access		public
	 *	@return		bool
	 */
	public function checkOrder()
	{
		$this->count	= 0;
		$this->found	= 0;
		$this->files	= array();
		$extensions		= implode( "|", $this->extensions );
		$pattern		= "@^[A-Z].*\.(".$extensions.")$@";
		$filter			=  new File_RecursiveRegexFilter( $this->path, $pattern );
		foreach( $filter as $entry )
		{
			if( preg_match( "@^(_|\.)@", $entry->getFilename() ) )
				continue;
			$this->count++;
			$check	= new File_PHP_MethodSortCheck( $entry->getPathname() );
			if( $check->compare() )
				continue;
			$this->found++;
			$list1	= $check->getOriginalList();
			$list2	= $check->getSortedList();
			do{
				$line1 = array_shift( $list1 );
				$line2 = array_shift( $list2 );
				if( $line1 != $line2 )
					break;
			}
			while( count( $list1 ) && count( $list2 ) );
			$fileName	= substr( $entry->getPathname(), strlen( $this->path ) + 1 );
			$this->files[$entry->getPathname()]	= array(
				'fileName'	=> $fileName,
				'pathName'	=> $entry->getPathname(),
				'original'	=> $line1,
				'sorted'	=> $line2,
			);
		}
		return !$this->found;
	}

	/**
	 *	Returns Number of scanned Files.
	 *	@access		public
	 *	@return		int
	 */
	public function getCount()
	{
		return $this->count;
	}

	/**
	 *	Returns an Array of Files and found Order Deviations within.
	 *	@access		public
	 *	@return		array
	 */
	public function getDeviations()
	{
		return $this->files;
	}
		
	/**
	 *	Returns Number of found Files with Order Deviations.
	 *	@access		public
	 *	@return		int
	 */
	public function getFound()
	{
		return $this->found;
	}

	/**
	 *	Returns Percentage Value of Ratio between Number of found and scanned Files.
	 *	@access		public
	 *	@param		int			$accuracy		Number of Digits after Dot
	 *	@return		float
	 */
	public function getPercentage( $accuracy = 0 )
	{
		if( !$this->count )
			return 0;
		return round( $this->found / $this->count * 100, $accuracy );
	}
	
	/**
	 *	Returns Ratio between Number found and scanned Files.
	 *	@access		public
	 *	@return		float
	 */
	public function getRatio()
	{
		if( !$this->count )
			return 0;
		return $this->found / $this->count;
	}
}
?>