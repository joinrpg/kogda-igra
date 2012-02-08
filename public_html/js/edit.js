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

function update_emails()
{
if (XMLHttpRequest && document.getElementById('email').value.length == 0)
{
		var req = new XMLHttpRequest();
		var dropdown = document.getElementById('allrpg_emails');
		var uri = 'http://inf.allrpg.info/kogdaigra.php?game_id=' + current_allrpg_value();
		req.open ('GET', uri, true);
		req.onreadystatechange = function (aEvt) {
			if (req.readyState == 4)
			{
				while (dropdown.options.length> 0)
				{
					dropdown.removeChild(dropdown.lastChild);
				}
				if (req.status == 200)
				{
          
          var str = req.responseText;
					var result = JSON.parse(str);
					if (result.length > 0)
					{
              var opt0 = document.createElement('option');
              opt0.setAttribute('value', 0);
              opt0.innerHTML = 'Выберите ...';
              dropdown.appendChild(opt0);
            for (var i = 0; i < result.length; i++)
            {
              var opt = document.createElement('option');
              opt.setAttribute('value', result[i].email);
              var duty = result[i].duty.join(',');
              opt.innerHTML = result[i].email + ' ' + result[i].name;
              dropdown.appendChild(opt);
            }
            dropdown.style.visibility = "visible";
					}
					else
					{
					dropdown.style.visibility = "hidden";
					}
				}
				else
				{
					dropdown.style.visibility = "hidden";
				}
			}
		}
		req.send();
	}
}


function update_allrpg_info(datestart, dateend)
{
  if (XMLHttpRequest)
	{
		var req = new XMLHttpRequest();
		var dropdown = document.getElementById('allrpg_games');
		var uri = 'http://inf.allrpg.info/kogdaigra.php?datestart=' + get_date_string(datestart) +'&datefinish='  + get_date_string(dateend);
		req.open ('GET', uri, true);
		req.onreadystatechange = function (aEvt) {
			if (req.readyState == 4)
			{
				while (dropdown.options.length> 0)
				{
					dropdown.removeChild(dropdown.lastChild);
				}
				if (req.status == 200)
				{
          
          var str = req.responseText;
					var result = JSON.parse(str);
					if (result.length > 0)
					{
              var opt0 = document.createElement('option');
              opt0.setAttribute('value', 0);
              opt0.innerHTML = 'Выберите ...';
              dropdown.appendChild(opt0);
            for (var i = 0; i < result.length; i++)
            {
              var opt = document.createElement('option');
              opt.setAttribute('value', result[i].allrpg_info_id);
              opt.innerHTML = result[i].allrpg_info_name;
              if (result[i].allrpg_info_id == current_allrpg_value())
              {
                opt.setAttribute('selected', 'selected');
                updateAllrpgInfoLink();
              }
              dropdown.appendChild(opt);
            }
            dropdown.style.visibility = "visible";
					}
					else
					{
					dropdown.style.visibility = "hidden";
					}
				}
				else
				{
					dropdown.style.visibility = "hidden";
				}
			}
		}
		req.send();
	}
}

function ask_if_delete()
{
  return window.confirm('Действительно удалить?');
}

function updateAllrpgInfoLink()
{
  
   var allrpgInfoLink = document.getElementById('allrpg_info_link');
   allrpgInfoLink.href = "http://inf.allrpg.info/events/" + current_allrpg_value().toString()  + "/";
   allrpgInfoLink.style.visibility = "visible";
   update_emails();
}

function current_allrpg_value()
{
   var allrpgInfo = document.getElementById('allrpg_info_id');
   return parseInt(allrpgInfo.value);
}


function updateAllrpgInfo()
{
  	var dropdown = document.getElementById('allrpg_games');
    var selected = dropdown.options[dropdown.selectedIndex].value; 
    if (selected >0)
    {
      var allrpgInfo = document.getElementById('allrpg_info_id');
      allrpgInfo.value = selected;
      updateAllrpgInfoLink();
    }
}

function get_date_string(date)
{
  return date.getFullYear() + '-' + (date.getMonth()+1) + '-' + date.getDate();
}

function update_time_placeholder(time_prefix, time2_prefix)
{
  var date = get_time_value (time_prefix);

  var placeholder = document.getElementById(time_prefix + '_placeholder');
	placeholder.innerHTML = weekday[date.getDay()];

	var length = document.getElementById(time2_prefix).value;
	
	if (length.length > 0)
	{
		var end_date = date;
		end_date.setDate(date.getDate() + parseInt(length) - 1);

		var placeholder2 = document.getElementById(time2_prefix + '_placeholder');
		placeholder2.innerHTML = weekday[date.getDay()];
	}
  
  update_allrpg_info (date, end_date);
}

function update_region_name(sender)
{

	update_subregion(get_select_value(sender));
}

function update_subregion(subregion_id)
{

	var placeholder = document.getElementById('region_placeholder');


	placeholder.innerHTML = '<strong>Раздел календаря:</strong> ' + get_region_name (subregion_id);

	var polygonsElement = document.getElementById('polygon_select');

	var selected = polygonsElement.options[polygonsElement.selectedIndex].value;


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