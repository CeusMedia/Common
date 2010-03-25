<?php
/**
 *	Class to find all Files with ToDos inside.
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
 *	@package		file
 *	@extends		File_TodoLister
 *	@uses			File_RecursiveRegexFilter
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.06.2008
 *	@version		$Id$
 */
import( 'de.ceus-media.file.TodoLister' );
import( 'de.ceus-media.file.RecursiveRegexFilter' );
/**
 *	Class to find all Files with ToDos inside.
 *	@category		cmClasses
 *	@package		file
 *	@extends		File_TodoLister
 *	@uses			File_RecursiveRegexFilter
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.06.2008
 *	@version		$Id$
 */
class File_RecursiveTodoLister extends File_TodoLister
{
	protected function getIndexIterator( $path, $filePattern, $contentPattern = NULL )
	{
		return new File_RecursiveRegexFilter( $path, $filePattern, $contentPattern );
	}
}
?>