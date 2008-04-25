<?php
import( 'de.ceus-media.file.Reader' );
/**
 *	Reader for Files with Text Block Contents, named by Section.
 *	@package		file
 *	@subpackage		block
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.12.2006
 *	@version		0.1
 */
/**
 *	Reader for Files with Text Block Contents, named by Section.
 *	@package		file
 *	@subpackage		block
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.12.2006
 *	@version		0.1
 */
class BlockFileReader
{
	var $pattern_section;
	var $_blocks			= array();

	/**
	 *	Constructor, reads Block File.
	 *	@access		public
	 *	@param		string		$filename		File Name of Block File
	 *	@return		void
	 */
	public function __construct( $filename )
	{
		$this->pattern_section	= "@^\[([a-z][^\]]*)\]$@i";
		$this->filename	= $filename;
		$this->_read();	
		
	}
	
	/**
	 *	Returns Array with Names of all Blocks.
	 *	@access		public
	 *	@return		array
	 */
	function getBlockNames()
	{
		return array_keys( $this->_blocks );
	}
	
	/**
	 *	Returns Block Content.
	 *	@access		public
	 *	@param		string		$section		Name of Block
	 *	@return		array
	 */
	function getBlock( $section )
	{
		if( $this->hasBlock( $section ) )
			return $this->_blocks[$section];
	}
	
	/**
	 *	Indicates whether a Block is existing by its Name.
	 *	@access		public
	 *	@param		string		$section		Name of Block
	 *	@return		bool
	 */
	function hasBlock( $section )
	{
		$names	= array_keys( $this->_blocks );
		$result	= array_search( $section, $names );
		$return	= is_int( $result );
		return $return;
	}
	
	/**
	 *	Returns Array of all Blocks.
	 *	@access		public
	 *	@param		string		$section		Name of Block
	 *	@return		bool
	 */
	function getBlocks()
	{
		return $this->_blocks;
	}
	
	/**
	 *	Reads Block File.
	 *	@access		private
	 *	@return		void
	 */
	function _read()
	{
		$open	= false;
		$file	= new File_Reader( $this->filename );
		$lines	= $file->readArray();
		foreach( $lines as $line )
		{
			$line	= trim( $line );
			if( $line )
			{
				if( preg_match( $this->pattern_section, $line ) )
				{
					$section 	= preg_replace( $this->pattern_section, "\\1", $line );
					if( !isset( $this->_blocks[$section] ) )
						$this->_blocks[$section]	= array();
					$open = true;
				}
				else if( $open )
				{
					$this->_blocks[$section][]	= $line;
				}
			}
		}
		foreach( $this->_blocks as $section => $block )
			$this->_blocks[$section]	= implode( "\n", $block );
	}
}
?>