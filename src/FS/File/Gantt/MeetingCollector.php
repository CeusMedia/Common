<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reads for several "Gantt Project" XML Files and extracts Project Information and Meeting Dates.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Gantt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Gantt;

use DirectoryIterator;
use Exception;

/**
 *	Reads for several "Gantt Project" XML Files and extracts Project Information and Meeting Dates.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Gantt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class MeetingCollector
{
	/**	@var		array		$files			Array of found Gantt Project XML Files */
	protected $files			= [];

	/**	@var		array		$projects		Array of extracted Project Dates */
	protected $projects			= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path			Path to Gantt Project XML Files
	 *	@return		void
	 *	@throws		Exception
	 */
	public function __construct( string $path )
	{
		$this->files	= self::listProjectFiles( $path );
		$this->projects	= self::readProjectFiles( $this->files );
	}

	/**
	 *	Returns extracted Meeting Dates.
	 *	@access		public
	 *	@param		string		$projectName	Name of Project (optional)
	 *	@return		array
	 */
	public function getMeetingDates( string $projectName = "" ): array
	{
		$dates	= [];
		foreach( $this->projects as $project ){
			if( $projectName && $projectName != $project['name'] )
				continue;
			foreach( $project['meetings'] as $meeting ){
				$dates[]	= [
					'project'	=> $project['name'],
					'name'		=> $meeting['name'],
					'start'		=> $meeting['start'],
					'end'		=> $meeting['end'],
				];
			}
		}
		return $dates;
	}

	/**
	 *	Returns extracted Project Dates.
	 *	@access		public
	 *	@param		string		$projectName	Name of Project (optional)
	 *	@return		array
	 */
	public function getProjectDates( string $projectName = "" ): array
	{
		$dates	= [];
		foreach( $this->projects as $project ){
			if( $projectName && $projectName != $project['name'] )
				continue;
			$dates[]	= [
				'name'	=> $project['name'],
				'start'	=> $project['start'],
				'end'	=> $project['end'],
			];
		}
		return $dates;
	}

	/**
	 *	Lists all Gantt Project XML Files in a specified Path.
	 *	@access		protected
	 *	@static
	 *	@param		string		$path			Path to Gantt Project XML Files
	 *	@return		array
	 */
	protected static function listProjectFiles( string $path ): array
	{
		$list	= [];
		$dir	= new DirectoryIterator( $path );
		foreach( $dir as $entry ){
			if( $entry->isDot() )
				continue;
			if( !preg_match( "@\.gan$@", $entry->getFilename() ) )
				continue;
			$list[]	= $entry->getPathname();
		}
		return $list;
	}

	/**
	 *	Reads Gantt Project XML Files and extracts Project and Meeting Dates.
	 *	@access		protected
	 *	@static
	 *	@param		array		$fileList		List of Gantt Project XML Files
	 *	@return		array
	 *	@throws		Exception
	 */
	protected static function readProjectFiles( array $fileList ): array
	{
		$projects	= [];
		foreach( $fileList as $fileName ){
			$reader		= new MeetingReader( $fileName );
			$projects[]	= $reader->getProjectData();
		}
		return $projects;
	}
}
