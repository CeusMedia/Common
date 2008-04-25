<?php
import( 'de.ceus-media.file.ini.Reader' );
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Property File Writer.
 *	@package		file.ini
 *	@extends		File_INI_Reader
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Property File Writer.
 *	@package		file.ini
 *	@extends		File_INI_Reader
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 *	@todo			Code Documentation
 */
class File_INI_Writer extends File_INI_Reader
{
	/**	@var		array		$added			Added Properties */
	protected $added			= array();
	/**	@var		array		$renamed		Renamed Properties */
	protected $renamed			= array();
	/**	@var		array		$deleted		Deleted Properties */
	protected $deleted			= array();

	/**
	 *	Returns a build Property line.
	 *	@access		private
	 *	@param		string		$key			Key of  Property
	 *	@param		string		$value			Value of Property
	 *	@param		string		$comment		Comment of Property
	 *	@return		string
	 */
	private function buildLine( $key, $value, $comment )
	{
		$keyBreaks		= 4 - floor( strlen( $key ) / 8 );
		$valueBreaks	= 4 - floor( strlen( $value ) / 8 );
		if( $keyBreaks < 1 )
			$keyBreaks = 1;
		if( $valueBreaks < 1 )
			$valueBreaks = 1;
		if( $comment )
			$line = $key.str_repeat( "\t", $keyBreaks )."=".$value.str_repeat( "\t", $valueBreaks )."; ".$comment."\n";
		else
			$line = $key.str_repeat( "\t", $keyBreaks )."=".$value;
		return $line;
	}

	/**
	 *	Activates a Property.
	 *	@access		public
	 *	@param		string		$key			Key of  Property
	 *	@param		string		$value			Section of Property
	 *	@return		bool
	 */
	public function activateProperty( $key, $section = false )
	{
		if( $this->usesSections() )
		{
			if( !$this->isActiveProperty( $key, $section ) )
			{
				unset( $this->disabled[$section][array_search( $key, $this->disabled[$section] )] );
				return $this->write();
			}
		}
		else
		{
			if( !$this->isActiveProperty( $key ) )
			{
				unset( $this->disabled[array_search( $key, $this->disabled )] );
				return $this->write();
			}
		}
	}

	/**
	 *	Adds a new Property with Comment.
	 *	@access		public
	 *	@param		string		$key			Key of new Property
	 *	@param		string		$value			Value of new Property
	 *	@param		bool		$state			Activity state of new Property
	 *	@param		string		$comment		Comment of new Property
	 *	@return		bool
	 */
	public function addProperty( $key, $value, $comment = "", $state = true, $section = false )
	{
		if( $section && !in_array( $section, $this->sections ) )
			$this->addSection( $section );
		$key =(  $state?"":$this->disableSign ).$key;
		$this->added[] = array(
			"key"		=> $key,
			"value"		=> $value,
			"comment"	=> $comment,
			"section"	=> $section,
			);
		return $this->write();
	}

	/**
	 *	Adds a new Section.
	 *	@access		public
	 *	@param		string		$sectionName	Name of new Section
	 *	@return		bool
	 */
	public function addSection( $sectionName )
	{
		$lines		= File_Reader::loadArray( $this->fileName );
		$lines[]	= "[".$sectionName."]";
		if( !in_array( $sectionName, $this->sections ) )
			$this->sections[] = $sectionName;
		$result		= File_Writer::saveArray( $this->fileName, $lines );
		$this->read();
		return $result;
	}

	/**
	 *	Deactivates a Property.
	 *	@access		public
	 *	@param		string		$key			Key of  Property
	 *	@param		string		$value			Section of Property
	 *	@return		bool
	 */
	public function deactivateProperty( $key, $section = false)
	{
		if( $this->usesSections() )
		{
			if( $this->isActiveProperty( $key, $section ) )
			{
				$this->disabled[$section][] = $key;
				return $this->write();
			}
		}
		else
		{
			if( $this->isActiveProperty( $key ) )
			{
				$this->disabled[] = $key;
				return $this->write();
			}
		}
	}

