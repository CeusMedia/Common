<?php
import( 'de.ceus-media.framework.neon.Action' );
import( 'de.ceus-media.file.log.LogFile' );
import( 'de.ceus-media.validation.DefinitionValidator' );
/**
 *	Generic Definition Action Handler.
 *	@package		framework.neon
 *	@extends		Framework_Neon_Action
 *	@uses			DefinitionValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2006
 *	@version		0.1
 */
/**
 *	Generic Definition Action Handler.
 *	@package		framework.neon
 *	@extends		Framework_Neon_Action
 *	@uses			DefinitionValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2006
 *	@version		0.1
 */
class Framework_Neon_DefinitionAction extends Framework_Neon_Action
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
		$this->loadLanguage( 'validator', false, false );
		$this->validator->setMessages( $this->words['validator'] );
		$this->definition	=& $this->ref->get( 'definition' );
	}

	/**
	 *	Runs Validation of Field Definitions againt Request Input and creates Error Messages.
	 *	@access		protected
	 *	@param		string		$file				Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@return		void
	 */
	protected function loadDefinition( $file , $form )
	{
		$this->definition->setForm( $form );
		$this->definition->setOption( 'prefix', $this->prefix );
		$this->definition->loadDefinition( $file );
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
	public function validateForm( $file , $form, $lan_file, $lan_section )
	{
		$request	= $this->ref->get( 'request' );
		$labels		= $this->words[$lan_file][$lan_section];

		$this->validator->setLabels( $labels );
		$errors	= array();
		$this->loadDefinition( $file , $form, $this->prefix );
		$fields	= $this->definition->getFields();
		foreach( $fields as $field )
		{
			$data	= $this->definition->getField( $field );
			$value	= $request->get( $data['input']['name'] );
//			if( is_array( $value ) )
//				$this->messenger->noteError( "Skipped Validation of Field '".$field."' because of Data Type Array" );
//			else
//			{
			if( !is_array( $value ) )
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
}
?>