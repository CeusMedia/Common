<?php
/**
 *	Parses vCard String to vCard Data Object.
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@package		file.vcard
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.7
 *	@version		$Id$
 *	@link			http://www.ietf.org/rfc/rfc2426.txt
 */
import( 'de.ceus-media.adt.VCard' );
/**
 *	Parses vCard String to vCard Data Object.
 *
 *	@category		cmClasses
 *	@package		file.vcard
 *	@uses			ADT_VCard
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.7
 *	@version		$Id$
 *	@link			http://www.ietf.org/rfc/rfc2426.txt
 *	@todo			PHOTO,BDAY,NOTE,LABEL,KEY,PRODID,MAILER,TZ
 *	@todo			Code Doc
 */
class File_VCard_Parser
{
	public function parse( $string )
	{
		$data	= new ADT_VCard;
		$status	= 0;
		$lines	= explode( "\n", $string );
		foreach( $lines as $line )
		{
			$parts		= explode( ":", $line );
			$key		= array_shift( $parts );
			$values		= implode( ":", $parts );
			$values		= explode( ";", $values );	

			$parts1		= explode( ";", $key );
			$type		= array_shift( $parts1 );

			$attributes	= implode( ";", $parts1 );
			$attributes	= $this->parseAttributes( $attributes );	

			$list	= array();
			for( $i=0; $i<10; $i++ )
				$list[$i]	= NULL;
			for( $i=0; $i<count( $values); $i++ )
				$list[$i]	= $values[$i];
			$values	= $list;
			
			switch( strtolower( $type ) )
			{
				case 'n':
					$data->setName(
						$values[0],
						$values[1],
						$values[2],
						$values[3],
						$values[4]
					);
					break;
				case 'fn':
					$data->setFormatedName( $values[0] );
					break;
				case 'email':
					$data->addEmail( $values[0], $attributes );
					break;
				case 'geo':
					$data->addGeoTag( $values[0], $values[1], $attributes );
					break;
				case 'org':
					$data->setOrganisation( $values[0], $values[1] );
					break;
				case 'title':
					$data->setTitle( $values[0] );
					break;
				case 'nickname':
					$data->addNickname( $values[0] );
					break;
				case 'role':
					$data->setRole( $values[0] );
					break;
				case 'url':
					$data->addUrl( $values[0], $attributes );
					break;
				case 'tel':
					$data->addPhone( $values[0], $attributes );
					break;
				case 'adr':
					$data->addAddress(
						$values[2],
						$values[1],
						$values[3],
						$values[4],
						$values[5],
						$values[6],
						$values[0],
						$attributes
					);
					break;
				
			}
		}
		return $data;
	}

	protected function parseAttributes( $string )
	{
		$parts	= explode( ";", $string );
		foreach( $parts as $part )
		{
			$parts1	= explode( "=", $part );
			$key	= array_shift( $parts1 );
			$values	= explode( ",", array_shift( $parts1 ) );
			foreach( $values as $value )
				$list[]	= $value;
		}
		return $list;
	}
}
?>