<?php
import( 'de.ceus-media.ui.html.service.Index' );
import( 'de.ceus-media.service.Handler' );
/**
 *	Service Handler which indexes with HTML Output.
 *	@package		ui.html.service
 *	@extends		UI_HTML_Service_Index
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.5
 */
/**
 *	Service Handler which indexes with HTML Output.
 *	@package		ui.html.service
 *	@extends		UI_HTML_Service_Index
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.5
 */
class UI_HTML_Service_ParametricIndex extends UI_HTML_Service_Index
{
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
						$ruleList[]	= $ruleKey.": ".$ruleValue;
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
		$rows		= array();
		$services	= $this->servicePoint->getServices();
		natcasesort( $services );
		$heads		= array();
		
		$heads	= array( "<th>Service</th>" );
		$cols	= array( "<col width='30%'/>" ); 
		foreach( $this->availableFormats as $format )
		{
			$cols[]		= "<col width='".round( ( 100 - 30 ) / count( $this->availableFormats ), 0 )."%'/>";
			$heads[]	= "<th>".strtoupper( $format )."</th>";
		}
		$cols	= "<colgroup>".implode( "", $cols )."</colgroup>";
		$heads	= "<tr>".implode( "", $heads )."</tr>";
		foreach( $services as $service )
		{
			$cells		= array();
			$formats	= $this->servicePoint->getServiceFormats( $service );
			foreach( $this->availableFormats as $format )
			{
				if( in_array( $format, $formats ) )
					$cells[]	= "<td class='yes'>+</td>";
				else
					$cells[]	= "<td class='no'>-</td>";
			}
			$row	= "<tr><td>".$service."</td>".implode( "", $cells )."</tr>";
			$rows[]	= $row;
		}
		return "<table class='".$this->tableClass."'>".$cols.$heads.implode( "", $rows )."</table>";
	}
	
	/**
	 *	Handles Request to Service Point by either calling a Service or indexing all Services.
	 *	@access		public
	 *	@param		array|Object	$requestData			Array from Request, containing Service Name and Response Format
	 *	@param		bool			$serializeExceptions	Flag: serialize Exceptions instead of throwing
	 *	@return		void
	 */
	public function handle( $request, $serializeExceptions = false )
	{
		parent::handle( $request, $serializeExceptions );
	}
	
	/**
	 *	Sets HTTP Basic Authentification.
	 *	@access		public
	 *	@param		string		$usename		Username for HTTP Basic Authentification
	 *	@param		string		$password		Password for HTTP Basic Authentification
	 *	@return		void
	 */
	public function setBasicAuth( $username, $password )
	{
		$this->username	= $username;
		$this->password	= $password;
	}
	
	/**
	 *	Sets Basic Host URL of Service.
	 *	@access		public
	 *	@param		string		Basic Host URL of Service
	 *	@return		void
	 */
	public function setHostAddress( $host )
	{
		$this->host	= $host;
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