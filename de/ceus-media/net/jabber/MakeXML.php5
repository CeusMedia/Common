<?php
/***************************************************************************
	Class.Jabber.PHP v0.4.2
	(c) 2004 Nathan "Fritzy" Fritz
	http://cjphp.netflint.net *** fritzy@netflint.net

	This is a bugfix version, specifically for those who can't get 
	0.4 to work on Jabberd2 servers. 

	last modified: 24.03.2004 13:01:53 

 ***************************************************************************/
/*
	MakeXML::AddPacketDetails($string {string}[, $value {string/number}])
	MakeXML::BuildPacket([$array {array}])
*/
class MakeXML
{
	var $nodes;

	function MakeXML()
	{
		$nodes = array();
	}

	function AddPacketDetails($string, $value = NULL)
	{
		if (preg_match("/\(([0-9]*)\)$/i", $string))
		{
			$string .= "/[\"#\"]";
		}

		$temp = @explode("/", $string);

		for ($a = 0; $a < count($temp); $a++)
		{
			$temp[$a] = preg_replace("/^[@]{1}([a-z0-9_]*)$/i", "[\"@\"][\"\\1\"]", $temp[$a]);
			$temp[$a] = preg_replace("/^([a-z0-9_]*)\(([0-9]*)\)$/i", "[\"\\1\"][\\2]", $temp[$a]);
			$temp[$a] = preg_replace("/^([a-z0-9_]*)$/i", "[\"\\1\"]", $temp[$a]);
		}

		$node = implode("", $temp);

		// Yeahyeahyeah, I know it's ugly... get over it. ;)
		echo "\$this->nodes$node = \"" . htmlspecialchars($value) . "\";<br/>";
		eval("\$this->nodes$node = \"" . htmlspecialchars($value) . "\";");
	}

	function BuildPacket($array = NULL)
	{

		if (!$array)
		{
			$array = $this->nodes;
		}

		if (is_array($array))
		{
			array_multisort($array, SORT_ASC, SORT_STRING);

			foreach ($array as $key => $value)
			{
				if (is_array($value) && $key == "@")
				{
					foreach ($value as $subkey => $subvalue)
					{
						$subvalue = htmlspecialchars($subvalue);
						$text .= " $subkey='$subvalue'";
					}

					$text .= ">\n";

				}
				elseif ($key == "#")
				{
					$text .= htmlspecialchars($value);
				}
				elseif (is_array($value))
				{
					for ($a = 0; $a < count($value); $a++)
					{
						$text .= "<$key";

						if (!$this->_preg_grep_keys("/^@/", $value[$a]))
						{
							$text .= ">";
						}

						$text .= $this->BuildPacket($value[$a]);

						$text .= "</$key>\n";
					}
				}
				else
				{
					$value = htmlspecialchars($value);
					$text .= "<$key>$value</$key>\n";
				}
			}

			return $text;
		}
	}

	function _preg_grep_keys($pattern, $array)
	{
		while (list($key, $val) = each($array))
		{
			if (preg_match($pattern, $key))
			{
				$newarray[$key] = $val;
			}
		}
		return (is_array($newarray)) ? $newarray : FALSE;
	}
}
?>