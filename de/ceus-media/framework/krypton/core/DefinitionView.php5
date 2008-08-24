<?php
import( 'de.ceus-media.framework.krypton.core.View' );
#import( 'de.ceus-media.file.log.LogFile' );
#import( 'de.ceus-media.framework.krypton.logic.ValidationError' );
/**
 *	Generic Definition View with Language Support.
 *	@package		framework.krypton.core
 *	@extends		Framework_Krypton_Core_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2006
 *	@version		0.6
 */
/**
 *	Generic Definition View with References.
 *	@package		framework.krypton.core
 *	@extends		Framework_Krypton_Core_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2006
 *	@version		0.6
 */
class Framework_Krypton_Core_DefinitionView extends Framework_Krypton_Core_View
{
	/**	@var	string		$prefix		Prefix of XML Definition Files */
	protected $prefix		= "";
	protected $definition	= null;
	
	/**
	 *	Constructor, references Output Objects.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $useWikiParser = false )
	{
		parent::__construct( $useWikiParser );
		$this->definition	= $this->registry->get( 'definition' );
	}

	/**
	 *	Build Fields of Form Fields.
	 *	@access		public
	 *	@param		string		$fileName			Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@param		string		$formName			Name of Form within XML Definition File (e.g. 'addExample' )
	 *	@param		string		$languageFile		Name of Language File (e.g. 'example')
	 *	@param		string		$languageSection	Section in Language File (e.g. 'add')
	 *	@param		array		$input				Array of built Input Fields
	 *	@return		array
	 */
	public function buildFields( $fileName, $formName, $languageFile, $languageSection, $inputs )
	{
		$cal_count	= 0;
		$labels		= $this->words[$languageFile][$languageSection];

		$array	= array();
		$this->loadDefinition( $fileName , $formName );
		$fields	= $this->definition->getFields();
		if( count( $fields ) )
		{
			foreach( $fields as $field )
			{
				$data	= $this->definition->getField( $field );
				$suffix	= isset( $labels[$field."_suffix"] ) ? $labels[$field."_suffix"] : "";
				if( isset( $data['calendar'] ) )
				{
					if( $data['calendar']['component'] == "MonthCalendar" )
					{
						import( 'de.ceus-media.framework.krypton.view.component.MonthCalendar' );
						$cal	= new Framework_Krypton_View_Component_MonthCalendar();
						if( isset( $data['calendar']['range'] ) )
							$cal->setRange( $data['calendar']['range'] );
						if( isset( $data['calendar']['type'] ) )
							$cal->setType( $data['calendar']['type'] );
						if( isset( $data['calendar']['direction'] ) )
							$cal->setDirection( $data['calendar']['direction'] == "asc" );
						if( isset( $this->words['main']['months'] ) )
							$cal->setMonths( $this->words['main']['months'] );
						$name	= $data['input']['name'];
						$id1	= "mcal".$cal_count;
						$id2	= "mcal_opener".$cal_count;
						$cal	= $cal->buildCalendar($name, $id1, $id2, $id1 );
						$suffix	= $cal."<span class='suffix'>".$suffix."</span>";
						$cal_count++;
					}
					if( $data['calendar']['component'] == "DayCalendar" )
					{
						import( 'de.ceus-media.framework.krypton.view.component.DayCalendar' );
						$cal	= new Framework_Krypton_View_Component_DayCalendar();
				//		if( isset( $data['calendar']['range'] ) )
				//			$cal->setRange( $data['calendar']['range'] );
						if( isset( $data['calendar']['format'] ) )
							$cal->setFormat( $data['calendar']['format'] );
						if( isset( $data['calendar']['type'] ) )
							$cal->setType( $data['calendar']['type'] == "future" ? 1 : ( $data['calendar']['type'] == "past" ? -1 : 0 ) );
				//		if( isset( $data['calendar']['direction'] ) )
				//			$cal->setDirection( $data['calendar']['direction'] == "asc" );
						
				//		$cal->setLanguage( $data['calendar']['language'] );
						$idInput	= $data['input']['name'];
						$idOpener	= "dcal_".$data['input']['name'];
						$cal		= $cal->buildCalendar( $idInput, $idOpener );
						$suffix		= $cal."<span class='suffix'>".$suffix."</span>";
					}
				}
				$colspan	= $data['input']['colspan'] ? $data['input']['colspan'] : 1;
				$class	= 'field';
				if( $data['input']['type'] == "label" && $data['input']['style'] )
					$class = $data['input']['style'];

				$array['field_'.$field]	= $this->html->Field( $data['input']['name'], $inputs['input_'.$field], $class, $suffix, $colspan );
			}
		}
		else
			$this->messenger->noteError( "DefinitionView->buildLabels: no Fields defined for Form '".$formName."'." );
		return $array;
	}

