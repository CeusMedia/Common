<?php
import( 'de.ceus-media.framework.krypton.core.View' );
import( 'de.ceus-media.file.log.LogFile' );
import( 'de.ceus-media.framework.krypton.logic.ValidationError' );
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
	 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
	 *	@since		18.06.2006
	 *	@version	0.1
	 */
	public function __construct( $useWikiParser = false )
	{
		parent::__construct( $useWikiParser );
		$this->definition	= $this->registry->get( 'definition' );
	}

	/**
	 *	Build Label of Field.
	 *	@access		public
	 *	@param		string		$field			Name of Field
	 *	@return		array
	 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
	 *	@since		18.06.2006
	 *	@version		0.1
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
	 *	@param		string		$file				Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@param		string		$form			Name of Form within XML Definition File (e.g. 'addExample' )
	 *	@param		string		$lan_file			Name of Language File (e.g. 'example')
	 *	@param		string		$lan_section		Section in Language File (e.g. 'add')
	 *	@return		array
	 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
	 *	@since		18.06.2006
	 *	@version		0.1
	 */
	public function buildLabels( $file , $form, $lan_file, $lan_section )
	{
		$request	= $this->registry->get( 'request' );
		$labels		= $this->words[$lan_file][$lan_section];

		$array	= array();
		$this->loadDefinition( $file, $form );
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
			$this->messenger->noteError( "DefinitionView->buildLabels: no Fields defined for Form '".$form."'." );
		return $array;
	}
	
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

	public function buildInputs( $file , $form, $lan_file, $lan_section, $values = array(), $sources = array() )
	{
		$request	= $this->registry->get( 'request' );
		$labels		= $this->words[$lan_file][$lan_section];

		$array	= array();
		$this->loadDefinition( $file , $form );
		$fields	= $this->definition->getFields();
		foreach( $fields as $field )
		{
			$input = "";
			$data	= $this->definition->getField( $field );
			if( !isset( $values[$field] ) )
				$values[$field]	= "";
			if( !$values[$field] && $value	= $request->get( $data['input']['name'] ) )
				$values[$field]	= $value;
			if( $data['input']['type'] == "select" )
			{
//				$disabled	= ( isset( $data['input']['disabled'] ) && $data['input']['disabled'] ) ? 'disabled' : false;
				$submit	= isset( $data['input']['submit'] ) && $data['input']['submit'] ? $form : false;
				if( $data['input']['options'] )
				{
					$options	= $this->words[$lan_file][$data['input']['options']];
					$options['_selected']	= $values[$field];
					$input	= $this->html->Select( $data['input']['name'], $options, $data['input']['style'], false, $submit );
				}
				else if( isset( $sources[$data['input']['source']] ) )
					$input	= $this->html->Select( $data['input']['name'], $sources[$data['input']['source']], $data['input']['style'], false, $submit );
				else
					$input	= $this->html->Select( $data['input']['name'], "", $data['input']['style'], false, $submit );

/*				if( is_string( $source ) )
				{
				$words = $language->getWords( $front, $source );
				$ins_options = "";
				foreach( $words as $word_key => $word_value )
				{
					$word_key = substr( $word_key, 4 );
					if( substr( $word_key, 0, 1 ) == "#" ) continue;
					if( $value == $word_key ) $ins_selected = " selected";
					else $ins_selected = "";
					$ins_options .= "<option value='".$word_key."'$ins_selected>".$word_value;
				}
				$field = $gui->elements->Select( $name, $ins_options, $class, $name, false, false, false, $tabindex );
*/			}
			else if( $data['input']['type'] == "textarea" )
				$input = $this->html->TextArea( $data['input']['name'], $values[$field], $data['input']['style'], false, $data['input']['validator'] );
			else if( $data['input']['type'] == "input" )
			{
				$maxlength	= isset( $data['syntax']['maxlength'] ) ? $data['syntax']['maxlength'] : 0;
				$validator	= isset( $data['input']['validator'] ) ? $data['input']['validator'] : "";
				$style		= isset( $data['input']['style'] ) ? $data['input']['style'] : "";
				$input = $this->html->Input( $data['input']['name'], $values[$field], $style, false, false, false, $maxlength, $validator );
			}
			else if( $data['input']['type'] == "password" )
#				$input = $this->html->Password( $data['input']['name'], '', $data['input']['style'] );
				$input = $this->html->Password( $data['input']['name'], $values[$field], $data['input']['style'] );
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
					$options	= $this->words[$lan_file][$data['input']['options']];
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
					$options	= $this->words[$lan_file][$data['input']['options']];
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
					$options	= $this->words[$lan_file][$data['input']['options']];
					$options['_selected']	= $values[$field];
					$input = $this->html->RadioList( $data['input']['name'], $options, $data['input']['style'] );
				}
				else
					$input = $this->html->RadioList( $data['input']['name'], $sources[$data['input']['source']], $data['input']['style'] );
			}

