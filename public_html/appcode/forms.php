<?php
function show_tb ($label, $name, $length, $value, $tb_type = 'text', $required = false, $list = '', $preinput_text = '', $postinput_text = '')
{
	$required = $required ? ' required="required" ' : '';
	$value = htmlspecialchars ($value);
	if ($value && $tb_type == 'uri')
	{
		$label = "<a href=\"$preinput_text$value$postinput_text\">$label</a>";
	}
	echo "<tr><td><label><strong>$label</strong></label></td>";
	$maxlength = $length;
	$length = $length > 100 ? 100 : $length;
	if ($list)
	{
		$list = " list=\"$list\" autocomplete=off";
	}
	echo "<td>$preinput_text<input type=\"$tb_type\" name=\"$name\" id =\"$name\" maxlength=\"$maxlength\" size=\"$length\" value=\"$value\" $required$list>$postinput_text</td></tr>\n";
}

function show_required_tb ($label, $name, $length, $value, $tb_type = 'text')
{
  show_tb ($label, $name, $length, $value, $tb_type, TRUE);
}

function show_tb_with_list ($label, $name, $length, $value, $list)
{
	show_tb($label, $name, $length, $value, 'text', FALSE, $list);
}

	function show_uri_tb ($label, $name, $length, $value)
{
		show_tb ($label, $name, $length, $value, 'uri');
}

function show_hidden($name, $value)
{
  echo "<input type=\"hidden\" name=\"$name\" value=\"$value\" />";
}


	function write_option($value, $is_selected, $option_name)
	{
    $selected = $is_selected ? ' selected="selected"' : '';
		echo "<option value=\"$value\"$selected>$option_name</option>";
	}
  
  	function show_dd ($label, $name, $value)
	{
		echo "<tr><td><label><strong>$label</strong></label></td>";
		
		echo "<td>";
		show_dropdown($name, $value);
		echo "</td>";
	}
	
	function show_dropdown($name, $value = 0)
	{
	  show_dropdown_with_data($name, get_array ($name), $value);
	}
	
	function show_dropdown_with_data($name, $array, $value = 0)
	{
    echo "<select name=\"$name\" size=\"1\">";
		
	  foreach ($array as $key => $kname)
		{
			if ($key == $value)
				echo "<option value=\"$key\" selected=\"selected\">$kname</option>";
			else
				echo "<option value=\"$key\">$kname</option>";
		}
		echo "</select>";
	}


?>