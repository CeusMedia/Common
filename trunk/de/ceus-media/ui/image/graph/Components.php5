<?php
/**
 *	Components to set up Graph by set Configuration and Graph Data.
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
 *	@package		ui.image.graph
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.04.2008
 *	@version		0.1
 */
/**
 *	Components to set up Graph by set Configuration and Graph Data.
 *	@package		ui.image.graph
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.04.2008
 *	@version		0.1
 */
class UI_Image_Graph_Components
{
	/**
	 *	Returns the interpreted Value of a Configuration Parameter.
	 *	@access		public
	 *	@static
	 *	@param		array		$config			Configuration Data
	 *	@param		string		$key			Parameter Key
	 *	@param		mixed		$default		Default Value to set if empty or not set
	 *	@return		mixed
	 */
	public static function getConfigValue( $config, $key, $default = NULL )
	{
		if( !isset( $config[$key] ) )
			return $default;
		$value	= trim( $config[$key] );
		if( preg_match( '/^[A-Z_]+$/', $value ) )
			$value	= constant( $value );
		else if( preg_match( '/^[0-9]+$/', $value ) )
			$value	= (int) $value;
		else if( preg_match( '/^[0-9.]+$/', $value ) )
			$value	= (real) $value;
		else if( empty( $value ) )
			return $default;
		return $value;
	}
	
	/**
	 *	Returns a Configuration Subset with a Prefix
	 *	@access		protected
	 *	@static
	 *	@param		array		$config			Configuration Data
	 *	@param		string		$prefix			Parameter Prefix, must end mit a Point
	 *	@return		array
	 */
	public static function getSubConfig( $config, $prefix )
	{
		$data	= array();
		$length	= strlen( $prefix );
		foreach( $config as $key => $value )
			if( substr( $key, 0, $length ) == $prefix )
				$data[substr( $key, $length )]	= $value;
		return $data;
	}

	/**
	 *	Adds an Axis to the JpGraph Graph Object.
	 *	@access		public
	 *	@static
	 *	@param		Graph		$object			JpGraph Graph Object
	 *	@param		array		$config			Configuration Data
	 *	@param		array		$data			Graph Data
	 *	@return		void
	 */
	public static function setAxis( $object, $config, $data )
	{
		$object->setColor( self::getConfigValue( $config, 'color', 'black' ) );
		$object->setWeight( self::getConfigValue( $config, 'weight', 1 ) );
		$object->SetTextLabelInterval( self::getConfigValue( $config, 'label.interval', 1 ) );
		$object->SetLabelAngle( self::getConfigValue( $config, 'label.angle', 0 ) );
		self::setTitle( $object->title, self::getSubConfig( $config, 'title.' ) );
		self::setFont( $object, self::getSubConfig( $config, 'label.' ) );

		$labels		= self::getConfigValue( $config, 'label.data' );
		if( $labels )
		{
			if( !isset( $data[$labels] ) )
				throw new Exception( 'Data source "'.$labels.'" is not available.' );
			$object->setTickLabels( $data[$labels] );
		}
		$grace		= self::getConfigValue( $config, 'scale.grace', 0 );
		if( $grace )
			$object->scale->setGrace( $grace );
	}
	
	/**
	 *	Sets the Font of a JpGraph Object.
	 *	@access		public
	 *	@static
	 *	@param		mixed		$object			JpGraph Object
	 *	@param		array		$config			Configuration Data
	 *	@return		void
	 */
	public static function setFont( $object, $config )
	{
		$name	= self::getConfigValue( $config, 'font.name' );
		$type	= self::getConfigValue( $config, 'font.type', FS_NORMAL );
		$size	= self::getConfigValue( $config, 'font.size', 10 );
		if( $name )
			$object->setFont( $name, $type, $size );
	}

	/**
	 *	Sets the Frame of a JpGraph Graph Object.
	 *	@access		public
	 *	@static
	 *	@param		Graph		$object			JpGraph Graph Object
	 *	@param		array		$config			Configuration Data
	 *	@return		void
	 */
	public static function setFrame( $object, $config )
	{
		$frameShow	= self::getConfigValue( $config, 'frame.show', FALSE );
		$frameColor	= self::getConfigValue( $config, 'frame.color', array( 0, 0, 0 ) );
		$frameWidth	= self::getConfigValue( $config, 'frame.width', 1 );
		$object->setFrame( $frameShow, $frameColor, $frameWidth );
#$graph->SetFrameBevel(12,true,'black'); 		
	}

	/**
	 *	Sets the Grid of a JpGraph Grid Object.
	 *	@access		public
	 *	@static
	 *	@param		Grid		$object			JpGraph Grid Object
	 *	@param		array		$config			Configuration Data
	 *	@return		void
	 */
	public static function setGrid( $object, $config )
	{
		$showMajor	= self::getConfigValue( $config, "show.major", FALSE );
		$showMinor	= self::getConfigValue( $config, "show.minor", FALSE );
		$object->show( $showMajor, $showMinor );
		$object->setWeight( self::getConfigValue( $config, "weight", 1 ) );
		$object->setLineStyle( self::getConfigValue( $config, "line.style", 'solid' ) );
		if( $showMajor || $showMinor )
		{
			$color1	= self::getConfigValue( $config, "color.1", FALSE );
			$color2	= self::getConfigValue( $config, "color.2", FALSE );
			$object->setColor( $color1, $color2 );
			$fill	= self::getConfigValue( $config, "fill.show", FALSE );
			if( $fill )
			{
				$color1	= self::getConfigValue( $config, "fill.color.1" );
				$color2	= self::getConfigValue( $config, "fill.color.2" );
				$object->setFill( $fill, $color1, $color2 );
			}
		}
	}

