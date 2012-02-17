<?php
require_once 'logic/updates.php';
require_once 'logic/gamelist.php';
require_once 'review.php';

	function active_button ($uri, $text, $add = '')
	{
		echo "<div class='active'><a href=\"$uri\">$text</a>$add</div>";
	}
	
	function passive_button ($text)
	{
		echo "<div class='passive'>$text</div>";
	}
	

	class TopMenu
	{
		function button ($uri, $text, $add = '')
		{
			if ($uri == $_SERVER['REQUEST_URI'])
			{
				passive_button ($text);
			}
			else
			{
				active_button ($uri, $text, $add);
			}
		}
		
		function __construct()
		{
			$this -> pagename = '';
			$this -> year = 0;
			$this -> region = 0;
			$this -> show_add_adv = TRUE;
			$this -> search = '';
		}
		
	function show_search_form()
	{
    echo '<form action="/search.php" method="post" id="search_form" style="clear:left; padding: 2px">';
    echo "<input type=\"search\" size=\"40\" maxlength=\"100\" value=\"{$this->search}\" name=\"search\"/>";
    echo '<input type="submit" value="Искать" />';
    echo '</form>';
	}
		
		function get_page_name()
		{
			return (($this -> calendar_mode) ? "{$this -> region_name}&nbsp;{$this -> year}" : '') . $this -> pagename;
			
		}
		
		function show_region_link($text, $region, $beta = false)
		{
			if (($this -> calendar_mode)  && ($region == $this -> region))
			{
				passive_button ($text);
			}
			else
			{
				$year_text = ($this -> year == CURRENT_YEAR) ? '' : "{$this -> year}/";
				$uri = get_region_uri($region);
				active_button ("$uri$year_text", $text, $beta ? '<sup>Бета</sup>' : '');
			}

		}
		
		function show_region_strip()
		{
			
			$this -> show_region_link ('Россия', 0);
			$this -> show_region_link ('Петербург', 2);
			$this -> show_region_link ('Москва', 3);
			$this -> show_region_link ('Урал', 5);
			
			if (check_edit_priv())
			{
				$this -> show_region_link ('Сибирь', 6, true);
				$this -> show_region_link ('ЮФО', 7, true);
			}
			
		}
		
		function show () 
		{
			$this -> calendar_mode = !!$this -> year;
			
			if (!$this -> calendar_mode)
			{
				$this -> year = CURRENT_YEAR;
			}
			echo '<div class=logo>';
			echo '<a href="/"><img src="/img/kogda-igra.png" height=32 width=32></a>';
			echo " <span class=logo_text>Когда-Игра: " . $this -> get_page_name() . '</span>';
			$this -> show_search_form ();
			echo '</div>';

			echo '<div class=menu_box>';
			echo '<div class=menu_strip>';
			$this -> show_region_strip();
			echo '</div> ';

			echo '<div class=menu_strip>';
			$this -> write_years_list ();
			echo '</div> ';


			echo '<div class=menu_strip>';
			$this -> button('/about/', 'О нас');
			$this -> button('/reviews/', 'Рецензии');
			$this -> button('/photo/', 'Фото');
			echo '</div> ';
			
			$username = get_username();
			if ($username)
			{
				echo '<div class=menu_strip>';

					passive_button(show_user_link($username) . '<form action="/logout/" method=post id=logout_form style="display:inline"><input type=submit value="Выйти"></form>');
					$this -> button ('/edit/game/', 'Добавить&nbsp;игру');
				
				if (check_edit_priv())
				{
					$this -> button ('/edit/', 'Панель&nbsp;управления');
				}
				echo '</div>';
			}
			$this -> show_messages ();
			echo '</div>';
			

			
			
			$this -> write_adv_box();
			
			if (!$username && $this -> show_add_adv)
			{
				echo '<b>Нет нужной игры</b>? <a href="/edit/game/">Добавьте</a> самостоятельно или напишите на <a href="mailto:rpg@kogda-igra.ru">rpg@kogda-igra.ru</a>';
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
			
			$update_text = str_replace('%game%', '«<a href="/game/'. $game['id'] . '">' . $game['name'].'</a>»', $update_text);
			$update_text = str_replace('%review_link%', '<a href="' . ReviewBase :: get_review_uri($game) .'">Рецензия</a>', $update_text);
			$update_text = str_replace('%photo%',  'Фотоотчет', $update_text);
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
				echo $this -> year_link ($max_early, '<<');
			}
			
			foreach ($years as $year)
			{
				echo $this -> year_link ($year, $year);
			}
			
			if ($min_later < 99999)
			{
				echo $this -> year_link ($min_later, '>>');
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