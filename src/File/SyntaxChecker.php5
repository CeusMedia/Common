<?php
/**
 *	Checks Syntax of PHP Classes and Scripts.
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
 *	@package		File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.05.2008
 *	@version		$Id$
 */
/**
 *	Checks Syntax of PHP Classes and Scripts.
 *	@category		cmClasses
 *	@package		File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.05.2008
 *	@version		$Id$
 */
class File_SyntaxChecker
{
	/**
	 *	Returns whether a PHP Class or Script has valid Syntax.
	 *	@access		public
	 *	@param		string		$fileName		File Name of PHP File to check
	 *	@return		bool
	 */
	public function checkFile( $fileName )
	{
		$output = shell_exec('php -l "'.$fileName.'"');
		if( preg_match( "@^No syntax errors detected@", $output	) )
			return TRUE;
		$this->error	= $output;
		return FALSE;
	}
	
	/**
	 *	Returns Error of last File Syntax Check.
	 *	@access		public
	 *	@return		string
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 *	Returns Error of last File Syntax Check in a shorter Format without File Name and Parser Prefix.
	 *	@access		public
	 *	@return		string
	 */
	public function getShortError()
	{
		$error	= array_shift( explode( "\n", trim( $this->error ) ) );
		$error	= preg_replace( "@^Parse error: (.*) in (.*) on (.*)$@i", "\\1 on \\3", $error );
		return $error;
	}
}
?>