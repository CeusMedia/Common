<?php
/**
 *	Class to find all Files with ToDos inside.
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
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			11.06.2008
 */
/**
 *	Class to find all Files with ToDos inside.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@extends		FS_File_TodoLister
 *	@uses			FS_File_RecursiveRegexFilter
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			11.06.2008
 */
class FS_File_RecursiveTodoLister extends FS_File_TodoLister
{
	protected function getIndexIterator( $path, $filePattern, $contentPattern = NULL )
	{
		return new FS_File_RecursiveRegexFilter( $path, $filePattern, $contentPattern );
	}
}
