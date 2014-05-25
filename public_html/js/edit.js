window.onload = function() {
  update_allrpg_fields();
  updateSyncButton();
  var startyearElem = document.getElementById('startyear');
  var startYear = startyearElem ? startyearElem.value : get_time_value ('begin').getFullYear();
  var isUpdate = current_allrpg_value() > 0
  if (isUpdate || startYear > 2013)
  {
   performSync (false);
  }
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
if (XMLHttpRequest)
{
		var req = new XMLHttpRequest();
		var uri = 'http://inf.allrpg.info/kogdaigra.php?game_id=' + current_allrpg_value();
		req.open ('GET', uri, true);
		req.onreadystatechange = function (aEvt) {
			if (req.readyState == 4)
			{
				clear_email_dropdown();
				if (req.status == 200)
				{
          var str = req.responseText;
					var result = JSON.parse(str);
					window.varResult = result;
					update_email_dropdown (result.masters);
					update_text_field ('mg', result.info.mg);
					update_text_field ('players_count', result.info.playernum);
					update_text_field ('name', result.info.name);
					var date = new Date(result.info.datestart);
					var end_date = new Date(result.info.datefinish);
					if (window.kogda_igra_default_value)
					{
						window.kogda_igra_default_value = false;
						document.getElementById('begin_day').value =  date.getDate();
						document.getElementById('begin_month').value =  date.getMonth() + 1;
						document.getElementById('begin_year').value =  date.getFullYear();
						document.getElementById('time').value =  ((end_date.getTime() - date.getTime()) / (1000 * 60 * 60 * 24)) +1 ;
						
						update_time_placeholder('begin', 'time');
					}
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
		if (!dropdown)
		{
			return;
		}
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
					var filtered_result = [];
					for (var i = 0; i < result.length; i++)
          {
            if (!result[i].kogdaigra_id || result[i].kogdaigra_id == current_id_value())
            {
              filtered_result.push(result[i]);
            }
          }
					if (filtered_result.length > 0)
					{
              var opt0 = document.createElement('option');
              opt0.setAttribute('value', 0);
              opt0.innerHTML = 'Выберите ...';
              dropdown.appendChild(opt0);
            for (var i = 0; i < filtered_result.length; i++)
            {
              var item = filtered_result[i];
              var opt = document.createElement('option');
              opt.setAttribute('value', item.allrpg_info_id);
              opt.innerHTML = item.allrpg_info_name;
              if (item.allrpg_info_id == current_allrpg_value())
              {
                opt.setAttribute('selected', 'selected');
                updateAllrpgInfoLink();
              }
              dropdown.appendChild(opt);
            }
            dropdown.style.display = 'inline';
					}
					else
					{
					dropdown.style.display = 'none';
					}
				}
				else
				{
					dropdown.style.display = 'none';
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
   allrpgInfoLink.style.display = "inline";
   update_allrpg_fields();
}

function updateSyncButton()
{
   var syncButton = document.getElementById('sync_to_allrpg');
   syncButton.style.display = current_id_value() > 0 ? 'inline' : 'none';
   syncButton.value = current_allrpg_value() > 0 ? 'Обновить в allrpg' : 'Добавить на allrpg';
}

function current_allrpg_value()
{
   var allrpgInfo = document.getElementById('allrpg_info_id');
   return parseInt(allrpgInfo.value);
}

function current_id_value()
{
   var idElement = document.getElementById('id');
   return idElement.value | 0;
}

function performSync(manual)
{
   if (XMLHttpRequest)
    {
      var req = new XMLHttpRequest();
      var dropdown = document.getElementById('allrpg_games');
      var uri = 'http://allrpg.info/kogdaigra2.php?from=' + current_id_value() +'&to=' + current_id_value() + '&automated=1';
      req.open ('GET', uri, true);
      req.onreadystatechange = function (aEvt) {
        if (req.readyState == 4)
        {
          if (req.status == 200)
          {
                       var str = req.responseText;
            var result = JSON.parse(str);
            if (result.length == 0 && manual)
            {
              alert("Синхронизация не удалась");
            }
            else 
            {
              result = result[0];
              document.getElementById('allrpg_info_id').value = result.allrpg_id;
              if (manual)
              {
                alert("Синхронизация успешна " + result.allrpg_id);
              }
              updateAllrpgInfoLink();
              var dropdown = document.getElementById('allrpg_games');
              dropdown.style.display = 'none';
              document.getElementById('allrpg_info_id').style.display = 'none';
            }
        }
      }
     }
		req.send();
	}
}

function syncToAllrpg()
{
  var startyearElem = document.getElementById('startyear');
  var startYear = startyearElem ? startyearElem.value : get_time_value ('begin').getFullYear();
  var isUpdate = current_allrpg_value() > 0
  if (isUpdate || startYear > 2013 || confirm ('Перед добавлением старых игр в базу allrpg.info необходимо убедиться в том, что такой игры действительно нет! Продолжить?'))
  {
   performSync (true);
  }
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
    updateSyncButton();
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