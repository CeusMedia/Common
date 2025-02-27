<?php
/**
 *	This class represents an object in a chart, i.e. a line in a line diagram, a piece of pie in a
 *	pie chart and so on.
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
 *	@package		CeusMedia_Common_UI_SVG
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI\SVG;

/**
 *	This class represents an object in a chart, i.e. a line in a line diagram, a piece of pie in a
 *	pie chart and so on.
 *	@category		Library
 *	@package		CeusMedia_Common_UI_SVG
 *	@author			Jonas Schneider <JonasSchneider@gmx.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class ChartData
{
	/**
	 *	Description of the data object.
	 *	@var string
	 *	@access public
	 */
	public string $desc;

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
	 *	@param		float		$value      Value
	 *	@param		string		$desc       Description
	 *	@return		void
	 */
	public function __construct( $value, string $desc = '' )
	{
		$this->desc = $desc;
		$this->value = $value;
	}
}