	/**
	 *	Builds Labels and Input Fields of Form widthin Definition.
	 *	@access		public
	 *	@param		string		$fileName			Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@param		string		$formName			Name of Form within XML Definition File (e.g. 'addExample' )
	 *	@param		string		$languageFile		Name of Language File (e.g. 'example')
	 *	@param		string		$languageSection	Section in Language File (e.g. 'add')
	 *	@param		array		$values				Array of Input Values of defined Fields
	 *	@param		array		$sources			Array of Sources for defined Fields (e.g. Options for Selects)
	 *	@return		array
	 */
	public function buildForm( $fileName, $formName, $languageFile, $languageSection, $values = array(), $sources = array() )
	{
		$fileName	= str_replace( ".", "/", $fileName );
		$this->definition->setForm( $formName );
		$inputs	= $this->buildInputs( $fileName, $formName, $languageFile, $languageSection, $values, $sources );
		$array	= $this->buildLabels( $fileName, $formName, $languageFile, $languageSection )
				+ $this->buildFields( $fileName, $formName, $languageFile, $languageSection, $inputs )
				+ $inputs;
		return (array)$array;
	}
	
	/**
	 *	Build Inputs of Form Fields.
	 *	@access		public
	 *	@param		string		$fileName			Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@param		string		$formName			Name of Form within XML Definition File (e.g. 'addExample' )
	 *	@param		string		$languageFile		Name of Language File (e.g. 'example')
	 *	@param		string		$languageSection	Section in Language File (e.g. 'add')
	 *	@param		array		$values				Array of available Input Field Values
	 *	@param		array		$sources			Array of Option Arrays for Select Boxes
	 *	@return		array
	 */
	public function buildInputs( $fileName, $formName, $languageFile, $languageSection, $values = array(), $sources = array() )
	{
		$request	= $this->registry->get( 'request' );
		$labels		= $this->words[$languageFile][$languageSection];

		$array	= array();
		$this->loadDefinition( $fileName, $formName );
		$fields	= $this->definition->getFields();
		foreach( $fields as $field )
		{
			$input = "";
			$data	= $this->definition->getField( $field );
			if( !isset( $values[$field] ) )
				$values[$field]	= "";
			if( !$values[$field] && $value	= $request->get( $data['input']['name'] ) )
				$values[$field]	= $value;

			if( $data['syntax']['mandatory'] )
				$data['input']['style']	.= " mandatory";
			if( $data['input']['type'] == "select" )
			{
//				$disabled	= ( isset( $data['input']['disabled'] ) && $data['input']['disabled'] ) ? 'disabled' : false;
				$submit	= isset( $data['input']['submit'] ) && $data['input']['submit'] ? $formName : false;
				if( $data['input']['options'] )
				{
					if( preg_match( "@[^:]+(:)[^:]+@", $data['input']['options'] ) )
					{
						$parts	= explode( ":", $data['input']['options'] );
						$this->loadLanguage( $parts[0] );
						$options	= $this->words[$parts[0]][$data['input']['options']];
					}
					else
						$options	= $this->words[$languageFile][$data['input']['options']];
					$options['_selected']	= $values[$field];
					$input	= $this->html->Select( $data['input']['name'], $options, $data['input']['style'], false, $submit );
				}
				else if( isset( $sources[$data['input']['source']] ) )
					$input	= $this->html->Select( $data['input']['name'], $sources[$data['input']['source']], $data['input']['style'], false, $submit );
				else
					$input	= $this->html->Select( $data['input']['name'], "", $data['input']['style'], false, $submit );
			}
			else if( $data['input']['type'] == "textarea" )
			{
				$input = $this->html->TextArea( $data['input']['name'], $values[$field], $data['input']['style'], $data['input']['disabled'], $data['input']['validator'] );
			}
			else if( $data['input']['type'] == "input" )
			{
				$maxlength	= isset( $data['syntax']['maxlength'] ) ? $data['syntax']['maxlength'] : 0;
				$validator	= isset( $data['input']['validator'] ) ? $data['input']['validator'] : "";
				$style		= isset( $data['input']['style'] ) ? $data['input']['style'] : "";
				$input = $this->html->Input( $data['input']['name'], $values[$field], $style, false, false, $maxlength, $validator );
			}
			else if( $data['input']['type'] == "password" )
				$input = $this->html->Password( $data['input']['name'], $data['input']['style'] );
			else if( $data['input']['type'] == "checkbox" )
				$input = $this->html->CheckBox( $data['input']['name'], 1, $values[$field], $data['input']['style'] );
			else if( $data['input']['type'] == "checklabel" )
			{
				$label	= $this->getLabel( $data, $field, $labels );
				$input = $this->html->CheckLabel( $data['input']['name'], $values[$field], $label, $data['input']['style'] );
			}
			else if( $data['input']['type'] == "file" )
				$input = $this->html->File( $data['input']['name'], '', $data['input']['style'] );
			else if( $data['input']['type'] == "label" )
			{
				$value	= $values[$field];
				if( $data['input']['options'] )
				{
					$options	= $this->words[$languageFile][$data['input']['options']];
					$value		= $options[$value];
				}
				if( isset( $data['input']['style'] ) && $data['input']['style'] )
					$input	= '<span class="'.$data['input']['style'].'">'.$value.'</span>';
				else
					$input	= '<span>'.$value.'</span>';
			}
/*			else if( $data['input']['type'] == "checklabel" )
			{
				$checkbox = $gui->elements->CheckBox( $name, $value, $name );
				$field = $gui->elements->CheckLabel( $checkbox, $source, $class, $name, $maxlength );
			}
*/			else if( $data['input']['type'] == "radio" )
			{
				$input = $this->html->Radio( $name, $value, array( $source ) );
			}
			else if( $data['input']['type'] == "radiogroup" )
			{
				if( $data['input']['options'] )
				{
					$options	= $this->words[$languageFile][$data['input']['options']];
					$options['_selected']	= $values[$field];
					$input = $this->html->RadioGroup( $data['input']['name'], $options, $data['input']['style'] );
				}
				else
					$input = $this->html->RadioGroup( $data['input']['name'], $sources[$data['input']['source']], $data['input']['style'] );
			}
			else if( $data['input']['type'] == "radiolist" )
			{
				if( $data['input']['options'] )
				{
					$options	= $this->words[$languageFile][$data['input']['options']];
					$options['_selected']	= $values[$field];
					$input = $this->html->RadioList( $data['input']['name'], $options, $data['input']['style'] );
				}
				else
					$input = $this->html->RadioList( $data['input']['name'], $sources[$data['input']['source']], $data['input']['style'] );
			}

/*			else if( $data['input']['type'] == "selectlabel" )
			{
				$options	= $this->words[$languageFile][$data['input']['source']];
				$input = $options[$values[$field]];
			}
*/			
			
/*			else if( $data['input']['type'] == "radios" && $source )
			{
				$words = $language->getWords( $front, $source );
				foreach( $words as $word_key => $word_value )
				{
					unset( $words[$word_key] );
					$word_key = substr( $word_key, 4 );
					$new_words[$word_key] = $word_value;
				}
				$field = $gui->elements->Radio( $name, $value, $new_words, $class, false, false, $allow, $tabindex, $disabled || $radio_dis );
*/			
			$array['input_'.$field]	= $input;
		}
		return $array;
	}

