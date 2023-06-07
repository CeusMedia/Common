<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reads "Gantt Project" XML File and extracts basic Project Information and Meeting Dates.
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
 *	@package		CeusMedia_Common_FS_File_Gantt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Gantt;

use CeusMedia\Common\XML\DOM\XPathQuery;
use Exception;

/**
 *	Reads "Gantt Project" XML File and extracts basic Project Information and Meeting Dates.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Gantt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class MeetingReader
{
	protected XPathQuery $xpath;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Gantt Project XML File
	 *	@return		void
	 */
	public function __construct( string $fileName )
	{
		$this->xpath	= new XPathQuery();
		$this->xpath->loadFile( $fileName );
	}

	/**
	 *	Calculates End Date from Start Date and Duration in Days.
	 *	@access		protected
	 *	@static
	 *	@param		string		$startDate		Start Date
	 *	@param		int			$durationDays	Duration in Days
	 *	@return		string		$endDate
	 */
	protected static function calculateEndDate( string $startDate, int $durationDays ): string
	{
		$time	= strtotime( $startDate ) + $durationDays * 24 * 60 * 60;
		return date( "Y-m-d", $time );
	}

	/**
	 *	Returns extracted Project and Meeting Dates.
	 *	@access		public
	 *	@return		array
	 *	@throws		Exception
	 */
	public function getProjectData(): array
	{
		$data		= $this->readProjectDates();
		$meetings	= $this->readMeetingDates();
		$data['meetings']	= $meetings;
		return $data;
	}

	/**
	 *	Returns extracted Meeting Dates.
	 *	@access		protected
	 *	@return		array
	 */
	protected function readMeetingDates(): array
	{
		$meetings	= [];
		$nodeList	= $this->xpath->evaluate( "//task[@meeting='true']" );
		foreach( $nodeList as $node ){
			$name		= $node->getAttribute( 'name' );
			$start		= $node->getAttribute( 'start' );
			$duration	= $node->getAttribute( 'duration' );
			$meetings[]	= [
				'name'		=> $name,
				'start'		=> $start,
				'end'		=> self::calculateEndDate( $start, $duration ),
				'duration'	=> $duration,
			];
		}
		return $meetings;
	}

	/**
	 *	Returns extracted Project Dates.
	 *	@access		protected
	 *	@return		array
	 *	@throws		Exception
	 */
	protected function readProjectDates(): array
	{
		$node	= $this->xpath->evaluate( "//project/tasks/task" );

		if( $node->length === 0 )
			throw new Exception( 'Task Node not found. No Task defined in Project.' );

		$name		= $node->item(0)->getAttribute( 'name' );
		$start		= $node->item(0)->getAttribute( 'start' );
		$duration	= $node->item(0)->getAttribute( 'duration' );

		return [
			'name'		=> $name,
			'start'		=> $start,
			'duration'	=> $duration,
			'end'		=> self::calculateEndDate( $start, $duration ),
		];
	}
}
