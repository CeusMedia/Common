<?php
/**
 *	Service Handler which indexes with HTML Output.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		UI.HTML.Service
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		$Id$
 */
/**
 *	Service Handler which indexes with HTML Output.
 *	@category		cmClasses
 *	@package		UI.HTML.Service
 *	@extends		Net_Service_Handler
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		$Id$
 */
class UI_HTML_Service_Index extends Net_Service_Handler
{
	/**	@param		array				List of available Response Formats */
	protected $formats		= array();
	/**	@param		ServicePoint		Intance of a Service Point */
	protected $servicePoint	= NULL;
	/**	@param		string				CSS Class of Template in Template */
	protected $tableClass;
	/**	@param		string				File Name of Template for Index */
	protected $template;

	/**
	 *	Shows Index Page of Service.
	 *	@access		public
	 *	@return		string		HTML of Service Index
	 */
	public function buildContent( $subfolderLevel = 0 )
	{
		$title			= $this->servicePoint->getTitle();							//  Services Title
		$description	= $this->servicePoint->getDescription();					//  Services Title
		$syntax			= $this->servicePoint->getSyntax();							//  Services Syntax
		$table			= $this->getServiceTable();									//  Services Table
		$list			= $this->getServiceList();									//  Services List

		//  --  TYPES FOR FILTER  --  //
		$optFormat	= array( '<option value=""> -- all -- </option>' );
		foreach( $this->availableFormats as $format )
			$optFormat[$format]	= "<option>".$format."</option>";
		$optFormat	= implode( "", $optFormat );
		
		$basePath	= str_repeat( "../", $subfolderLevel );
		return require_once( $this->template );
	}
		
	protected function buildTest( $requestData )
	{
		$test	= new UI_HTML_Service_Test( $this->servicePoint, $this->availableFormats, $this->tableClass );
		echo $test->buildContent( $requestData );
		return;		
		$service	= $requestData['test'];
		$parameterList	= array();
		$parameters	= $this->servicePoint->getServiceParameters( $service );
		foreach( $parameters as $parameter => $rules )
		{
			$ruleList	= array();
			if( $rules )
			{
				foreach( $rules as $ruleKey => $ruleValue )
				{
					if( $ruleKey == "mandatory" )
						$ruleValue = $ruleValue ? "yes" : "no";
					$ruleList[]	= $ruleKey.": ".htmlspecialchars( $ruleValue );
				}
			}
			$rules	= implode( ", ", $ruleList );
			if( $rules )
			{
				$rules	= ' ('.$rules.')';
			}
			$value		= isset( $requestData[$parameter] ) ? $requestData[$parameter] : NULL;	
			$parameter	= $parameter.$rules.'<br/>'.UI_HTML_Elements::Input( $parameter, $value, 'x' );
			$parameterList[]	= $parameter;
		}
		$parameters	= implode( ", ", $parameterList );
		if( $parameters )
			$parameters	= " ".$parameters." ";

		die( $parameters );
	}

	/**
	 *	Return Service List.
	 *	@access		public
	 *	@return		string			HTML of Service List
	 */
	protected function getServiceList()
	{	
		$services	= array();
		$list		= $this->servicePoint->getServices();
		natcasesort( $list );

		foreach( $list as $entry )
		{
			$parameterList	= array();
			$parameters	= $this->servicePoint->getServiceParameters( $entry );
			
			foreach( $parameters as $parameter => $rules )
			{
				$ruleList	= array();
				if( $rules )
				{
					foreach( $rules as $ruleKey => $ruleValue )
					{
						if( $ruleKey == "mandatory" )
							$ruleValue = $ruleValue ? "yes" : "no";
						if( $ruleKey == "filters" )
							$ruleValue = implode( ", ", $ruleValue );
						$ruleList[]	= $ruleKey.": ".htmlspecialchars( $ruleValue );
					}
				}
				$rules	= implode( ", ", $ruleList );
				if( $rules )
					$parameter	= '<acronym title="'.$rules.'">'.$parameter.'</acronym>';
				$parameterList[]	= $parameter;
			}
			$parameters	= implode( ", ", $parameterList );
			if( $parameters )
				$parameters	= " ".$parameters." ";

			$desc	= $this->servicePoint->getServiceDescription( $entry );
			if( $desc )
				$entry	= '<acronym title="'.$desc.'">'.$entry.'</acronym>';
			$services[]	= "<li>".$entry."(".$parameters.")</li>";
		}
		$services	= "<ul>".implode( "", $services )."</ul>";	
		return $services;
	}


	/**
	 *	Return HTML Table of Services with their available Formats.
	 *	@access		public
	 *	@return		string			HTML of Service Table
	 */
	protected function getServiceTable()
	{
		$table	= new UI_HTML_Service_Table( $this->servicePoint, $this->availableFormats, $this->tableClass );
		return $table->buildContent();
	}
	
	/**
	 *	Sets CSS Class of Template in Template.
	 *	@access		public
	 *	@param		string		$class			CSS Class of Template in Template
	 *	@return		void
	 */
	public function setTableClass( $class )
	{
		$this->tableClass	= $class;
	}
	
	/**
	 *	File Name of Template for Index.
	 *	@access		public
	 *	@param		string		$template		File Name of Template
	 *	@return		void
	 */
	public function setTemplate( $template )
	{
		$this->template			= $template;
	}
}
?>