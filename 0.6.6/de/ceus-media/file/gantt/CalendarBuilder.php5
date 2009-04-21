<?php
/**
 *	Builds iCal File with Meeting Dates from "Gantt Project" File.
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
 *	@package		file.gantt
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.03.2008
 *	@version		0.1
 */
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.file.ical.Builder' );
/**
 *	Builds iCal File with Meeting Dates from "Gantt Project" File.
 *	@package		file.gantt
 *	@uses			XML_DOM_Node
 *	@uses			File_iCal_Builder
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.03.2008
 *	@version		0.1
 */
class File_Gantt_CalendarBuilder
{
	/**	@var		string		$title		Title of iCal Root Node */
	protected $title;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$title		Title of iCal Root Node
	 *	@return		void
	 */
	public function __construct( $title = "GanttProjectMeetings" )
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
	public function build( $projects, $meetings )
	{
		$tree		= new XML_DOM_Node( $this->title );
		$cal		= new XML_DOM_Node( "vcalendar" );
		$cal->addChild( new XML_DOM_Node( "version", "2.0" ) );
		foreach( $projects as $project )
		{
			$start		= strtotime( $project['start'] );
			$end		= strtotime( $project['end'] );
			$event		= new XML_DOM_Node( "vevent" );
			$start		= new XML_DOM_Node( "dtstart", date( "Ymd", $start ) );
			$end		= new XML_DOM_Node( "dtend", date( "Ymd", $end ) );
			$summary	= new XML_DOM_Node( "summary", $project['name'] );
			$event->addChild( $start );
			$event->addChild( $end );
			$event->addChild( $summary );
			$cal->addChild( $event );
		}

		foreach( $meetings as $meeting )
		{
			$start		= strtotime( $meeting['start'] );
			$end		= strtotime( $meeting['end'] );
			$event		= new XML_DOM_Node( "vevent" );
			$start		= new XML_DOM_Node( "dtstart", date( "Ymd", $start ) );
			$end		= new XML_DOM_Node( "dtend", date( "Ymd", $end ) );
			$summary	= new XML_DOM_Node( "summary", $meeting['name'] );
			$event->addChild( $start );
			$event->addChild( $end );
			$event->addChild( $summary );
			$cal->addChild( $event );
		}
		$tree->addChild( $cal );

		$builder	= new File_iCal_Builder();
		$ical		= $builder->build( $tree );
		return $ical;
	}
}
?>