	/**
	 *	Build Label of Field.
	 *	@access		public
	 *	@param		string		$field			Name of Field
	 *	@return		array
	 *	@todo		TO BE CHANGED in next Version (check Usage with LogFile)
	 *	@todo		sense clear: create simple label, usage unclear: no form, no lan?
	 */
	public function buildLabel( $field )
	{
		$data	= $this->definition->getField( $field );
		$key	= $data['input']['label'] ? $data['input']['label'] : $field;
		$label	= $this->html->Label( $data['input']['name'], $labels[$key] );
		return $label;
	}
	
	/**
	 *	Build Labels of Form Fields.
	 *	@access		public
	 *	@param		string		$fileName			Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@param		string		$formName			Name of Form within XML Definition File (e.g. 'addExample' )
	 *	@param		string		$languageFile		Name of Language File (e.g. 'example')
	 *	@param		string		$languageSection	Section in Language File (e.g. 'add')
	 *	@return		array
	 */
	public function buildLabels( $fileName, $formName, $languageFile, $languageSection )
	{
		$labels		= $this->words[$languageFile][$languageSection];

		$array	= array();
		$this->loadDefinition( $fileName, $formName );
		$fields	= $this->definition->getFields();
		if( count( $fields ) )
		{
			foreach( $fields as $field )
			{
				$data	= $this->definition->getField( $field );
				$classes	= array( 'label');
				if( isset( $data['syntax']['mandatory'] ) && $data['syntax']['mandatory'] )
					$classes[]	= "mandatory";
				$classes	= implode( " ", $classes );
				$label	= $this->getLabel( $data, $field, $labels );
				$label	= $this->html->Label( $data['input']['name'], $label, $classes );
				$array['label_'.$field]	= $label;
			}
		}
		else
			$this->messenger->noteError( "DefinitionView->buildLabels: no Fields defined for Form '".$formName."'." );
		return $array;
	}

