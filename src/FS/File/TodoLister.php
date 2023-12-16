<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Class to find all Files with ToDos inside.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File;

use Exception;
use RegexIterator;
use RuntimeException;
use UnexpectedValueException;

/**
 *	Class to find all Files with ToDos inside.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class TodoLister
{
	/**	@var		int				$numberFound	Number of matching Files */
	protected int $numberFound		= 0;

	/**	@var		int				$numberLines	Number of scanned Lines in matching Files */
	protected int $numberLines		= 0;

	/**	@var		int				$numberScanned	Total Number of scanned Files */
	protected int $numberScanned	= 0;

	/**	@var		int				$numberTodos	Number of found Todos */
	protected int $numberTodos		= 0;

	/**	@var		string			$extension		Default File Extension */
	protected string $extension		= 'php';

	/**	@var		array			$extensions		Other File Extensions */
	protected array $extensions		= [];

	/**	@var		array			$list			List of numberFound Files */
	protected array $list			= [];

	/**	@var		string			$pattern		Default Pattern */
	protected string $pattern		= '@todo';

	/**	@var		array			$patterns		Other Patterns */
	protected array $patterns		= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$additionalExtensions	Other File Extensions than 'php5'
	 *	@param		array		$additionalPatterns		Other Patterns than '@todo'
	 */
	public function __construct( array $additionalExtensions = [], array $additionalPatterns = [] )
	{
		$this->extensions	= $additionalExtensions;
		$this->patterns		= $additionalPatterns;
	}

	/**
	 *	Returns Array of numberFound Files.
	 *	@access		public
	 *	@param		bool		$full		Flag: Return Path Name, File Name and Content also
	 *	@return		array
	 */
	public function getList( bool $full = NULL ): array
	{
		if( $full )
			return $this->list;
		$list	= [];
		foreach( $this->list as $pathName => $fileData )
			$list[$pathName]	= $fileData['fileName'];
		return $list;
	}

	/**
	 *	Returns Number of matching Files.
	 *	@access		public
	 *	@return		int
	 */
	public function getNumberFound(): int
	{
		return $this->numberFound;
	}

	/**
	 *	Returns Number of scanned Lines in matching Files.
	 *	@access		public
	 *	@return		int
	 */
	public function getNumberLines(): int
	{
		return $this->numberLines;
	}

	/**
	 *	Returns Number of scanned Files.
	 *	@access		public
	 *	@return		int
	 */
	public function getNumberScanned(): int
	{
		return $this->numberScanned;
	}

	/**
	 *	Returns Number of found Todos.
	 *	@access		public
	 *	@return		int
	 */
	public function getNumberTodos(): int
	{
		return $this->numberTodos;
	}

	/**
	 *	Scans a Path for Files with Pattern.
	 *	@access		public
	 *	@param		string		$path
	 *	@return		void
	 */
	public function scan( string $path ): void
	{
		$this->numberFound		= 0;
		$this->numberScanned	= 0;
		$this->numberTodos		= 0;
		$this->numberLines		= 0;
		$this->list				= [];
		$extensions		= $this->getExtensionPattern();
		$pattern		= $this->getExtendedPattern();
		$iterator		= $this->getIndexIterator( $path, $extensions );
		try{
			foreach( $iterator as $entry ){
				$this->numberScanned++;
				$content	= file_get_contents( $entry->getPathname() );
				$lines		= explode( "\n", $content );
				$i			= 0;
				$list		= [];
				foreach( $lines as $line ){
					$this->numberLines++;
					$i++;
					if( !preg_match( $pattern, $line ) )
						continue;
					$this->numberTodos++;
					$list[$i]	= $line;#trim( $line );
				}
				if( !$list )
					continue;
				$this->numberFound++;
				$this->list[$entry->getPathname()]	= [
					'fileName'	=> $entry->getFilename(),
					'lines'		=> $list,
				];
			}
		}
		catch( UnexpectedValueException $e ){
		}
		catch( Exception $e ){
			throw new RuntimeException( $e->getMessage(), $e->getCode(), $e );
		}
	}

	protected function getExtensionPattern(): string
	{
		return $this->getExtendedPattern( 'extension' );
	}

	protected function getIndexIterator( string $path, string $filePattern ): RegexIterator
	{
		return new RegexFilter( $path, $filePattern );
	}

	protected function getPattern(): string
	{
		return $this->getExtendedPattern();
	}

	private function getExtendedPattern( string $member = 'pattern' ): string
	{
		$list1	= [$this->$member];
		$list1	= array_merge( $list1, $this->{$member.'s'} );
		$list2	= [];
		foreach( $list1 as $item )
			$list2[]	= str_replace( '.', '\.', $item );
		if( 1 === count( $list2 ) )
			$pattern	= array_pop( $list2 );
		else
			$pattern	= '('.implode( '|', $list2 ).')';
		if( $member == 'extension' )
			$pattern	.= "$";
		return '%'.$pattern.'%';
	}
}
