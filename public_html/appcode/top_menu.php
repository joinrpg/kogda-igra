<?php
	function active_button ($uri, $text, $add = '')
	{
		echo "<td class='active'><a href=\"$uri\">$text</a>$add</td>";
	}
	
	function passive_button ($text)
	{
		echo "<td class='passive'>$text</td>";
	}
	
	function show_search_form2($string = '')
	{
    echo '<form action="/search.php" method="post" id="search_form" style="display:inline;padding-bottom:1em">';
    echo "<input type=\"search\" size=\"60\" maxlength=\"100\" value=\"$string\" name=\"search\"/>";
    echo '<input type="submit" value="Искать" />';
    echo '</form>';
	}
	

	class TopMenu
	{
		function __construct()
		{
			$this -> pagename = '';
			$this -> year = 0;
			$this -> region = 0;
		}
		
		function get_page_name()
		{
			return (($this -> calendar_mode) ? "{$this -> region_name} {$this -> year}" : '') . $this -> pagename;
		}
		
		function show_region_link($text, $region, $beta)
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
		
		function show () 
		{
			$this -> calendar_mode = !!$this -> year;
			
			if (!$this -> calendar_mode)
			{
				$this -> year = CURRENT_YEAR;
			}
			echo '<table class="top_line_menu"><tr>';
			
			echo '<td><table class=logo_text><tr>';
			echo '<td><a href="/"><img src="/img/kogda-igra.png" height=32 width=32></a>';
			echo " Когда-Игра :: " . $this -> get_page_name();
			echo '</td>';
			echo '</tr></table></td>';

			echo '<td><table><tr>';
			$this -> show_region_link ('Россия', 0);
			$this -> show_region_link ('Петербург', 2);
			$this -> show_region_link ('Москва', 3);
			$this -> show_region_link ('Урал', 5);
			
			if (check_edit_priv())
			{
				$this -> show_region_link ('Сибирь', 6, true);
				$this -> show_region_link ('ЮФО', 7, true);
			}
			
			echo '</tr></table></td>';
			
			echo '<td><table><tr>';
			$this -> write_years_list ();
			echo '</tr></table></td>';

			echo '</tr></table>';
			$this -> show_menu ();
			show_search_form2 ();
			
		}

		function show_menu()
		{
			$username = get_username();

			echo '<div class=user_menu>';
			if ($username)
			{
				echo show_user_link($username);
				echo ' <form action="/logout/" method=post id=logout_form style="display:inline"><input type=submit value="Выйти"></form>';
				echo '<br>';
			}
			
			
			if (check_edit_priv())
			{
				show_menu_link ('/edit/game', 'Добавить&nbsp;игру', '');
				show_menu_link ('/edit/', 'Панель&nbsp;управления', ' :: ');
			}
			else
			{
				echo 'Нет нужной игры? ';
				show_menu_link ('/edit/game', 'Добавьте самостоятельно', '');
				echo ' или напишите на <a href="mailto:rpg@kogda-igra.ru">rpg@kogda-igra.ru</a><br>
				<a href="/about/">Ответы на другие вопросы</a>';
			}
			$user = get_user();
			if ($user && !$user['email'])
			{
				echo '<div class="urgent_message">В вашем профиле не указан адрес email. <input type="button" onclick="try_login()" value="Указать"></div>';
			}
			echo '</div>';
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
			if ($year == $this -> year)
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