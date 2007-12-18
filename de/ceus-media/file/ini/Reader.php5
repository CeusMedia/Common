<?php
import( 'de.ceus-media.file.Reader' );
/**
 *	Property File Reader.
 *	@package		file.ini
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.01.2001
 *	@version		0.5
 */
/**
 *	Property File Reader.
 *	@package		file.ini
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.01.2001
 *	@version		0.5
 */
class File_INI_Reader extends File_Reader
{
	/**	@var		string		$fileName				URI of Ini File */
	protected $fileName			= array();
	/**	@var		array		$comments				List of collected Comments */
	protected $comments			= array();
	/**	@var		array		$lines					List of collected Lines */
	protected $lines			= array();
	/**	@var		array		$properties				List of collected Properties */
	protected $properties		= array();
	/**	@var		array		$sections				List of collected Sections */
	protected $sections			= array();
	/**	@var		array		$disabled				List of disabled Properties */
	protected $disabled			= array();
	/**	@var		bool		$useSections			Flag: use Sections */
	protected $useSections		= false;
	/**	@var		string		$disableSign			Sign( string) of disabled Properties */
	protected $disableSign;
	/**	@var		string		$disablePattern			Pattern( regex) of disabled Properties */
	protected $disablePattern;
	/**	@var		string		$propertyPattern		Pattern( regex) of Properties */
	protected $propertyPattern;
	/**	@var		string		$descriptionPattern		Pattern( regex) of Descriptions */
	protected $descriptionPattern;
	/**	@var		string		$sectionPattern			Pattern( regex) of Sections */
	protected $sectionPattern;
	/**	@var		string		$lineCommentPattern		Pattern( regex) of Line Comments */
	protected $lineCommentPattern;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		Filename of Property File
	 *	@param		bool		$useSections	Switch to use Sections in Property File
	 *	@return		void
	 */
	public function __construct( $fileName, $useSections = false )
	{
		parent::__construct( $fileName );
		$this->usesSections( $useSections );
		$this->disableSign 			= ";";
		$this->disablePattern 		= "^[".$this->disableSign."]{1}";
		$this->propertyPattern		= "^(".$this->disableSign."|[a-z0-9-])+([a-z0-9#.:@/\|_-]*[ |\t]*=)";
		$this->descriptionPattern		= "^[;|#|:|/|=]{1,2}";
		$this->sectionPattern			= "^([){1}([a-z0-9_=.,:;#@-])+(]){1}$";
		$this->lineCommentPattern	= "([\t| ]+([/]{2}|[;])+[\t| ]*)";
		$this->read();
	}

	/**
	 *	Reads the entire Property File and divides Properties and Comments.
	 *	@access		protected
	 *	@return		void
	 */
	protected function read()
	{
		$this->comments		= array();
		$this->disabled		= array();
		$this->lines		= array();
		$this->properties	= array();
		$this->sections		= array();
		$f = new File_Reader( $this->fileName );
		if( $f->exists() )
		{
			if( $f->isReadable() )
			{
				$lines = $f->readArray();
				$this->lines = $this->comments = array();
				foreach( $lines as $line )
				{
					$line	= trim( $line );
					$this->lines[] = $line;
					if( $this->usesSections() && eregi( $this->sectionPattern, $line ) )
					{
						$currentSection	= substr( trim( $line ), 1, -1 );
						$this->sections[]	= $currentSection;
						$this->disabled[$currentSection]	= array();
						$this->properties[$currentSection]	= array();
						$this->comments[$currentSection]	= array();
					}
					else if( eregi( $this->propertyPattern, $line ) )
					{
						if( !count( $this->sections ) )
							$this->usesSections( false );
						$pos = strpos( $line, "=" );
						$key = trim( substr( $line, 0, $pos ) );
						$value = trim( substr( $line, $pos+1 ) );
						if( ereg( $this->disablePattern, $key ) )
						{
							$key = ereg_replace( $this->disablePattern, "", $key );
							if( $this->usesSections() ) $this->disabled[$currentSection][] = $key;
							$this->disabled[] = $key;
						}
						if( eregi( $this->lineCommentPattern, $value ) )
						{
							$newValue = spliti( $this->lineCommentPattern, $value );
							$value = trim( $newValue[0] );
							$inlineComment = trim( $newValue[1] );
							if( $this->usesSections() ) $this->comments[$currentSection][$key] = $inlineComment;
							else $this->comments[$key] = $inlineComment;
						}
						if( $this->usesSections() )
							$this->properties[$currentSection][$key] = $value;
						else
							$this->properties[$key] = $value;
					}
				}
			}
			else
				trigger_error( "File '".$this->fileName."' is not readable.", E_USER_WARNING );
		}
		else
			trigger_error( "File '".$this->fileName."' is not existing.", E_USER_WARNING );
	}

	/**
	 *	Returns the Value of a Property by its Key.
	 *	@access		public
	 *	@param		string		$key		Key of Property
	 *	@param		string		$sections	Key of Section
	 *	@return		string
	 */
	public function getProperty( $key, $section = false )
	{
		if( $this->usesSections() )
		{
			if( $this->isActiveProperty( $key, $section ) )
				return $this->properties[$section][$key];
		}
		else
		{
			if( isset( $this->properties[$key] ) && $this->isActiveProperty( $key ) )
				return $this->properties[$key];
		}
	}

	/**
	 *	Indicates wheter a Property is existing.
	 *	@access		public
	 *	@param		string		$key		Key of Property
	 *	@param		string		$sections	Key of Section
	 *	@return		bool
	 */
	public function isProperty( $key, $section = false)
	{
		if( $this->usesSections() )
			return isset( $this->properties[$section][$key] );
		else
			return isset( $this->properties[$key] );
	}