/*			else if( $data['input']['type'] == "selectlabel" )
			{
				$options	= $this->words[$lan_file][$data['input']['source']];
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

	public function buildFields( $file , $form, $lan_file, $lan_section, $inputs )
	{
		$cal_count	= 0;
		$request	= $this->registry->get( 'request' );
		$labels		= $this->words[$lan_file][$lan_section];

		$array	= array();
		$this->loadDefinition( $file , $form );
		$fields	= $this->definition->getFields();
		if( count( $fields ) )
		{
			foreach( $fields as $field )
			{
				$data	= $this->definition->getField( $field );
				if( isset( $data['calendar'] ) )
				{
					if( $data['calendar']['component'] == "MonthCalendar" )
					{
						require_once( "classes/view/component/MonthCalendar.php5" );
						$cal	= new View_Component_MonthCalendar();							if( isset( $data['calendar']['range'] ) )
							$cal->setRange( $data['calendar']['range'] );
						if( isset( $data['calendar']['type'] ) )
							$cal->setType( $data['calendar']['type'] );
						if( isset( $data['calendar']['direction'] ) )
							$cal->setDirection( $data['calendar']['direction'] == "asc" );
						$name	= $data['input']['name'];
						$id1	= "mcal".$cal_count;
						$id2	= "mcal_opener".$cal_count;
						$cal	= $cal->buildCalendar($name, $id1, $id2, $id1 );
						$inputs['input_'.$field]	.= $cal;
						$cal_count++;
					}
					if( $data['calendar']['component'] == "DayCalendar" )
					{
						require_once( "classes/view/component/DayCalendar.php5" );
						$cal	= new View_Component_DayCalendar();
				//		if( isset( $data['calendar']['range'] ) )
				//			$cal->setRange( $data['calendar']['range'] );
						if( isset( $data['calendar']['format'] ) )
							$cal->setFormat( $data['calendar']['format'] );
						if( isset( $data['calendar']['type'] ) )
							$cal->setType( $data['calendar']['type'] );
				//		if( isset( $data['calendar']['direction'] ) )
				//			$cal->setDirection( $data['calendar']['direction'] == "asc" );
						
				//		$cal->setLanguage( $data['calendar']['language'] );
						$id_input	= $data['input']['name'];
						$id_opener	= "dcal_".$data['input']['name'];
						$cal	= $cal->buildCalendar( $id_input, $id_opener );
						$inputs['input_'.$field]	.= $cal;
					}
				}
				$suffix	= isset( $labels[$field."_suffix"] ) ? $labels[$field."_suffix"] : "";
				$colspan	= $data['input']['colspan'] ? $data['input']['colspan'] : 1;
				$class	= 'field';
				if( $data['input']['type'] == "label" && $data['input']['style'] )
					$class = $data['input']['style'];

				$array['field_'.$field]	= $this->html->Field( $data['input']['name'], $inputs['input_'.$field], $class, $suffix, $colspan );
			}
		}
		else
			$this->messenger->noteError( "DefinitionView->buildLabels: no Fields defined for Form '".$form."'." );
		return $array;
	}

	/**
	 *	Builds Labels and Input Fields of Form widthin Definition.
	 *	@access		public
	 *	@param		string		$file				Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@param		string		$form				Name of Form within XML Definition File (e.g. 'addExample' )
	 *	@param		string		$lan_file			Name of Language File (e.g. 'example')
	 *	@param		string		$lan_section		Section in Language File (e.g. 'add')
	 *	@param		array		$values				Array of Input Values of defined Fields
	 *	@param		array		$sources			Array of Sources for defined Fields (e.g. Options for Selects)
	 *	@return		array
	 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
	 *	@since		18.06.2006
	 *	@version		0.1
	 *	@todo		TO BE DELETED in next Version
	 */
	public function buildForm( $file , $form, $lan_file, $lan_section, $values = array(), $sources = array() )
	{
		$this->definition->setForm( $form );
		$inputs	= $this->buildInputs( $file, $form, $lan_file, $lan_section, $values, $sources );
		$array	= $this->buildLabels( $file, $form, $lan_file, $lan_section )
				+ $this->buildFields( $file, $form, $lan_file, $lan_section, $inputs )
				+ $inputs;
		return (array)$array;
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Runs Validation of Field Definitions againt Request Input and creates Error Messages.
	 *	@access		protected
	 *	@param		string		$file				Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@return		void
	 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
	 *	@since		18.06.2006
	 *	@version		0.1
	 */
	protected function loadDefinition( $file , $form )
	{
		$this->definition->setForm( $form );
		$this->definition->setPrefix( $this->prefix );
		$this->definition->loadDefinition( $file );
	}
	
	function buildTemplateSkeleton( $file, $form )
	{
		$this->loadDefinition( $file , $form );
		$fields	= $this->definition->getFields();
		if( count( $fields ) )
		{
//			print_m( $fields );
//			die;
			$lines[]	= '<?php
/**
 *	Template.
 *	@package		MV2_Prototype.car
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2007
 *	@version		0.1
 */
return "
<h2>".$heading."</h2>
".$form."
<table class=\"panel\" cellspacing=\"0\" width=\"100%\">
  ".$caption."
  ".$colgroup."';
			foreach( $fields as $field )
			{

				$lines[]	= '  <tr>".	$label_'.$field.'.		$field_'.$field.'.		"</tr>';
			}
			$lines[]	= '
  <tr>".	$field_button_cancel.	$field_button_edit.	"</tr>
</table>
</form>";
?>';			
			$lines	= implode( "\n", $lines );
		
			
			$fp	= fopen( "skeleton_".$form.".phpt", "w" );
			fputs( $fp, $lines );
			fclose( $fp );
		}		
	}
}
?>
