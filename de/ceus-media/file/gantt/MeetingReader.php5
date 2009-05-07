<?php
/**
 *	Reads "Gantt Project" XML File and extracts basic Project Information and Meeting Dates.
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.03.2008
 *	@version		0.1
 */
import( 'de.ceus-media.xml.dom.XPathQuery' );
/**
 *	Reads "Gantt Project" XML File and extracts basic Project Information and Meeting Dates.
 *	@uses			XML_DOM_XPathQuery
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.03.2008
 *	@version		0.1
 */
class File_Gantt_MeetingReader
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Gantt Project XML File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->xpath	= new XML_DOM_XPathQuery();
		$this->xpath->loadFile( $fileName );
	}

	/**
	 *	Calculates End Date from Start Date and Duration in Days.
	 *	@access		protected
	 *	@param		string		$startDate		Start Date
	 *	@param		int			$durationDays	Duration in Days
	 *	@return		string		$endDate
	 */
	protected static function calculateEndDate( $startDate, $durationDays )
	{
		$time	= strtotime( $startDate ) + $durationDays * 24 * 60 * 60;
		$time	= date( "Y-m-d", $time );
		return $time;
	}
	
	/**
	 *	Returns extracted Project and Meeting Dates.
	 *	@access		public
	 *	@return		array
	 */
	public function getProjectData()
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
	protected function readMeetingDates()
	{
		$meetings	= array();
		$nodeList	= $this->xpath->evaluate( "//task[@meeting='true']" );
		foreach( $nodeList as $node )
		{
			$name		= $node->getAttribute( 'name' );
			$start		= $node->getAttribute( 'start' );
			$duration	= $node->getAttribute( 'duration' );
			$meetings[]	= array(
				'name'		=> $name,
				'start'		=> $start,
				'end'		=> self::calculateEndDate( $start, $duration ),
				'duration'	=> $duration,
			);
		}
		return $meetings;
	}

	/**
	 *	Returns extracted Project Dates.
	 *	@access		protected
	 *	@return		array
	 */
	protected function readProjectDates()
	{
		$node	= $this->xpath->evaluate( "//project/tasks/task[@id=0]" );

		if( $node->length == 0 )
			throw new Exception( 'Task Node not found. No Task defined in Project.' );

		$name		= $node->item(0)->getAttribute( 'name' );
		$start		= $node->item(0)->getAttribute( 'start' );
		$duration	= $node->item(0)->getAttribute( 'duration' );

		$data	= array(
			'name'		=> $name,
			'start'		=> $start,
			'duration'	=> $duration,
			'end'		=> self::calculateEndDate( $start, $duration ),
		);
		return $data;
	}
}
?>