	/**
	 *	Indicates wheter a Property is active.
	 *	@access		public
	 *	@param		string		$key		Key of Property
	 *	@param		string		$sections	Key of Section
	 *	@return		bool
	 */
	public function isActiveProperty( $key, $section = false )
	{
		if( $this->usesSections() )
		{
			if( is_array( $this->disabled[$section] ) )
				return !in_array( $key, $this->disabled[$section] );
			else return true;
		}
		else
		{
			return !in_array( $key, $this->disabled );
		}
	}

	/**
	 *	Returns an associative array with all or active only Properties.
	 *	@access		public
	 *	@param		bool			$activeOnly	Switch to return only active Properties
	 *	@return		array
	 */
	public function getPropertyList( $activeOnly = false )
	{
		$properties = array();
		if( $this->usesSections() )
		{
			foreach( $this->sections as $sectionName )
				foreach( $this->properties[$sectionName]  as $key => $value )
					if( $activeOnly && $this->isActiveProperty( $key, $sectionName ) || !$activeOnly )
						$properties[$sectionName][] = $key;
		}
		else
		{
			foreach( $this->properties as $key => $value )
				if( $activeOnly && $this->isActiveProperty( $key ) || !$activeOnly )
					$properties[] = $key;
		}
		return $properties;
	}

	/**
	 *	Returns an associative array with all or active only Properties.
	 *	@access		public
	 *	@param		bool		$activeOnly		Switch to return only active Properties
	 *	@param		string		$section		Only Section with given Section Name
	 *	@return		array
	 */
	public function getProperties( $activeOnly = false, $section = false)
	{
		$properties = array();
		if( $this->usesSections() )
		{
			foreach( $this->sections as $sectionName )
			{
				if( !$section || $sectionName == $section )
				{
					$properties[$sectionName]	= array();
					foreach( $this->properties[$sectionName]  as $key => $value )
						if( $activeOnly && $this->isActiveProperty( $key, $sectionName ) || !$activeOnly )
							$properties[$sectionName][$key] = $value;
				}
			}
			if( $section )
			{
				if( isset( $properties[$section] ) )
					$properties	= $properties[$section];
				else
					return array();
			}
		}
		else
		{
			foreach( $this->properties as $key => $value )
			{
				if( $activeOnly && $this->isActiveProperty( $key ) || !$activeOnly )
					$properties[$key] = $value;
			}
		}
		return $properties;
	}

	/**
	 *	Returns the Comment of a Property.
	 *	@access		public
	 *	@param		string		$key		Key of Property
	 *	@param		string		$sections	Section of Property
	 *	@return		string
	 */
	public function getComment( $key, $section = false )
	{
		if( $this->usesSections() && isset( $this->comments[$section][$key] ) )
			return $this->comments[$section][$key];
		else if( isset( $this->comments[$key] ) )
			return $this->comments[$key];
		else
			return "";
	}

	/**
	 *	Returns all Comments or all Comments of a Section.
	 *	@access		public
	 *	@param		string		$section	Key of Section
	 */
	public function getComments( $section = false)
	{
		if( $this->usesSections() && is_array( $this->comments[$section] ) )
			return $this->comments[$section];
		return $this->comments;
	}

	/**
	 *	no desc yet.
	 *	@access		public
	 *	@return		array
	 */
	public function getCommentProperties()
	{
		return $this->toCommentedArray();
	}

	/**
	 *	Returns an array of all Section Keys.
	 *	@access		public
	 *	@return		array
	 */
	public function getSections()
	{
		return $this->sections;
	}

	/**
	 *	Returns an array of all Properties.
	 *	@access		public
	 *	@param		bool			$activeOnly	Switch to return only active Properties
	 *	@return		array
	 */
	public function toArray( $activeOnly = false )
	{
		return $this->getProperties( $activeOnly );
	}

	/**
	 *	Returns an array with associations of Key, Value and Comment for every Property.
	 *	@access		public
	 *	@param		bool			$activeOnly	Switch to return only active Properties
	 *	@return		array
	 *	@todo 		[IniReader::toCommentedArray] überarbeiten
	 */
	public function toCommentedArray( $activeOnly = false )
	{
		$a = Array();
		if( $this->usesSections() )
		{
			foreach( $this->sections as $section )
			{
				foreach( $this->properties[$section] as $key => $value )
				{
					if( ( $activeOnly && $this->isActiveProperty( $key, $section ) ) || !$activeOnly )
					{
						$b = Array(
							"key"		=>	$key,
							"value"		=>	$value,
							"comment"	=>	$this->getComment( $key, $section ),
							"active"		=> 	(bool)$this->isActiveProperty( $key, $section )
							);
						$a[$section][] = $b;
					}
				}
			}
		}
		else
		{
			foreach( $this->properties as $key => $value )
			{
				if( ( $activeOnly && $this->isActiveProperty( $key ) ) || !$activeOnly )
				{
					$b = Array(
						"key"		=>	$key,
						"value"		=>	$value,
						"comment"	=>	$this->getComment( $key ),
						"active"	=> 	(bool)$this->isActiveProperty( $key )
						);
					$a[] = $b;
				}
			}
		}
		return $a;
	}

	/**
	 *	Indicates wheter Sections are used and sets this Switch.
	 *	@access		public
	 *	@param		string		$set		Flag: use Sections or not
	 *	@return		array
	 */
	public function usesSections( $set = 0 )
	{
		if( is_bool( $set ) )
			$this->useSections = $set;
		return $this->useSections;
	}
}
?>