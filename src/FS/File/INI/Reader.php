<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for Property Files or typical .ini Files with Key, Values and optional Sections and Comments.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_INI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\INI;

use CeusMedia\Common\FS\File\Reader as FileReader;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Reader for Property Files or typical .ini Files with Key, Values and optional Sections and Comments.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_INI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader extends FileReader
{
	/**	@var		array					$comments				List of collected Comments */
	protected array $comments				= [];

	/**	@var		array					$lines					List of collected Lines */
	protected array $lines					= [];

	/**	@var		array					$properties				List of collected Properties */
	protected array $properties				= [];

	/**	@var		array					$sections				List of collected Sections */
	protected array $sections				= [];

	/**	@var		array					$disabled				List of disabled Properties */
	protected array $disabled				= [];

	/**	@var		bool					$usesSections			Flag: use Sections */
	protected bool $usesSections			= FALSE;

	/**	@var		boolean					$reservedWords			Flag: use reserved words */
	protected bool $reservedWords			= TRUE;

	/**	@var		string					$signDisabled			Sign( string) of disabled Properties */
	protected string $signDisabled			= ';';

	/**	@var		string					$patternDisabled		Pattern( regex) of disabled Properties */
	protected string $patternDisabled 		= '/^;/';

	/**	@var		string					$patternProperty		Pattern( regex) of Properties */
	protected string $patternProperty		= '/^(;|[a-z\d-])+([a-z\d#.:@\/\\|_-]*[ |\t]*=)/i';

	/**	@var		string					$patternDescription		Pattern( regex) of Descriptions */
	protected string $patternDescription	= '/^[;|#|:|\/|=]{1,2}/';

	/**	@var		string					$patternSection			Pattern( regex) of Sections */
	protected string $patternSection		= '/^\s*\[([a-z\d_=.,:;#@-]+)\]\s*$/i';

	/**	@var		string					$patternLineComment		Pattern( regex) of Line Comments */
	protected string $patternLineComment	= '/([\t| ]+(\/{2}|;)+[\t| ]*)/';

	/**
	 *	Constructor, reads Property File.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Property File, absolute or relative URI
	 *	@param		bool		$usesSections	Flag: Property File contains Sections
	 *	@param		bool		$reservedWords	Flag: interpret reserved Words like yes,no,true,false,null
	 *	@return		void
	 */
	public function __construct( string $fileName, bool $usesSections = FALSE, bool $reservedWords = TRUE )
	{
		parent::__construct( $fileName );
		$this->usesSections			= $usesSections;
		$this->reservedWords		= $reservedWords;
		$this->read();
	}

	/**
	 *	Returns the Comment of a Property.
	 *	@access		public
	 *	@param		string		$key			Key of Property
	 *	@param		string|NULL	$section		Section of Property
	 *	@return		string
	 */
	public function getComment( string $key, ?string $section = NULL ): string
	{
		if( $this->usesSections() ){
			if( empty( $section ) )
				throw new InvalidArgumentException( 'No section given' );
			if( !$this->hasSection( $section ) )
				throw new InvalidArgumentException( 'Section "'.$section.'" is not existing' );
			if( !empty( $this->comments[$section][$key] ) )
				return $this->comments[$section][$key];
		}
		else if( !empty( $this->comments[$key] ) )
			return $this->comments[$key];
		return '';
	}

	/**
	 *	Returns a List of Property Arrays with Key, Value, Comment and Activity of every Property.
	 *	@access		public
	 *	@param		bool		$activeOnly		Flag: return only active Properties
	 *	@return		array
	 */
	public function getCommentedProperties( bool $activeOnly = TRUE ): array
	{
		$list = [];
		if( $this->usesSections() ){
			foreach( $this->sections as $section ){
				foreach( $this->properties[$section] as $key => $value ){
					if( $activeOnly && !$this->isActiveProperty( $key, $section ) )
						continue;
					$property = [
						"key"		=> $key,
						"value"		=> $value,
						"comment"	=> $this->getComment( $key, $section ),
						"active"	=> $this->isActiveProperty( $key, $section )
					];
					$list[$section][] = $property;
				}
			}
		}
		else{
			foreach( $this->properties as $key => $value ){
				if( $activeOnly && !$this->isActiveProperty( $key ) )
					continue;
				$property = [
					"key"		=> $key,
					"value"		=> $value,
					"comment"	=> $this->getComment( $key ),
					"active"	=> $this->isActiveProperty( $key )
				];
				$list[] = $property;
			}
		}
		return $list;
	}

	/**
	 *	Returns all Comments or all Comments of a Section.
	 *	@access		public
	 *	@param		string|NULL	$section		Key of Section
	 *	@return		array
	 */
	public function getComments( ?string $section = NULL ): array
	{
		if( $this->usesSections() && $section ){
			if( $this->hasSection( $section ) )
				return $this->comments[$section];
			throw new InvalidArgumentException( 'Section "'.$section.'" is not existing' );
		}
		return $this->comments;
	}

	/**
	 *	Returns an Array with all or active only Properties.
	 *	@access		public
	 *	@param		bool		$activeOnly		Flag: return only active Properties
	 *	@param		string|NULL	$section		Only Section with given Section Name
	 *	@return		array
	 */
	public function getProperties( bool $activeOnly = TRUE, ?string $section = NULL ): array
	{
		$properties = [];
		if( $this->usesSections() ){
			if( $section ){
				if( !$this->hasSection( $section ) )
					throw new InvalidArgumentException( 'Section "'.$section.'" is not existing.' );
				foreach( $this->properties[$section]  as $key => $value ){
					if( $activeOnly && !$this->isActiveProperty( $key, $section ) )
						continue;
					$properties[$key] = $value;
				}
			}
			else{
				foreach( $this->sections as $section){
					$properties[$section]	= [];
					foreach( $this->properties[$section] as $key => $value ){
						if( $activeOnly && !$this->isActiveProperty( $key, $section ) )
							continue;
						$properties[$section][$key] = $value;
					}
				}
			}
		}
		else{
			foreach( $this->properties as $key => $value ){
				if( $activeOnly && !$this->isActiveProperty( $key ) )
					continue;
				$properties[$key] = $value;
			}
		}
		return $properties;
	}

	/**
	 *	Returns the Value of a Property by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Property
	 *	@param		string|NULL	$section		Key of Section
	 *	@param		bool		$activeOnly		Flag: return only active Properties
	 *	@return		string
	 */
	public function getProperty( string $key, ?string $section = NULL, bool $activeOnly = TRUE ): string
	{
		if( $this->usesSections() ){
			if( !$section )
				throw new InvalidArgumentException( 'No section given' );
			if( $activeOnly && !$this->isActiveProperty( $key, $section ) )
				throw new InvalidArgumentException( 'Property "'.$key.'" is not set or inactive' );
			return $this->properties[$section][$key];
		}
		else{
			if( $activeOnly && !$this->isActiveProperty( $key ) )
				throw new InvalidArgumentException( 'Property "'.$key.'" is not set or inactive' );
			return $this->properties[$key];
		}
	}

	/**
	 *	Returns an Array with all or active only Properties.
	 *	@access		public
	 *	@param		bool		$activeOnly		Flag: return only active Properties
	 *	@return		array
	 */
	public function getPropertyList( bool $activeOnly = TRUE ): array
	{
		$properties = [];
		if( $this->usesSections() ){
			foreach( $this->sections as $sectionName ){
				foreach( $this->properties[$sectionName] as $key => $value ){
					if( $activeOnly && !$this->isActiveProperty( $key, $sectionName ) )
						continue;
					$properties[$sectionName][] = $key;
				}
			}
		}
		else{
			foreach( $this->properties as $key => $value ){
				if( $activeOnly && !$this->isActiveProperty( $key ) )
					continue;
				$properties[] = $key;
			}
		}
		return $properties;
	}

	/**
	 *	Returns an array of all Section Keys.
	 *	@access		public
	 *	@return		array
	 */
	public function getSections(): array
	{
		if( !$this->usesSections() )
			throw new RuntimeException( 'Sections are disabled' );
		return $this->sections;
	}

	/**
	 *	Indicates whether a Property is existing.
	 *	@access		public
	 *	@param		string		$key		Key of Property
	 *	@param		string|NULL	$section	Key of Section
	 *	@return		bool
	 */
	public function hasProperty( string $key, ?string $section = NULL ): bool
	{
		if( $this->usesSections() ){
			if( empty( $section ) )
				throw new InvalidArgumentException( 'No section given' );
			if( !$this->hasSection( $section ) )
				throw new InvalidArgumentException( 'Section "'.$section.'" is not existing' );
			return isset( $this->properties[$section][$key] );
		}
		else
			return isset( $this->properties[$key] );
	}

	/**
	 *	Indicates whether a Property is existing.
	 *	@access		public
	 *	@param		string		$section	Key of Section
	 *	@return		bool
	 */
	public function hasSection( string $section ): bool
	{
		if( !$this->usesSections() )
			throw new RuntimeException( 'Sections are disabled' );
		return in_array( $section, $this->sections );
	}

	/**
	 *	Indicates whether a Property is active.
	 *	@access		public
	 *	@param		string		$key		Key of Property
	 *	@param		string|NULL	$section	Key of Section
	 *	@return		bool
	 */
	public function isActiveProperty( string $key, ?string $section = NULL ): bool
	{
		if( $this->usesSections() ){
			if( empty( $section ) )
				throw new InvalidArgumentException( 'No Section given' );
			if( isset( $this->disabled[$section] ) )
				if( is_array( $this->disabled[$section] ) )
					if( in_array( $key, $this->disabled[$section] ) )
						return FALSE;
			return $this->hasProperty( $key, $section );
		}
		else if( in_array( $key, $this->disabled ) )
			return FALSE;
		return $this->hasProperty( $key );
	}

	/**
	 *	Loads an INI File and returns an Array statically.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Property File, absolute or relative URI
	 *	@param		bool		$usesSections	Flag: Property File contains Sections
	 *	@param		bool		$activeOnly		Flag: return only active Properties
	 *	@return		array
	 */
	public static function loadArray( string $fileName, bool $usesSections = FALSE, bool $activeOnly = TRUE ): array
	{
		$reader	= new self( $fileName, $usesSections );
		return $reader->toArray( $activeOnly );
	}

	/**
	 *	Reads the entire Property File and divides Properties and Comments.
	 *	@access		protected
	 *	@return		void
	 */
	protected function read()
	{
		$this->comments		= [];
		$this->disabled		= [];
		$this->lines		= [];
		$this->properties	= [];
		$this->sections		= [];
		$commentOpen		= 0;
		$lines				= $this->readArray();
		foreach( $lines as $line ){
			$line			= trim( $line );
			$this->lines[]	= $line;

			$commentOpen	+= preg_match( "@^/\*@", trim( $line ) );
			$commentOpen	-= preg_match( "@\*/$@", trim( $line ) );

			if( $commentOpen )
				continue;

			if( $this->usesSections() && preg_match( $this->patternSection, $line ) ){
//  @todo remove this line in 0.8.0
#				$currentSection		= substr( trim( $line ), 1, -1 );
				$currentSection		= preg_replace( $this->patternSection, '\\1', $line );
				$this->sections[]	= $currentSection;
				$this->disabled[$currentSection]	= [];
				$this->properties[$currentSection]	= [];
				$this->comments[$currentSection]	= [];
			}
			else if( preg_match( $this->patternProperty, $line ) ){
				if( !count( $this->sections ) )
					$this->usesSections	= false;
				$pos	= strpos( $line, "=" );
				$key	= trim( substr( $line, 0, $pos ) );
				$value	= trim( substr( $line, ++$pos ) );

				if( preg_match( $this->patternDisabled, $key ) ){
					$key = preg_replace( $this->patternDisabled, "", $key );
					if( $this->usesSections() && isset( $currentSection ) )
						$this->disabled[$currentSection][] = $key;
					$this->disabled[] = $key;
				}

				//  --  EXTRACT COMMENT  --  //
				if( preg_match( $this->patternLineComment, $value ) ){
					$newValue		= preg_split( $this->patternLineComment, $value, 2 );
					$value			= trim( $newValue[0] );
					$inlineComment	= trim( $newValue[1] );
					if( $this->usesSections() && isset( $currentSection ) )
						$this->comments[$currentSection][$key] = $inlineComment;
					else
						$this->comments[$key] = $inlineComment;
				}

				//  --  CONVERT PROTECTED VALUES  --  //
				if( $this->reservedWords ){
					if( in_array( strtolower( $value ), ['yes', 'true'] ) )
						$value	= TRUE;
					else if( in_array( strtolower( $value ), ['no', 'false'] ) )
						$value	= FALSE;
					else if( strtolower( $value ) === "null" )
						$value	= NULL;
				}
				if( preg_match( '@^".*"$@', $value ?? '' ) )
					$value	= substr( stripslashes( $value ), 1, -1 );
				if( $this->usesSections() && isset( $currentSection ) )
					$this->properties[$currentSection][$key] = $value;
				else
					$this->properties[$key] = $value;
			}
		}
	}

	/**
	 *	Returns an array of all Properties.
	 *	@access		public
	 *	@param		bool			$activeOnly	Switch to return only active Properties
	 *	@return		array
	 */
	public function toArray( bool $activeOnly = TRUE ): array
	{
		return $this->getProperties( $activeOnly );
	}

	/**
	 *	Indicates whether Sections are used and sets this Switch.
	 *	@access		public
	 *	@param		boolean|NULL	$switch
	 *	@return		bool
	 */
	public function usesSections( ?bool $switch = NULL ): bool
	{
		if( !is_null( $switch ) )
			$this->usesSections= $switch;
		return $this->usesSections;
	}
}