	/**
	 *	Deletes a  Property.
	 *	@access		public
	 *	@param		string		$key			Key of Property to be deleted
	 *	@return		bool
	 */
	public function deleteProperty( $key, $section = false )
	{
		if( $this->usesSections() )
			$this->deleted[$section][] = $key;
		else
			$this->deleted[] = $key;
		return $this->write();
	}

	public function importArray( $array )
	{
		$this->lines = array();
		foreach( $array as $key => $value )
		{
			$this->lines[] = $this->buildLine( $key, $value, false );
		}
	}

	/**
	 *	Renames a Property Key.
	 *	@access		public
	 *	@param		string		$key			Key of Property to rename
	 *	@param		string		$new			New Key of Property
	 *	@param		string		$section		Section of Property
	 *	@return		bool
	 */
	public function renameProperty( $key, $new, $section = false )
	{
		if( $this->usesSections() )
		{
			if( $this->hasProperty( $key, $section ) )
			{
				$this->properties [$section][$new]	= $this->properties[$section][$key];
				if( isset( $this->disabled[$section][$key] ) )
					$this->disabled [$section][$new]		= $this->disabled[$section][$key];
				if( isset( $this->comments[$section][$key] ) )
					$this->comments [$section][$new]	= $this->comments[$section][$key];
				$this->renamed[$section][$key] = $new;
				return $this->write();
			}
		}
		else
		{
			if( $this->hasProperty( $key ) )
			{
				$this->properties[$new]	= $this->properties[$key];
				if( isset( $this->disabled[$key] ) )
					$this->disabled[$new]		= $this->disabled[$key];
				if( isset( $this->comments[$key] ) )
					$this->comments[$new]	= $this->comments[$key];
				$this->renamed[$key]	= $new;
				return $this->write();
			}
		}
	}

	/**
	 *	Renames as Section.
	 *	@access		public
	 *	@param		string		$section		Key of Section to rename
	 *	@param		string		$new			New Key of Section
	 *	@return		bool
	 */
	public function renameSection( $section, $new )
	{
		if( !$this->usesSections() )
			return false;
		$content	= File_Reader::load( $this->fileName );
		$content	= preg_replace( "/(.*)(\[".$section."\])(.*)/si", "$1[".$new."]$3", $content );
		$result		= File_Writer::save( $content );
		$this->added	= array();
		$this->deleted	= array();
		$this->renamed	= array();
		$this->read();
		return $result;
	}

	/**
	 *	Sets the Comment of a Property.
	 *	@access		public
	 *	@param		string		$key			Key of Property
	 *	@param		string		$comment		Comment of Property to set
	 *	@param		string		$section		Key of Section
	 *	@return		bool
	 */
	public function setComment( $key, $comment, $section = false )
	{
		if( $this->usesSections() )
			$this->comments[$section][$key] = $comment;
		else
			$this->comments[$key] = $comment;
		return $this->write();
	}

	/**
	 *	Sets the Comment of a Property.
	 *	@access		public
	 *	@param		string		$key			Key of Property
	 *	@param		string		$value			Value of Property
	 *	@param		string		$section		Key of Section
	 *	@return		bool
	 */
	public function setProperty( $key, $value, $section = false )
	{
		if( $this->usesSections() )
		{
			try
			{
				if( $this->hasProperty( $key, $section ) )
					$this->properties[$section][$key] = $value;
				else $this->addProperty( $key, $value, false, true, $section );
			}
			catch( InvalidArgumentException $e )
			{
				$this->addProperty( $key, $value, false, true, $section );
			}
		}
		else
		{
			if( $this->hasProperty( $key ) )
				$this->properties[$key] = $value;
			else $this->addProperty( $key, $value, false, true );
		}
		return $this->write();
	}

	/**
	 *	Removes a Section
	 *	@access		public
	 *	@param		string		$section		Key of Section to remove
	 *	@return		bool
	 */
	public function removeSection( $section )
	{
		if( !$this->usesSections() )
			return false;
		$index	= array_search( $section, $this->sections );
		if( $index !== false )
			unset( $this->sections[$index] );
		return $this->write();
	}

