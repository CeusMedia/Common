<?php
/**
 *	Checks order of methods in a several PHP Files within a Folder.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			04.09.2008
 */
/**
 *	Checks order of methods in a several PHP Files within a Folder.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Folder
 *	@uses			Net_Service_Definition_NameSortCheck
 *	@uses			FS_File_RecursiveRegexFilter
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			04.09.2008
 */
class FS_Folder_ServiceNameSortCheck
{
	protected $count	= 0;
	protected $found	= 0;
	protected $files	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path			Path to Folder containing Service Definition Files
	 *	@param		array		$extensions		List of allowed Service Definition Files
	 *	@return		void
	 */
	public function __construct( $path, $extensions = array( "xml", "yaml", "json" ) )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( 'Will be removed without replacement' );
		$this->path			= $path;
		$this->extensions	= $extensions;
	}

	/**
	 *	Indicates whether all services are in correct order.
	 *	@access		public
	 *	@return		bool
	 */
	public function checkOrder()
	{
		$this->count	= 0;
		$this->found	= 0;
		$this->files	= array();
		$extensions		= implode( "|", $this->extensions );
		$pattern		= "@\.(".$extensions.")$@";
		$filter			=  new FS_File_RecursiveRegexFilter( $this->path, $pattern );
		foreach( $filter as $entry )
		{
			if( preg_match( "@^(_|\.)@", $entry->getFilename() ) )
				continue;
			$this->count++;
			$check	= new Net_Service_Definition_NameSortCheck( $entry->getPathname() );
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
