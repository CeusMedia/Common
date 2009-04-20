<?php
/**
 *	This class represents an object in a chart, i.e. a line in a line diagram, a piece of pie in a 
 *	pie chart and so on.
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
 *	@package		ui.svg
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
/**
 *	This class represents an object in a chart, i.e. a line in a line diagram, a piece of pie in a 
 *	pie chart and so on.
 *	@package		ui.svg
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
class UI_SVG_ChartData
{
	/**
	 *	Description of the data object.
	 *	@var string
	 *	@access public
	 */
	public $desc;
	
	/**
	 *	Value of the data object.
	 *	@var float
	 *	@access public
	 */
	public $value;
	
	/**
	 * 	The constructor.
	 *	It receives the description o the data, not needed, but for some chart types useful,
	 *	and, as a float, the value of the data.
	 *	@param		string		Description
	 *	@param		float		Value
	 *	@return		void
	 */
	public function __construct( $value, $desc = "" )
	{
		$this->desc = $desc;
		$this->value = $value;
	}
}
?>