	/**
	 *	Writes manipulated Content to File.
	 *	@access		public
	 *	@return		bool
	 */
	public function write()
	{
		$file	= new File_Writer( $this->fileName, 777 );
		if( !$file->isWritable() )
			throw new Exception( 'File "'.$this->fileName.'" is not writable.' );
		$newLines = array();
		$currentSection	= "";
		foreach( $this->lines as $line )
		{
			if( $this->usesSections() && eregi( $this->sectionPattern, $line ) )
			{
				$lastSection = $currentSection;
				$newAdded = array();
				if( $lastSection )
				{
					foreach( $this->added as $property )
					{
						if( $property['section'] == $lastSection )
						{
							if( !trim( $newLines[count($newLines)-1] ) )
								array_pop( $newLines );
							$newLines[]	= $this->buildLine( $property['key'], $property['value'], $property['comment'] );
							$newLines[]	= "";
						}
						else $newAdded[] = $property;
					}
					$this->added = $newAdded;
				}
				$currentSection =  substr(trim($line), 1, -1);
				if( !in_array( $currentSection, $this->sections ) )
					unset( $line );
			}
			else if( eregi( $this->propertyPattern, $line ) )
			{
				$pos = strpos( $line, "=" );
				$key = trim(substr( $line, 0, $pos ) );
				$pureKey = eregi_replace( $this->disablePattern, "", $key);
				$parts = explode( "//", trim(substr($line, $pos+1 ) ) );
				$value = trim( $parts[0] );
				if( count( $parts ) > 1 )
					$comment = trim($parts[1] );
				if( $this->usesSections() )
				{
					if( in_array( $currentSection, $this->sections ) )
					{
						if( isset( $this->deleted[$currentSection] ) && in_array( $pureKey, $this->deleted[$currentSection] ) )
							unset( $line );
						else if( isset( $this->renamed[$currentSection] ) && in_array( $pureKey, array_keys( $this->renamed[$currentSection] ) ) )
						{
							$newKey	= $key	= $this->renamed[$currentSection][$pureKey];
							if( !$this->isActiveProperty( $newKey, $currentSection) )
								$key = $this->disableSign.$key;
							$comment	= isset( $this->comments[$currentSection][$newKey] ) ? $this->comments[$currentSection][$newKey] : "";
							$line = $this->buildLine( $key, $this->properties[$currentSection][$newKey], $comment );

						}
						else
						{
							if( $this->isActiveProperty( $pureKey, $currentSection ) && eregi( $this->disablePattern, $key ) )
								$key = substr( $key, 1 );
							else if( !$this->isActiveProperty( $pureKey, $currentSection ) && !eregi( $this->disablePattern, $key ) )
								$key = $this->disableSign.$key;
							$comment	= isset( $this->comments[$currentSection][$pureKey] ) ? $this->comments[$currentSection][$pureKey] : "";
							$line = $this->buildLine( $key, $this->properties[$currentSection][$pureKey], $comment );
						}
					}
					else
						unset( $line );
				}
				else
				{
					if( in_array( $pureKey, $this->deleted ) )
						unset( $line);
					else if( in_array( $pureKey, array_keys( $this->renamed ) ) )
					{
						$newKey	= $key	= $this->renamed[$pureKey];
						if( !$this->isActiveProperty( $newKey ) )
							$key = $this->disableSign.$key;
						$line = $this->buildLine( $newKey, $this->properties[$newKey], $this->comments[$newKey] );
					}
					else
					{
						if( $this->isActiveProperty( $pureKey ) && eregi( $this->disablePattern, $key ) )
							$key = substr( $key, 1 );
						else if( !$this->isActiveProperty( $pureKey) && !eregi( $this->disablePattern, $key ) )
							$key = $this->disableSign.$key;
						$line = $this->buildLine( $key, $this->properties[$pureKey], $this->getComment( $pureKey ) );
					}
				}
			}
			if( isset( $line ) )
				$newLines[] = $line;
		}
		foreach( $this->added as $property )
		{
			$newLine = $this->buildLine( $property['key'], $property['value'], $property['comment'] );
			$newLines[] = $newLine;
		}
		$result	= $file->writeArray( $newLines );
		$this->added	= array();
		$this->deleted	= array();
		$this->renamed	= array();
		$this->read();
		return $result;
	}
}
?>