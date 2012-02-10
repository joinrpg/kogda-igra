<?php 
require_once "funcs.php";
require_once 'logic/review.php';
require_once 'user_funcs.php';

class ReviewBase 
{
  public static function get_review_uri ($review_data)
  {
    $topic_id = intval ($review_data['topic_id']);
    if ($topic_id > 0)
    {
      return "http://forum.rpg.ru/index.php?showtopic=$topic_id";
    }
    else
    {
      return $review_data['review_uri'];
    }
  }
  
  public static function get_review_author ($review)
  {
    $username = $review['username'];
    $author = $review['author_name'];
    return $username ? show_user_link($username, $review['author_id']) : $author;
  }
}

abstract class ReviewTable extends ReviewBase
{
	
	function get_reviews()
	{
		return NULL;
	}
	
	function write_header() {}
	
	function write_author_columns ($row)
	{
	}
	
	function write_extra_columns($review)
	{
	}
	
	function show_table() {}
	
	function write_extra_rows() {}
	
	function show ()
  {
		$this -> reviews = $this -> get_reviews();
		if (!$this -> show_table())
		{
			return;
		}
    echo "<h3 id=\"review\">Рецензии</h3>";
		echo "<table>";
		$this -> write_header();
		if (is_array($this -> reviews))
    {
        foreach ($this -> reviews as $review)
        {

          $topic_id = intval ($review['topic_id']);
          
          echo "<tr>";
          $this -> write_author_columns($review);
          $review_uri = Review :: get_review_uri ($review);
          echo "<td><a href=\"$review_uri\">$review_uri</a></td>";
          $this -> write_extra_columns($review);
          echo "</tr>";
        }
      }
      $this -> write_extra_rows();
		echo "</table>";
  }
}

class ReviewEdit extends ReviewTable
{
	function __construct ($game_id)
  {
    $this -> game_id = $game_id;
  }

	function get_reviews()
	{
		return get_reviews_for_game_edit ($this -> game_id);
	}
	
	function write_header() {
		echo "<tr><th>Автор (ЖЖ)</th><th>Имя автора<br>(указать, если нет ЖЖ)</th><th>Ссылка</th><th>&nbsp;</th></tr>";
	}
	
	function write_author_columns ($review)
	{
		$username = $review['username'];
		$author = $review['author_name'];
		if ($username)
		{
			$username = show_user_link($username, $review['author_id']);
		}
		echo "<td>$username</td>";
		if ($author)
		{
			echo "<td>$author (<a href=\"/edit/problems/update-author/?author=$author\">Исправить</a>)</td>";
		}
		else
		{
			echo "<td>&nbsp;</td>";
		}
	}
	
	function write_extra_columns($review)
	{
		echo "<td>";
		echo "<form action=\"\" method=\"post\" id=\"delete_review\">";
		echo "<input type=\"hidden\" name=\"review_id\" value=\"{$review['review_id']}\" />";
		$deleted = $review['show_review_flag'];
		submit($deleted ? 'Удалить' : 'Восстановить', $deleted ? 'delete_review' : 'restore_review', $this -> game_id, '', TRUE);
		echo"</form></td>";
	}
	
	function show_table ()
	{
		return TRUE;
	}
	
	function write_extra_rows()
	{
		 echo "<tr><form action=\"\" method=\"post\" id=\"add_review\">";
        echo "<td>
          <input type=\"text\" name=\"author_lj\" maxlength=\"100\" size=\"30\" value=\"\">
          </td>";
        echo "<td>
          <input type=\"text\" name=\"author\" maxlength=\"100\" size=\"10\" value=\"\">
          </td>";
        echo "<td><input type=\"text\" name=\"review_uri\" maxlength=\"200\" size=\"80\" value=\"\" /></td>";
        echo "<td>";

          submit('Добавить', 'add_review', $this -> game_id, '', TRUE);
        echo"</td>";
      echo "</form></tr>";
	}
}

class Review extends ReviewTable
{
  function __construct ($game_id)
  {
    $this -> game_id = $game_id;
  }
  
	function show_table()
	{
		return is_array($this -> reviews);
	}
	
	function get_reviews()
	{
		return get_reviews_for_game ($this -> game_id);
	}
	
	function write_header() {
		echo "<tr><th>Автор</th><th>Ссылка</th></tr>";
	}
	
	function write_author_columns ($review)
	{
		$username = Review :: get_review_author($review);
		echo "<td>$username</td>";
	}  
}

class ReviewForUser extends ReviewBase
{
   function ReviewForUser ($user_id)
   {
      $this -> reviews = get_reviews_for_user ($user_id);
   }
   
     function show ()
  {
     if (!is_array($this -> reviews))
     {
      return;
     }
    echo "<h3 id=\"review\">Рецензии</h3>";
		echo "<table>";

        echo "<tr><th>Игра</th><th>Ссылка</th></tr>";
		

        foreach ($this -> reviews as $review)
        {

          $topic_id = intval ($review['topic_id']);
          
          echo "<tr>";


              
              echo "<td><a href=\"/game/{$review['game_id']}\">{$review['name']}</a></td>";
            
            $review_uri = Review :: get_review_uri ($review);
            echo "<td><a href=\"$review_uri\">$review_uri</a></td>";
          echo "</tr>";
        }

		echo "</table>";
  }
  
}