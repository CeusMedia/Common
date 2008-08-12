<?php
import( 'de.ceus-media.file.Reader' );
/**
 *	Reader for Files with Text Block Contents, named by Section.
 *	@package		file.block
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.12.2006
 *	@version		0.1
 */
/**
 *	Reader for Files with Text Block Contents, named by Section.
 *	@package		file.block
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.12.2006
 *	@version		0.1
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
	 *	Returns Array with Names of all Blocks.
	 *	@access		public
	 *	@return		array
	 */
	public function getBlockNames()
	{
		return array_keys( $this->blocks );
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