<?php
/**
 *	Reader for Files with Text Block Contents, named by Section.
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
 *	Reader for Files with Text Block Contents, named by Section.
 *	@category		cmClasses
 *	@package		File.Block
 *	@uses			File_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			19.12.2006
 *	@version		$Id$
 */
class File_Block_Reader
{
	protected $blocks			= array();
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
		$this->patternSection	= "@^\[([a-z][^\]]*)\]$@i";
		$this->fileName	= $fileName;
		$this->readBlocks();	
		
	}

	/**
	 *	Returns Block Content.
	 *	@access		public
	 *	@param		string		$section		Name of Block
	 *	@return		array
	 */
	public function getBlock( $section )
	{
		if( $this->hasBlock( $section ) )
			return $this->blocks[$section];
	}

	/**
	 *	Returns Array with Names of all Blocks.
	 *	@access		public
	 *	@return		array
	 */
	public function getBlockNames()
	{
		return array_keys( $this->blocks );
	}

	/**
	 *	Returns Array of all Blocks.
	 *	@access		public
	 *	@param		string		$section		Name of Block
	 *	@return		bool
	 */
	public function getBlocks()
	{
		return $this->blocks;
	}

	/**
	 *	Indicates whether a Block is existing by its Name.
	 *	@access		public
	 *	@param		string		$section		Name of Block
	 *	@return		bool
	 */
	public function hasBlock( $section )
	{
		$names	= array_keys( $this->blocks );
		$result	= array_search( $section, $names );
		$return	= is_int( $result );
		return $return;
	}

	/**
	 *	Reads Block File.
	 *	@access		protected
	 *	@return		void
	 */
	protected function readBlocks()
	{
		$open	= false;
		$file	= new File_Reader( $this->fileName );
		$lines	= $file->readArray();
		foreach( $lines as $line )
		{
			$line	= trim( $line );
			if( $line )
			{
				if( preg_match( $this->patternSection, $line ) )
				{
					$section 	= preg_replace( $this->patternSection, "\\1", $line );
					if( !isset( $this->blocks[$section] ) )
						$this->blocks[$section]	= array();
					$open = true;
				}
				else if( $open )
				{
					$this->blocks[$section][]	= $line;
				}
			}
		}
		foreach( $this->blocks as $section => $block )
			$this->blocks[$section]	= implode( "\n", $block );
	}
}
?>