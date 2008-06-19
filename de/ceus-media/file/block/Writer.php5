<?php
import( 'de.ceus-media.file.Writer' );
/**
 *	Writer for Files with Text Block Contents, named by Section.
 *	@package		file.block
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.12.2006
 *	@version		0.6
 */
/**
 *	Writer for Files with Text Block Contents, named by Section.
 *	@package		file.block
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.12.2006
 *	@version		0.6
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