<?php
import( 'de.ceus-media.framework.helium.Action' );
import( 'de.ceus-media.file.log.LogFile' );
/**
 *	Generic Definition Action Handler.
 *	@package		framework
 *	@subpackage		helium
 *	@extends		Action
 *	@uses			DefinitionValidator
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2006
 *	@version		0.1
 */
/**
 *	Generic Definition Action Handler.
 *	@package		framework
 *	@subpackage		helium
 *	@extends		Action
 *	@uses			DefinitionValidator
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2006
 *	@version		0.1
 *	@todo			TO BE CLEARED in next Version (see below)
 */
class DefinitionAction extends Action
{
	/**	@var	string		$prefix		Prefix of XML Definition Files */
	var $prefix	= "";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->validator	= new DefinitionValidator;
		if( $this->loadLanguage( 'validator', false, false ) )
			$this->validator->setMessages( $this->lan['validator'] );
		$this->definition	=& $this->ref->get( 'definition' );
	}

	/**
	 *	Runs Validation of Field Definitions againt Request Input and creates Error Messages and returns Success.
	 *	@access		public
	 *	@param		string		$file				Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@param		string		$form			Name of Form within XML Definition File (e.g. 'addExample' )
	 *	@param		string		$lan_file			Name of Language File (e.g. 'example')
	 *	@param		string		$lan_section		Section in Language File (e.g. 'add')
	 *	@return		bool
	 */
	function validateForm( $file , $form, $lan_file, $lan_section )
	{
		$request	= $this->ref->get( 'request' );
		$labels	= $this->lan[$lan_file][$lan_section];

		$this->validator->setLabels( $labels );
		$errors	= array();
		$this->_loadDefinition( $file , $form, $this->prefix );
		$fields	= $this->definition->getFields();
		foreach( $fields as $field )
		{
			$data	= $this->definition->getField( $field );
			$value	= $request->get( $data['input']['name'] );
			if( is_string( $value ) )
			{
				$_errors	= $this->validator->validateSyntax( $field, $data, $value );
				if( !count( $_errors ) )
					$_errors	= $this->validator->validateSemantics( $field, $data, $value );
				if( count( $_errors ) )
					$errors[]	= $_errors[0];
			}
//			else 
//				$this->messenger->noteError( "Skipped Validation of Field '".$field."'" );
		}
		if( count( $errors ) )
			foreach( $errors as $error )
				$this->messenger->noteError( $error );
		return !count( $errors );
	}
	
	/**
	 *	Runs Validation of Field Definitions againt Request Input and creates Error Messages and returns Success.
	 *	@access		public
	 *	@param		string		$file				Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@param		string		$form			Name of Form within XML Definition File (e.g. 'addExample' )
	 *	@param		string		$lan_file			Name of Language File (e.g. 'example')
	 *	@param		string		$lan_section		Section in Language File (e.g. 'add')
	 *	@return		bool
	 *	@todo		TO BE DELETED in next Version
	 */
	function validateFormular( $file , $form, $lan_file, $lan_section )
	{
		$log	= new LogFile( "!depracated.log" );
		$log->addEntry( "ACTION file: ".$file." | form: ".$form." | lan: ".$lan_file." | section: ".$lan_section );
		return $this->validateForm( $file , $form, $lan_file, $lan_section );
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Runs Validation of Field Definitions againt Request Input and creates Error Messages.
	 *	@access		public
	 *	@param		string		$file				Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@return		void
	 */
	function _loadDefinition( $file , $form )
	{
		$this->definition->setForm( $form );
		$this->definition->setOption( 'prefix', $this->prefix );
		$this->definition->loadDefinition( $file );
	}
}
?>