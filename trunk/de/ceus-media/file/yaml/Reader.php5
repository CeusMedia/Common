<?php
import( 'de.ceus-media.file.Reader' );
import( 'net.sourceforge.spyc.Spyc' );
/**
 *	YAML Reader based on Spyc.
 *	@package		file.yaml
 *	@uses			File_Reader
 *	@uses			Spyc
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.6
 */
/**
 *	YAML Reader based on Spyc.
 *	@package		file.yaml
 *	@uses			File_Reader
 *	@uses			Spyc
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.6
 */
class File_YAML_Reader
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
	 *	Reads YAML File.
	 *	@access		public
	 *	@return		array
	 */
	public function read()
	{
		return self::load( $this->fileName );
	}

	/**
	 *	Loads YAML File statically.
	 *	@access		public
	 *	@param		string		$fileName		File Name of YAML File.
	 *	@return		array
	 */
	public static function load( $fileName )
	{
		$yaml	= File_Reader::load( $fileName );
		$array	= Spyc::YAMLLoad( $yaml );
		return $array;
	}
}
?>