	/**
	 *	Returns Field Label including Acronym, ToolTip or HelpHover if available.
	 *	@access		public
	 *	@param		array		$data			Field Definition
	 *	@param		string		$field			Field Key
	 *	@param		array		$labels			Label Pairs
	 *	@return		string
	 */
	public function getLabel( $data, $field, $labels )
	{
		$key	= $data['input']['label'] ? $data['input']['label'] : $field;
		if( isset( $labels[$key] ) )
		{
			$label	= $labels[$key];
			if( isset( $labels[$key."_acronym"] ) )
				$label	= $this->html->Acronym( $label, $labels[$key."_acronym"] );
			else if( isset( $labels[$key."_tip"] ) )
				$label	= $this->html->ToolTip( $label, $labels[$key."_tip"] );
			else if( isset( $labels[$key."_hover"] ) )
				$label	= $this->html->HelpHover( $label, $labels[$key."_hover"] );
			return $label;
		}
		else
			$this->messenger->noteError( "Label for Field '".$field."' is not defined" );
	}

	/**
	 *	Runs Validation of Field Definitions againt Request Input and creates Error Messages.
	 *	@access		protected
	 *	@param		string		$fileName			Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@param		string		$formName			Name of Form within XML Definition File (e.g. 'addExample' )
	 *	@return		void
	 */
	protected function loadDefinition( $fileName, $formName )
	{
		$this->definition->setForm( $formName );
		$this->definition->setPrefix( $this->prefix );
		$this->definition->loadDefinition( $fileName );
	}
}
?>