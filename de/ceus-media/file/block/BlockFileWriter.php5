<?php
import( 'de.ceus-media.file.File' );
/**
 *	Writer for Files with Text Block Contents, named by Section.
 *	@package		file
 *	@subpackage		block
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.12.2006
 *	@version		0.1
 */
/**
 *	Writer for Files with Text Block Contents, named by Section.
 *	@package		file
 *	@subpackage		block
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.12.2006
 *	@version		0.1
 */
class BlockFileWriter
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
		$this->pattern_section	= "[{#name#}]";
		$this->filename	= $filename;
	}
	
	/**
	 *	Writes Blocks to Block File.
	 *	@access		public
	 *	@param		array		$blocks			Associative Array with Block Names and Contents
	 *	@return		bool
	 */
	function writeBlocks( $blocks )
	{
		foreach( $blocks as $name => $content )
		{
			$list[]	= str_replace( "{#name#}", $name, $this->pattern_section );
			$list[]	= $content;
			$list[]	= "";
		}
		$file	= new File( $this->filename );
		return $file->writeArray( $list );
	}
}
?>