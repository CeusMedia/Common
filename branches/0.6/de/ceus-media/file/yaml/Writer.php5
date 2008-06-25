<?php
import( 'de.ceus-media.file.Writer' );
import( 'net.sourceforge.spyc.Spyc' );
/**
 *	YAML Writer based on Spyc.
 *	@package		file.yaml
 *	@uses			File_Writer
 *	@uses			Spyc
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.6
 */
/**
 *	YAML Writer based on Spyc.
 *	@package		file.yaml
 *	@uses			File_Writer
 *	@uses			Spyc
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.6
 */
class File_YAML_Writer
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of YAML File.
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
	}

	/**
	 *	Writes Data into YAML File.
	 *	@access		public
	 *	@return		bool
	 */
	public function write( $data )
	{
		return self::save( $this->fileName, $data );
	}

	/**
	 *	Writes Data into YAML File statically.
	 *	@access		public
	 *	@param		string		$fileName		File Name of YAML File.
	 *	@param		array		$data		Array to write into YAML File
	 *	@return		bool
	 */
	public static function save( $fileName, $data )
	{
		$yaml	= Spyc::YAMLDump( $data );
		return File_Writer::save( $fileName, $yaml );
	}
}
?>