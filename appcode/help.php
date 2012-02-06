<?php
require_once 'calendar.php';
require_once 'logic.php';

abstract class Form
{
  protected static function get_control($label, $control_text)
  {
    return "<tr><td>$label</td><td>$control_text</td>";
  }
  
  protected static function get_input ($input_type, $id,  $value)
  {
    return "<input type=\"$input_type\" name=\"$id\" id=\"$id\" value=\"$value\" />";
  }

  protected static function get_input_control ($label, $id, $input_type, $value = '')
  {
    return self :: get_control($label, self :: get_input($input_type, $id, $value));
  }

  protected static function get_textbox ($label, $id, $value = '')
  {
    return self :: get_input_control ($label, $id, 'text', $value);
  }
  
  protected static function get_hidden ($label, $id, $value = '')
  {
    return self :: get_input('hidden', $id, $value);
  }
}

abstract class HelpNeededForm extends Form
{
  protected $id = 0;
  protected $action;
  function __construct($id)
  {
    $this -> id = intval($id);
    $this -> action = get_post_field ('action');
  }
  
  function show_calendar()
  {
    $calendar = new Calendar(get_calendar_game_by_id($this -> id));
    $calendar -> editor = false;
    $calendar -> show_status = false;
    $calendar -> write_calendar();
  }
  
  function is_submit()
  {
    return $this -> action == 'submit';
  }
  
  abstract function get_return_to();
  abstract function write_controls();
  abstract function save();
  
  function write_form()
  {
    echo '<form action="' . $this -> get_return_to() . '" method="post" id="correction">';
    echo '<table class="form">';
    echo self :: get_hidden ('game_id', $this -> id);
    $this -> write_controls ();
    echo self :: get_hidden ('action', 'submit');
    echo '</table></form>';
  }
  
  function show_form()
  {
    if (!is_logged_in())
    {
      show_login_box($this -> get_return_to());
      echo "<p>Чтобы воспользоваться этой функцией, надо залогинится. Пожалуйста, введите название своего ЖЖ в форме выше.</p>";
      return;
    }
    if ($this -> is_submit())
    {
      $this -> save ();
    }
    else 
    {
      $thos -> write_form();
    }
  }
  
  function show()
  {
    $this -> show_calendar();
    $this -> show_form();
  }
}

class EmailNeededForm extends HelpNeededForm
{
  function __construct ($id)
  {
    parent :: __construct ($id);
  }
  
  function get_return_to()
  {
    return '/help-needed/email/' . $this -> id . '/';
  }
  
  function write_controls()
  {
    echo self :: get_textbox ('Email мастеров', 'email');
  }
  
  function save()
  {
    
  }
  
}


?>