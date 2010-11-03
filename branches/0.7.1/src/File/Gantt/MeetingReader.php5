<?php
/**
 *	Reads "Gantt Project" XML File and extracts basic Project Information and Meeting Dates.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		File.Gantt
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.03.2008
 *	@version		$Id$
 */
/**
 *	Reads "Gantt Project" XML File and extracts basic Project Information and Meeting Dates.
 *	@category		cmClasses
 *	@package		File.Gantt
 *	@uses			XML_DOM_XPathQuery
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.03.2008
 *	@version		$Id$
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
	 *	@static
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
		$node	= $this->xpath->evaluate( "//project/tasks/task" );

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