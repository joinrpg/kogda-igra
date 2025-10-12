<?php
require_once 'base_funcs.php';
require_once 'logic/updates.php';
require_once 'logic/gamelist.php';
require_once 'review.php';
require_once 'uifuncs.php';
require_once 'funcs.php';
require_once 'config.php';

  $sitename_editors_email = SITENAME_EDITORS_EMAIL;
  $mailto_editors = "<a href=\"mailto:$sitename_editors_email\">$sitename_editors_email</a>";

	function get_region_uri ($region)
	{
        $region_arr = get_array('region_uri');
        $result = array_key_exists ($region, $region_arr) ? $region_arr[$region] : '';
        if ($result)
        {
          return "/$result/";
        }
        else 
        {
          return '/';
        }
	}

	class TopMenu
	{
		function __construct()
		{
			$this -> pagename = '';
			$this -> year = 0;
			$this -> region = 0;
			$this -> show_add_adv = TRUE;
			$this -> search = '';
			$this -> edit = FALSE;
			$this -> show_new_adv = TRUE;
		}
		
		function show_search_form()
		{
			echo '<form action="/search.php" method="post" id="search_form" style="clear:left; padding: 2px">';
			echo "<input type=\"search\" size=\"40\" maxlength=\"100\" value=\"{$this->search}\" name=\"search\"/>";
			echo '<input type="submit" value="Искать" />';
			echo '</form>';
		}
		
		function get_site_title()
		{
			return SITENAME_MAIN;
		}
		
		function get_mailto_editors()
		{
			$sitename_editors_email = SITENAME_EDITORS_EMAIL;
			return "<a href=\"mailto:$sitename_editors_email\">$sitename_editors_email</a>";
		}

		function get_telegram_bot()
		{
			$botname = SITENAME_EDITORS_BOT;
			return "<a href=\"https://t.me/$botname\"><img src='/img/telegram.png' style='margin-bottom: -3px;'/>$botname</a>";
		}
		
		function get_page_header()
		{
			return  $this->get_site_header(). ': ' . $this -> get_page_name();
		}
		
		function get_site_header()
		{
			$header = $this -> get_site_title();
			return ($_SERVER['REQUEST_URI'] == '/' ? $header : "<a href=\"/\">$header</a>");
		}
		
		function get_page_name()
		{
			return (($this -> calendar_mode) ? "{$this -> region_name}&nbsp;{$this -> year}" : '') . $this -> pagename;
		}
		
		function get_page_title()
		{
			return  $this->get_site_title() . ': ' . $this -> get_page_name();
		}
		
		function show_region_link($text, $region, $beta = false)
		{
			if (($this -> calendar_mode)  && ($region == $this -> region))
			{
				passive_button ($text);
			}
			else
			{
				$year_text = ($this -> year == get_current_year ()) ? '' : "{$this -> year}/";
				$uri = get_region_uri($region);
				active_button ("$uri$year_text", $text, $beta ? '<sup>бета</sup>' : '');
			}

		}
		
		function show_region_strip()
		{
			$regions = get_array('region');
			foreach (array_keys($regions) as $region_id)
			{
				if ($region_id == 10) 
				{
					//Remove Ukraine from top_menu
					//TODO: Add flag "show in top_menu" into region settings
					continue;
				}
				$this -> show_region_link($regions[$region_id], $region_id);
			}
		}
		
		function show () 
		{
			
			$this -> calendar_mode = !!$this -> year;
			
			if (!$this -> calendar_mode)
			{
				$this -> year = get_current_year ();
			}
			
			write_header ($this -> get_page_title(), $this -> edit);

			echo '<div class=logo>';
			echo '<a href="/"><img src="/img/kogda-igra.png" height=32 width=32></a>';
			echo " <span class=logo_text>" . $this -> get_page_header() . '</span>';
			$this -> show_search_form ();
			echo '</div>';

			echo '<div class=menu_box>';
			echo '<div class=menu_strip>';
			
			$this -> show_region_strip();
			echo '</div>';

			echo '<div class=menu_strip>';
			$this -> write_years_list ();
			echo '</div>';


			echo '<div class=menu_strip>';
			show_button('/about/', 'О нас');

			// Мертвые разделы, переделать!
			// show_button('/reviews/', 'Рецензии');
			// show_button('/photo/', 'Фото');

			show_button('http://rpg.ru/', 'Что такое ролевые игры? Новичку...');
			joinrpg_box();
			echo '</div> ';
			
			$username = get_username();
			echo '<div class=menu_strip>';
			if ($username)
			{
					passive_button(show_user_link($username) . ' <a href="/logout/" class="g_id_signout">Выйти</a>');
			}
			real_button (get_game_edit_link(NULL), 'Добавить&nbsp;игру...');
			if (check_edit_priv())
			{
				show_button ('/edit/', 'Панель&nbsp;управления');
			}

			if (!$username) {
			passive_button( '
			<div id="g_id_onload"
			data-client_id="422789528511-ova7a1omkeak7ul1f134t6a392nv8k15.apps.googleusercontent.com"
			data-context="signin"
			data-ux_mode="popup"
			data-callback="onSignIn"
			data-auto_prompt="false">
	   </div>
	   
	   <div class="g_id_signin"
			data-type="standard"
			data-shape="rectangular"
			data-theme="filled_blue"
			data-text="$ {button.text}"
			data-size="medium"
			data-logo_alignment="left">
	   </div>
	   ');
			}
			echo '</div>';

			$this -> show_messages ();
			echo '</div>';

			if ($this -> show_new_adv)
			{
				$this -> write_adv_box();
			}
			
			if ($this -> show_add_adv)
			{
				echo '<b>Нет нужной игры</b>? <a href="' . get_game_edit_link(NULL). '">Добавьте</a> самостоятельно или напишите в '. $this -> get_telegram_bot() .' или на ' . $this -> get_mailto_editors();
			}
		}

		function show_messages()
		{

			$user = get_user();
			if ($user && !$user['email'])
			{
				echo '<div class="urgent_message">В вашем профиле не указан адрес email. <input type="button" onclick="try_login()" value="Указать"></div>';
			}
		}
		
		function write_adv_box()
	{
    return;
    $adv = get_adv_updates_for_week();
    if (!is_array($adv))
    {
			return;
    }
    echo '<div class="adv_box"><b>Новое:</b> ';
		$sep ='';
    foreach ($adv as $game)
    {
			echo $sep;
			$sep = ', ';
			$update_text = htmlspecialchars ($game['update_type_user_text']);
			
			$update_text = str_replace('%game%', '«<a href="'. get_game_profile_link($game['id']) . '">' . $game['name'].'</a>»', $update_text);
			$update_text = str_replace('%review_link%', '<a href="' . ReviewBase :: get_review_uri($game) .'">Рецензия</a>', $update_text);
			$update_text = str_replace('%photo%',  'Фото/видео', $update_text);
			$update_text = str_replace('%updated_user%', show_user_link ($game['updated_user_name']), $update_text);
			if ($game['msg'])
			{
				$update_text .= "{$game['msg']}";
			}
			echo "$update_text";
    }
    echo '.</div>';
   }

	function write_years_list()
		{
			$region = get_region_uri($this -> region);
			$current_year = $this -> year;

			$sep = ' :: ';
			
			$max_early = 0;
			$min_later = 99999;
			
			$years_list = get_year_list ($region);
			foreach ($years_list as $year_val)
			{
				$year = $year_val['year'];
				if ($year < $current_year - 2)
				{
					$max_early = max ($max_early, $year);
				}
				elseif ($year > $current_year + 2)
				{
					$min_later = min ($min_later, $year);
				}
				else
				{
					$years[] = $year;
				}
			}
			
			if ($max_early > 0)
			{
				echo $this -> year_link ($max_early, '&lt;&lt;');
			}
			
			if (!is_array($years))
			{
				return;
			}
			
			foreach ($years as $year)
			{
				echo $this -> year_link ($year, $year);
			}
			
			if ($min_later < 99999)
			{
				echo $this -> year_link ($min_later, '&gt;&gt;');
			}
		}
		
		function year_link ($year, $text)
		{
			if ($year == $this -> year && $this->calendar_mode)
			{
				passive_button ($text); 
			}
			else
			{
				active_button (get_region_uri($this -> region) . $year, $text);
			}
		}
	}
?>