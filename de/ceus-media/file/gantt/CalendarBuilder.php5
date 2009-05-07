<?php
/**
 *	Builds iCal File with Meeting Dates from "Gantt Project" File.
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.03.2008
 *	@version		0.1
 */
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.file.ical.Builder' );
/**
 *	Builds iCal File with Meeting Dates from "Gantt Project" File.
 *	@uses			XML_DOM_Node
 *	@uses			File_iCal_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
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