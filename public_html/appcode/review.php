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
class Review extends ReviewBase
{
  function Review ($game_id)
  {
    $this -> game_id = $game_id;
    $this -> reviews = get_reviews_for_game ($game_id);
    $this -> show_edit = FALSE;
  }

  function show ()
  {
     if (!is_array($this -> reviews) && !$this -> show_edit)
     {
      return;
     }
    echo "<h3 id=\"review\">Рецензии</h3>";
		echo "<table>";
		if ($this -> show_edit)
		{
      echo "<tr><th>Автор (ЖЖ)</th><th>Имя автора<br>(указать, если нет ЖЖ)</th><th>ID</th><th>Ссылка</th><th>&nbsp;</th></tr>";
		}
		else
		{
        echo "<tr><th>Автор</th><th>Ссылка</th></tr>";
		}

      
      if (is_array($this -> reviews))
      {
        foreach ($this -> reviews as $review)
        {

          $topic_id = intval ($review['topic_id']);
          
          echo "<tr>";

            if ($this -> show_edit)
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
              echo "<td>$topic_id</td>";
            }
            else
            {
              
              $username = Review :: get_review_author($review);
              echo "<td>$username</td>";
            }
            $review_uri = Review :: get_review_uri ($review);
            echo "<td><a href=\"$review_uri\">$review_uri</a></td>";
            if ($this -> show_edit)
             {
                echo "<td>";
               echo "<form action=\"\" method=\"post\" id=\"delete_review\">";
               echo "<input type=\"hidden\" name=\"review_id\" value=\"{$review['review_id']}\" />";
              submit('Удалить рецензию', 'delete_review', $this -> game_id, '', TRUE);
              echo"</form></td>";
            }
          echo "</tr>";
        }
      }
      if ($this -> show_edit)
             {
      echo "<tr><form action=\"\" method=\"post\" id=\"add_review\">";
        echo "<td>
          <input type=\"text\" name=\"author_lj\" maxlength=\"100\" size=\"30\" value=\"\">
          </td>";
        echo "<td>
          <input type=\"text\" name=\"author\" maxlength=\"100\" size=\"10\" value=\"\">
          </td>";
        echo "<td><input type=\"text\" name=\"topic_id\" maxlength=\"20\" size=\"10\" value=\"\" /></td>";
        echo "<td><input type=\"text\" name=\"review_uri\" maxlength=\"200\" size=\"80\" value=\"\" /></td>";
        echo "<td>";

          submit('Добавить', 'add_review', $this -> game_id, '', TRUE);
        echo"</td>";
      echo "</form></tr>";
      }
		echo "</table>";
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