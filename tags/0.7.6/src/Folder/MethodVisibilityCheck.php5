<?php
/**
 *	Checks visibility of methods in a folder containing PHP files.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.12.2009
 *	@version		$Id$
 */
/**
 *	Checks visibility of methods in a folder containing PHP files.
 *	@category		cmClasses
 *	@package		Folder
 *	@uses			File_RecursiveRegexFilter
 *	@uses			File_PHP_Check_MethodVisibility
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.12.2009
 *	@version		$Id$
 */
class Folder_MethodVisibilityCheck
{
	public $count	= 0;
	public $found	= 0;
	public $list	= array();
	
	/**
	 *	Scans a folder containing PHP files for methods without defined visibility.
	 *	@access		public
	 *	@param		string		$path			Path to Folder containing PHP Files
	 *	@param		string		$extension		Extension of PHP Files. 
	 *	@return		void
	 */
	public function scan( $path, $extension = "php5" )
	{
		$this->count	= 0;
		$this->found	= 0;
		$this->list		= array();
		$finder	= new File_RecursiveRegexFilter( $path, '@^[^_].*\.'.$extension.'$@', "@function @" );
		foreach( $finder as $entry )
		{
			$checker	= new File_PHP_Check_MethodVisibility( $entry->getPathname() );
			if( $checker->check() )
				continue;
			$this->found++;
			$this->list[$entry->getPathname()]	= $checker->getMethods();
		}
		$this->count	= $finder->getNumberFound();
	}
}
?>