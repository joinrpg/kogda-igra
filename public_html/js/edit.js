window.onload = function() {
  update_allrpg_fields();
  updateSyncButton();
  var startyearElem = document.getElementById('startyear');
  var startYear = startyearElem ? startyearElem.value : get_time_value ('begin').getFullYear();
  var isUpdate = current_allrpg_value() > 0
  if (!is_new() && (isUpdate || startYear > 2013))
  {
   performSync (false);
  }
}

function is_new()
{
return !document.getElementById('id').value;
}

function set_email_field()
{
  	var dropdown = document.getElementById('allrpg_emails');
    var selected = dropdown.options[dropdown.selectedIndex].value; 
    if (selected)
    {
      var allrpgInfo = document.getElementById('email');
      allrpgInfo.value = selected;
    }

}

function clear_email_dropdown()
{
	var dropdown = document.getElementById('allrpg_emails');
	while (dropdown.options.length> 0)
	{
		dropdown.removeChild(dropdown.lastChild);
	}
	dropdown.style.display = "none";
}

function update_email_dropdown (masters)
{
	clear_email_dropdown();
	var dropdown = document.getElementById('allrpg_emails');

	if (masters.length > 0 && document.getElementById('email').value.length == 0)
	{
			var opt0 = document.createElement('option');
			opt0.setAttribute('value', 0);
			opt0.innerHTML = 'Выберите ...';
			dropdown.appendChild(opt0);
		for (var i = 0; i < masters.length; i++)
		{
			var opt = document.createElement('option');
			opt.setAttribute('value', masters[i].email);
			var duty = masters[i].duty.join(',');
			opt.innerHTML = masters[i].email + ' ' + masters[i].name;
			dropdown.appendChild(opt);
		}
		dropdown.style.display = 'inline';
	}
}

function update_text_field(fieldname, val)
{
	var textbox = document.getElementById(fieldname);
	
	if (textbox.value.length == 0)
	{
		textbox.value = val;
	}
}

function update_allrpg_fields()
{
}

function update_allrpg_info(datestart, dateend)
{
 
}

function ask_if_delete()
{
  return window.confirm('Действительно удалить?');
}

function updateAllrpgInfoLink()
{
}

function updateSyncButton()
{

}

function current_allrpg_value()
{
}

function current_id_value()
{
   var idElement = document.getElementById('id');
   return idElement.value | 0;
}


function performSync(manual)
{
}

function syncToAllrpg()
{
}

function updateAllrpgInfo()
{
}

function get_date_string(date)
{
	if (date) 
  {
		return date.getFullYear() + '-' + (date.getMonth()+1) + '-' + date.getDate();
	}
	else
	{
		return null;
	}
}

function length_change (time_prefix, time2_prefix)
{
	window.kogda_igra_default_value = false;
	update_time_placeholder (time_prefix, time2_prefix);
}

function update_time_placeholder (time_prefix, time2_prefix)
{
  var date = get_time_value (time_prefix);

  var placeholder = document.getElementById(time_prefix + '_placeholder');
	placeholder.innerHTML = weekday[date.getDay()];

	var length = document.getElementById(time2_prefix).value;
	
	if (length.length > 0 && !window.kogda_igra_default_value)
	{
		var end_date = get_end_date (date, length);
	}
	else
	{
		var end_date = date;
		length = 1;
		while (end_date.getDay() !=0)
		{
			end_date.setDate(end_date.getDate()+1);
			length++;
		}
		document.getElementById(time2_prefix).value = length;
		window.kogda_igra_default_value = true;
	}
		var placeholder2 = document.getElementById(time2_prefix + '_placeholder');
		placeholder2.innerHTML = weekday[date.getDay()];
  
  update_allrpg_info (date, end_date);

}

function get_end_date (date, length)
{
	var end_date = date;
	end_date.setDate(date.getDate() + parseInt(length) - 1);
	return end_date;
}

function update_region_name(sender)
{

	update_subregion(get_select_value(sender));
}

function update_subregion(subregion_id)
{
	var polygonsElement = document.getElementById('polygon_select');
	
	if (polygonsElement.selectedIndex > 0)
	{
		var selected = polygonsElement.options[polygonsElement.selectedIndex].value;
	}

	while (polygonsElement.options.length> 0)
	{
		polygonsElement.removeChild(polygonsElement.lastChild);
	}

	for (var i = 0; i < tbl_polygons.length; i++)
	{
		if (tbl_polygons[i].meta_polygon > 0 || (tbl_polygons[i].sub_region_id == subregion_id))
			{
				var id = tbl_polygons[i].polygon_id;
				var opt = document.createElement('option');
				opt.setAttribute('value', id);
				if (id == selected)
				{
					opt.setAttribute('selected', 'selected');
				}
				opt.innerHTML = tbl_polygons[i].polygon_name;
				polygonsElement.appendChild(opt);
			}
	}
}

function get_region_name(subregion_id)
{
	for (var i = 0; i < tbl_subregions.length; i++)
	{
		if (tbl_subregions[i].sub_region_id == subregion_id)
			return tbl_subregions[i].region_name;
	}
	return "";
}

function get_vis_for_region (polygon_id, subregion_id)
{
	for (var i = 0; i < tbl_polygons.length; i++)
	{
		if (tbl_polygons[i].polygon_id == polygon_id)
			return tbl_polygons[i].meta_polygon || (tbl_polygons[i].sub_region_id == subregion_id);
	}
	return false;
}

function get_time_value(time_prefix)
{
    var day = document.getElementById(time_prefix + '_day').value;
  var month = get_select_value(document.getElementById(time_prefix + '_month'));
  var year = get_select_value(document.getElementById(time_prefix + '_year'));
  var date = new Date();
  date.setFullYear(year, parseInt(month) - 1, day);
  return date;
}