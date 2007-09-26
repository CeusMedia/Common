<?php
import( 'de.ceus-media.file.File' );
/**
 *	Builder for File in .ini-Format.
 *	@package	file
 *	@subpackage	ini
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		18.07.2005
 *	@version		0.1
 */
/**
 *	Builder for File in .ini-Format.
 *	@package	file
 *	@subpackage	ini
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		18.07.2005
 *	@version		0.1
 */
class IniCreator
{
	/**	@var	array		$_data			Data of Ini File */
	var $_data = array();
	/**	@var	string		$_currentSection	Current working Section */
	var $_currentSection = "";
	/**	@var	bool			$_useSections		Flag: use Sections within Ini File */
	var $_useSections = false;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		bool		$useSections		Flag: use Sections within Ini File
	 *	@return		void
	 */
	public function __construct( $useSections = false )
	{
		$this->_useSections = $useSections;
	}
	
	/**
	 *	Adds a Property (to current Section).
	 *	@access		public
	 *	@param		string	$key			Key of new Property
	 *	@param		string	$value			Value of new Property
	 *	@param		string	$comment		Comment of Property (optional)
	 *	@param		string	$section			Name of new Section
	 *	@return		void
	 */
	function addProperty( $key, $value, $comment )
	{
		if( $this->_useSections )
			$this->addPropertyToSection( $key, $value, $comment, $this->_currentSection );
		else
			$this->_setData( $key, $value, $comment );
	}
	
	/**
	 *	Adds a Property (to current Section).
	 *	@access		public
	 *	@param		string	$key			Key of new Property
	 *	@param		string	$value			Value of new Property
	 *	@param		string	$comment		Comment of Property (optional)
	 *	@param		string	$section			Name of new Section
	 *	@return		void
	 */
	function addPropertyToSection( $key, $value, $comment = "", $section )
	{
		$this->_setData( $key, $value, $comment, $section );
	}

	/**
	 *	Adds a Section.
	 *	@access		public
	 *	@param		string	$section			Name of new Section
	 *	@return		void
	 */
	function addSection( $section )
	{
		if ( !( isset( $this->_data[$section] ) && is_array( $this->_data[$section] ) ) )
			$this->_data[$section]	= array();
		$this->_currentSection	= $section;
	}
	
	/**
	 *	Creates and writes Settings to File.
	 *	@access		public
	 *	@param		string	$filename			File Name of new Ini File
	 *	@return		void
	 */
	function write( $filename )
	{
		$lines	= array();
		foreach ( $this->_data as $section => $section_data )
		{
			$lines[]	= "[".$section."]";
			foreach ( $section_data as $key => $key_data )
			{
				$value		= $key_data['value'];
				$comment	= $key_data['comment'];
				$lines[]		= $this->_buildLine( $key, $value, $comment);
			}
		}
		$file		= new File( $filename, 0777 );
		$file->writeArray( $lines );
	}

	/**
	 *	Returns a build Property line.
	 *	@access		private
	 *	@param		string		key			Key of  Property
	 *	@param		string		value		Value of Property
	 *	@param		string		comment		Comment of Property
	 *	@return		string
	 */
	function _buildLine( $key, $value, $comment )
	{
		$key_breaks	= 4 - floor( strlen( $key ) / 8 );
		$value_breaks	= 4 - floor( strlen( $value ) / 8 );
		if( $key_breaks < 1 )
			$key_breaks = 1;
		if( $value_breaks < 1 )
			$value_breaks = 1;
		if( $comment )
			$line = $key.str_repeat( "\t", $key_breaks )."=".$value.str_repeat( "\t", $value_breaks )."; ".$comment."\n";
		else
			$line = $key.str_repeat( "\t", $key_breaks )."=".$value;
		return $line;
	}
	
	/**
	 *	Sets Property (in Section).
	 *	@access		private
	 *	@param		string	$key			Key of new Property
	 *	@param		string	$value			Value of new Property
	 *	@param		string	$comment		Comment of Property (optional)
	 *	@param		string	$section			Name of new Section (optional)
	 *	@return		void
	 */
	function _setData( $key, $value, $comment = "", $section = false )
	{
		if ( $section )
		{
			$this->_data[$section][$key]['key'] = $key;
			$this->_data[$section][$key]['value'] = $value;
			$this->_data[$section][$key]['comment'] = $comment;
		}
		else
		{
			$this->_data[$key]['key'] = $key;
			$this->_data[$key]['value'] = $value;
			$this->_data[$key]['comment'] = $comment;
		
		}
	}
}
?>