	/**
	 *	Sets the Grid of a JpGraph Graph Object.
	 *	@access		public
	 *	@static
	 *	@param		Graph		$object			JpGraph Graph Object
	 *	@param		array		$config			Configuration Data
	 *	@return		void
	 */
	public static function setLegend( $graph, $config )
	{
		if( !self::getConfigValue( $config, 'show' ) )
		{
			$graph->legend->hide();
			return;
		}
		
		$graph->legend->setLayout( self::getConfigValue( $config, 'layout', LEGEND_VERT ) );

		$shadowShow		= self::getConfigValue( $config, 'shadow.show', FALSE );
		$shadowWidth	= self::getConfigValue( $config, 'shadow.width', 2 );
		$shadowColor	= self::getConfigValue( $config, 'shadow.color', 'gray' );
		$graph->legend->setShadow( FALSE );
		if( $shadowShow )
			$graph->legend->setShadow( $shadowColor, $shadowWidth );

		$color		= self::getConfigValue( $config, 'color', 'black' );
		$frameColor	= self::getConfigValue( $config, 'frame.color', 'black' );
		$graph->legend->setColor( $color, $frameColor );
		$graph->legend->setFrameWeight( self::getConfigValue( $config, 'frame.weight', 1 ) ); 

		$posX		= self::getConfigValue( $config, 'pos.x', 0 );
		$posY		= self::getConfigValue( $config, 'pos.y', 0 );
		$posAlignH	= self::getConfigValue( $config, 'pos.align.h', 'right' );
		$posAlignV	= self::getConfigValue( $config, 'pos.align.v', 'top' );
		$graph->legend->pos( $posX, $posY, $posAlignH, $posAlignV );

		$fillColor	= self::getConfigValue( $config, 'fill.color', NULL );
		$graph->legend->setFillColor( $fillColor );

		self::setFont( $graph->legend, $config );
	}

	/**
	 *	Sets the Marks of a JpGraph Plot Object.
	 *	@access		public
	 *	@static
	 *	@param		mixed		$object			JpGraph Plot Object
	 *	@param		array		$config			Configuration Data
	 *	@return		void
	 */
	public static function setMark( $object, $config )
	{
		$show		= self::getConfigValue( $config, 'mark.show' );
		$type		= self::getConfigValue( $config, 'mark.type' );
		$file		= self::getConfigValue( $config, 'mark.file' );
		$scale		= self::getConfigValue( $config, 'mark.scale' );
		$width		= self::getConfigValue( $config, 'mark.width' );
		$fillColor	= self::getConfigValue( $config, 'mark.fill.color' );

		$object->mark->show( $show );
		if( $type && $file && $scale )
			$object->mark->setType( $type, $file, $scale );
		else if( $type && $file )
			$object->mark->setType( $type, $file );
		elseif( $type )
			$object->mark->setType( $type );
		if( $width )
			$object->mark->setWidth( $width );
		if( $fillColor )
			$object->mark->setFillColor( $fillColor );
	}
	
	/**
	 *	Sets Shadow of a JpGraph Object.
	 *	@access		public
	 *	@static
	 *	@param		mixed		$object			JpGraph Object
	 *	@param		array		$config			Configuration Data
	 *	@return		void
	 */
	public static function setShadow( $object, $config )
	{
		$shadowShow		= self::getConfigValue( $config, 'shadow.show', TRUE );
		$shadowWidth	= self::getConfigValue( $config, 'shadow.width', 5 );
		$shadowColor	= self::getConfigValue( $config, 'shadow.color', array(102,102,102) );
		$object->setShadow( $shadowShow, $shadowWidth, $shadowColor );
	}

	/**
	 *	Sets Subtitle of a JpGraph Graph Object.
	 *	@access		public
	 *	@static
	 *	@param		Graph		$graph			JpGraph Graph Object
	 *	@param		array		$config			Configuration Data
	 *	@return		void
	 */
	public static function setSubTitle( $graph, $config )
	{
		if( empty( $config['subtitle'] ) )
			return;
		$graph->subtitle->Set( $config['subtitle'] );
		self::setFont( $graph->subtitle, self::getSubConfig( $config, 'subtitle.' ) );
	}

	/**
	 *	Sets Title of a JpGraph Graph Object.
	 *	@access		public
	 *	@static
	 *	@param		Graph		$graph			JpGraph Graph Object
	 *	@param		array		$config			Configuration Data
	 *	@return		void
	 */
	public static function setTitle( $graph, $config )
	{
		if( empty( $config['title'] ) )
			return;
		$graph->title->Set( $config['title'] );
		self::setFont( $graph->title, self::getSubConfig( $config, 'title.' ) );
	}

	/**
	 *	Sets Value Style of a JpGraph Plot Object.
	 *	@access		public
	 *	@static
	 *	@param		Graph		$object			JpGraph Plot Object
	 *	@param		array		$config			Configuration Data
	 *	@return		void
	 */
	public static function setValue( $object, $config )
	{
		$show		= self::getConfigValue( $config, 'value.show' );
		$weight		= self::getConfigValue( $config, 'value.weight' );
		$fillColor	= self::getConfigValue( $config, 'value.fill.color' );
		$object->value->show( (bool) $show );
		if( $show )
			self::setFont( $object, $config );
	}
}
?>