<?php
/**
 *	Class to find all Files with ToDos inside.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		file
 *	@uses			File_RegexFilter
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.06.2008
 *	@version		0.1
 */
import( 'de.ceus-media.file.RegexFilter' );
/**
 *	Class to find all Files with ToDos inside.
 *	@category		cmClasses
 *	@package		file
 *	@uses			File_RegexFilter
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.06.2008
 *	@version		0.1
 */
class File_TodoLister
{
	/**	@var		int			$numberFound	Number of matching Files */
	protected $numberFound		= 0;
	/**	@var		int			$numberLines	Number of scanned Lines in matching Files */
	protected $numberLines		= 0;
	/**	@var		int			$numberScanned	Total Number of scanned Files */
	protected $numberScanned	= 0;
	/**	@var		int			$numberTodos	Number of found Todos */
	protected $numberTodos		= 0;
	/**	@var		string		$extension		Default File Extension */
	protected $extension		= "php5";
	/**	@var		array		$extensions		Other File Extensions */
	protected $extensions		= array();
	/**	@var		array		$list			List of numberFound Files */
	protected $list				= array();
	/**	@var		string		$pattern		Default Pattern */
	protected $pattern			= "@todo";
	/**	@var		array		$patterns		Other Patterns */
	protected $patterns			= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$additionalExtensions	Other File Extensions than 'php5'
	 *	@param		array		$additionalPatterns		Other Patterns than '@todo'
	 */
	public function __construct( $additionalExtensions = array(), $additionalPatterns = array() )
	{
		if( !is_array( $additionalExtensions ) )
			throw new InvalidArgumentException( 'Additional Extensions must be an Array.' );
		if( !is_array( $additionalPatterns ) )
			throw new InvalidArgumentException( 'Additional Patterns must be an Array.' );
		$this->extensions	= $additionalExtensions;
		$this->patterns		= $additionalPatterns;
	}
	
	private function getExtendedPattern( $member = "pattern" )
	{
		$list1	= array( $this->$member );
		$list1	= array_merge( $list1, $this->{$member."s"} );
		$list2	= array();
		foreach( $list1 as $item )
			$list2[]	= str_replace( ".", "\.", $item );
		if( count( $list2 ) == 1 )
			$pattern	= array_pop( $list2 );
		else
			$pattern	= "(".implode( "|", $list2 ).")";
		if( $member == "extension" )
			$pattern	.= "$";
		$pattern	= "%".$pattern."%";
		return $pattern;
	}
	
	protected function getExtensionPattern()
	{
		return $this->getExtendedPattern( "extension" );
	}

	/**
	 *	Returns Number of matching Files.
	 *	@access		public
	 *	@return		int
	 */
	public function getNumberFound()
	{
		return $this->numberFound;
	}

	/**
	 *	Returns Number of scanned Lines in matching Files.
	 *	@access		public
	 *	@return		int
	 */
	public function getNumberLines()
	{
		return $this->numberLines;
	}

	/**
	 *	Returns Number of scanned Files.
	 *	@access		public
	 *	@return		int
	 */
	public function getNumberScanned()
	{
		return $this->numberScanned;
	}

	/**
	 *	Returns Number of found Todos.
	 *	@access		public
	 *	@return		int
	 */
	public function getNumberTodos()
	{
		return $this->numberTodos;
	}

	protected function getPattern()
	{
		return $this->getExtendedPattern();
	}
	
	/**
	 *	Returns Array of numberFound Files.
	 *	@access		public
	 *	@param		bool		$full		Flag: Return Path Name, File Name and Content also
	 *	@return		array
	 */
	public function getList( $full = NULL )
	{
		if( $full )
			return $this->list;
		$list	= array();
		foreach( $this->list as $pathName => $fileData )
			$list[$pathName]	= $fileData['fileName'];
		return $list;
	}

	protected function getIndexIterator( $path, $filePattern )
	{
		return new File_RegexFilter( $path, $filePattern );
	}

	/**
	 *	Scans a Path for Files with Pattern.
	 *	@access		public
	 *	@return		int
	 */
	public function scan( $path )
	{
		$this->numberFound		= 0;
		$this->numberScanned	= 0;
		$this->numberTodos		= 0;
		$this->numberLines		= 0;
		$this->list		= array();
		$extensions		= $this->getExtensionPattern(); 
		$pattern		= $this->getExtendedPattern();
		$iterator		= $this->getIndexIterator( $path, $extensions );
		foreach( $iterator as $entry )
		{
			$this->numberScanned++;
			$content	= file_get_contents( $entry->getPathname() );
			$lines		= explode( "\n", $content );
			$i			=0;
			$list		= array();
			foreach( $lines as $line )
			{
				$this->numberLines++;
				$i++;
				if( !preg_match( $pattern, $line ) )
					continue;
				$this->numberTodos++;
				$list[$i]	= trim( $line );
			}
			if( !$list )
				continue;
			$this->numberFound++;
			$this->list[$entry->getPathname()]	= array(
				'fileName'	=> $entry->getFilename(),
				'lines'		=> $list,
			);
		}
	}
}
?>