<?php
/**
 *	Writer for Files with Text Block Contents, named by Section.
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
 *	@package		File.Block
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			19.12.2006
 *	@version		$Id$
 */
/**
 *	Writer for Files with Text Block Contents, named by Section.
 *	@category		cmClasses
 *	@package		File.Block
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			19.12.2006
 *	@version		$Id$
 */
class File_Block_Writer
{
	protected $fileName;
	protected $patternSection;

	/**
	 *	Constructor, reads Block File.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Block File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->patternSection	= "[{#name#}]";
		$this->fileName			= $fileName;
	}
	
	/**
	 *	Writes Blocks to Block File.
	 *	@access		public
	 *	@param		array		$blocks			Associative Array with Block Names and Contents
	 *	@return		int
	 */
	public function writeBlocks( $blocks )
	{
		foreach( $blocks as $name => $content )
		{
			$list[]	= str_replace( "{#name#}", $name, $this->patternSection );
			$list[]	= $content;
			$list[]	= "";
		}
		$file	= new File_Writer( $this->fileName );
		return $file->writeArray( $list );
	}
}
?>