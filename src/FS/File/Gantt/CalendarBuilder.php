<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Builds iCal File with Meeting Dates from "Gantt Project" File.
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
 *	@package		CeusMedia_Common_FS_File_Gantt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Gantt;

use CeusMedia\Common\FS\File\ICal\Builder as IcalBuilder;
use CeusMedia\Common\XML\DOM\Node;

/**
 *	Builds iCal File with Meeting Dates from "Gantt Project" File.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Gantt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class CalendarBuilder
{
	/**	@var		string		$title		Title of iCal Root Node */
	protected $title;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$title		Title of iCal Root Node
	 *	@return		void
	 */
	public function __construct( string $title = "GanttProjectMeetings" )
	{
		$this->title	= $title;
	}

	/**
	 *	Builds iCal File with Project and Meeting Dates.
	 *	@access		public
	 *	@param		array		$projects		Array of Projects
	 *	@param		array		$meetings		Array of Meetings
	 *	@return		string
	 */
	public function build( array $projects, array $meetings ): string
	{
		$tree		= new Node( $this->title );
		$cal		= new Node( "vcalendar" );
		$cal->addChild( new Node( "version", "2.0" ) );
		foreach( $projects as $project ){
			$start		= strtotime( $project['start'] );
			$end		= strtotime( $project['end'] );
			$event		= new Node( "vevent" );
			$start		= new Node( "dtstart", date( "Ymd", $start ) );
			$end		= new Node( "dtend", date( "Ymd", $end ) );
			$summary	= new Node( "summary", $project['name'] );
			$event->addChild( $start );
			$event->addChild( $end );
			$event->addChild( $summary );
			$cal->addChild( $event );
		}

		foreach( $meetings as $meeting ){
			$start		= strtotime( $meeting['start'] );
			$end		= strtotime( $meeting['end'] );
			$event		= new Node( "vevent" );
			$start		= new Node( "dtstart", date( "Ymd", $start ) );
			$end		= new Node( "dtend", date( "Ymd", $end ) );
			$summary	= new Node( "summary", $meeting['name'] );
			$event->addChild( $start );
			$event->addChild( $end );
			$event->addChild( $summary );
			$cal->addChild( $event );
		}
		$tree->addChild( $cal );

		$builder	= new IcalBuilder();
		return $builder->build( $tree );
	}
}
