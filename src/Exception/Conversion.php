<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Exception for conversion, translation or transformation errors.
 *	Based on runtime exception.
 *
 *	Copyright (c) 2011-2023 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Exception;

use CeusMedia\Common\Exception\Traits\Creatable as CreatableTrait;
use CeusMedia\Common\Exception\Traits\Descriptive as DescriptiveTrait;
use CeusMedia\Common\Exception\Traits\Jsonable as JsonableTrait;
use CeusMedia\Common\Exception\Traits\Renderable as RenderableTrait;
use CeusMedia\Common\Exception\Traits\Serializable as SerializableTrait;

/**
 *	Exception for conversion, translation or transformation errors.
 *	Based on runtime exception.
 *	@category		Library
 *	@package		CeusMedia_Common_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Conversion extends Runtime
{
	use CreatableTrait;
	use DescriptiveTrait;
	use JsonableTrait;
	use RenderableTrait;
	use SerializableTrait;
}