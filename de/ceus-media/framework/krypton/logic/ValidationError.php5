<?php
/**
 *	Validation Error.
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
 *	@package		framework.krypton.logic
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			22.02.2007
 *	@version		$Id$
 */
/**
 *	Validation Error.
 *	@category		cmClasses
 *	@package		framework.krypton.logic
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			22.02.2007
 *	@version		$Id$
 */
class Framework_Krypton_Logic_ValidationError
{
	/**	@var	string			$edge			Edge for semantic validation */
	public $edge;
	/**	@var	string			$fieldKey		Definition Key of Field */
	public $fieldKey;
	/**	@var	string			$fieldName		Name of Field in Form */
	public $fieldName;
	/**	@var	string			$fieldValue		Value of Field */
	public $fieldValue;
	/**	@var	string			$validator		Validator Class */
	public $validator;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fieldKey		Definition Key of Field
	 *	@param		string		$fieldName		Name of Field in Form
	 *	@param		string		$value			Value of Field
	 *	@param		string		$validator		Validator Class
	 *	@param		string		$edge			Edge for semantic validation
	 *	@return		void
	 */
	public function __construct( $fieldKey, $fieldName, $value, $validator, $edge = NULL )
	{
		$this->fieldKey		= $fieldKey;
		$this->fieldName	= $fieldName;
		$this->fieldValue	= $value;
		$this->validator	= $validator;
		$this->edge			= $edge;
	}
}
?>