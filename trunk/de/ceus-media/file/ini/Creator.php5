<?php
import( 'de.ceus-media.file.Writer' );
/**
 *	Builder for File in .ini-Format.
 *	@package		file.ini
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.2005
 *	@version		0.6
 */
/**
 *	Builder for File in .ini-Format.
 *	@package		file.ini
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.2005
 *	@version		0.6
 */
class File_INI_Creator
{
	/**	@var	array			$data			Data of Ini File */
	protected $data = array();
	/**	@var	string			$currentSection	Current working Section */
	protected $currentSection = "";
	/**	@var	bool			$useSections	Flag: use Sections within Ini File */
	protected $useSections = false;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		bool		$useSections	Flag: use Sections within Ini File
	 *	@return		void
	 */
	public function __construct( $useSections = FALSE )
	{
		$this->useSections = $useSections;
	}
	
	/**
	 *	Adds a Property (to current Section).
	 *	@access		public
	 *	@param		string		$key			Key of new Property
	 *	@param		string		$value			Value of new Property
	 *	@param		string		$comment		Comment of Property (optional)
	 *	@param		string		$section		Name of new Section
	 *	@return		void
	 */
	public function addProperty( $key, $value, $comment = NULL )
	{
		if( $this->useSections )
			$this->addPropertyToSection( $key, $value, $this->currentSection, $comment );
		else
		{
			$this->data[$key]['key']		= $key;
			$this->data[$key]['value']		= $value;
			$this->data[$key]['comment']	= $comment;
		}
	}
	
	/**
	 *	Adds a Property (to current Section).
	 *	@access		public
	 *	@param		string		$key			Key of new Property
	 *	@param		string		$value			Value of new Property
	 *	@param		string		$comment		Comment of Property (optional)
	 *	@param		string		$section		Name of new Section
	 *	@return		void
	 */
	public function addPropertyToSection( $key, $value, $section, $comment = NULL )
	{
		$this->data[$section][$key]['key']		= $key;
		$this->data[$section][$key]['value']	= $value;
		$this->data[$section][$key]['comment']	= $comment;
	}

	/**
	 *	Adds a Section.
	 *	@access		public
	 *	@param		string		$section		Name of new Section
	 *	@return		void
	 */
	public function addSection( $section )
	{
		if ( !( isset( $this->data[$section] ) && is_array( $this->data[$section] ) ) )
			$this->data[$section]	= array();
		$this->currentSection	= $section;
	}
	
	/**
	 *	Returns a build Property line.
	 *	@access		protected
	 *	@param		string		$key			Key of  Property
	 *	@param		string		$value			Value of Property
	 *	@param		string		$comment		Comment of Property
	 *	@return		string
	 */
	protected function buildLine( $key, $value, $comment )
	{
		$key_breaks	= 4 - floor( strlen( $key ) / 8 );
		$value_breaks	= 4 - floor( strlen( $value ) / 8 );
		if( $key_breaks < 1 )
			$key_breaks = 1;
		if( $value_breaks < 1 )
			$value_breaks = 1;
		if( $comment )
			$line = $key.str_repeat( "\t", $key_breaks )."=".$value.str_repeat( "\t", $value_breaks )."; ".$comment;
		else
			$line = $key.str_repeat( "\t", $key_breaks )."=".$value;
		return $line;
	}
	
	/**
	 *	Creates and writes Settings to File.
	 *	@access		public
	 *	@param		string		$fileName		File Name of new Ini File
	 *	@return		bool
	 */
	public function write( $fileName )
	{
		$lines	= array();
		foreach ( $this->data as $section => $section_data )
		{
			$lines[]	= "[".$section."]";
			foreach ( $section_data as $key => $key_data )
			{
				$value		= $key_data['value'];
				$comment	= $key_data['comment'];
				$lines[]	= $this->buildLine( $key, $value, $comment);
			}
			$lines[]	= "";
		}
		$file		= new File_Writer( $fileName, 0777 );
		return $file->writeArray( $lines );
	}
}
?>