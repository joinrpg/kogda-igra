<?php
require_once 'logic.php';
require_once 'uri_funcs.php';

abstract class Email
{
  function __construct()
  {
  }
  
  function mg_send($to, $from, $subject, $message) {


    if (!MAILGUN_KEY)
    {
      return;
    }

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERPWD, 'api:'.MAILGUN_KEY);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $plain = strip_tags(str_replace("<br />", "\n", $message));

  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_URL, 'https://api.eu.mailgun.net/v3/'.SITENAME_HOST.'/messages');
  curl_setopt($ch, CURLOPT_POSTFIELDS, array('from' => $from,
        'to' => $to,
        'subject' => $subject,
        'html' => $message,
        'text' => $plain));

  $j = json_decode(curl_exec($ch));

  $info = curl_getinfo($ch);

  if($info['http_code'] != 200)
        {
          var_dump($info);
					die("Send failed");
        }

  curl_close($ch);

  return $j;
	}
  
  function send ()
  {
    $recipient = $this -> get_recipient();
    $sender = $this -> get_sender();
    if ($recipient)
    {
			$bcc = "Bcc: $sender\r\n";
    }
    else
    {
			$bcc = '';  
			$recipient = $sender;
    }
    if ($recipient)
    {
      $this -> mg_send($recipient, $sender, $this -> get_subject(), $this -> get_message());
    }
  }
  
  function get_sender ()
  {
    return SITENAME_EDITORS_EMAIL;
  }
}

class AddedURIEmal extends Email
{
	function __construct($add_uri_id)
  {
		$this -> add_uri_id = $add_uri_id;
  }
  
  function get_subject()
  {
		return SITENAME_MAIN . ": требуется модерация ссылки";
  }
  
	function get_message()
  {
     return "Пользователь добавил ссылку на анонс игры. " .
		SITENAME_SCHEME . "://" . SITENAME_HOST . "/edit/game?add_uri_id={$this->add_uri_id}
--
C уважением, " . SITENAME_SIGNATURE;
  }
  
  function get_recipient()
  {
		return NULL;
  }
}

class GameUpdatedEmail extends Email
{
  
  function __construct ($game_id, $updated)
  {
    $this -> game_data = get_game_by_id ($game_id);
    $this -> updated = $updated;
    $this -> intersections = get_intersections($game_id);
  }
  
  function get_recipient()
  {
   if (!$this -> game_data['email'])
   {
    return '';
   }
   return '<' . $this -> game_data['email'] .'>';
  }
  
  function get_subject()
  {
    return SITENAME_MAIN . ": " . ($this -> updated ? "Обновлена" : "Добавлена") . " игра \"" . $this -> game_data['name'] .  "\"";
  }
  
  function get_int_table()
  {
		$list = '';
     foreach ($this->intersections as $game)
     {
        $masked = $game['show_flags'] && 1;
        if (!$masked)
        {
          $uri = get_game_profile_link($game['id'], true);
          $mg = $game['mg'] ? "({$game['mg']})" : '';
          $list .= "{$game['status_name']} - {$game['name']} $mg $uri \n";
        }
     }
     return $list ? "Пересечения:\n" . $list . "\n" : '';
  }
  
  function get_game_info_text()
  {
		$game =$this -> game_data;
		if ($game['uri'])
      {
        $uri = "\nСайт: {$game['uri']}";
      }
      else
      {
        $uri = '';
      }
     if ($game['show_date_flag'])
     {
      $begin_date = strtotime ($game['begin']);
      $days = $game['time']-1;
      $end_date = getdate(strtotime ("+$days day", $begin_date));
      $begin_date = getdate ($begin_date);
      $date_text = "\nДаты игры: " . html_entity_decode(get_date_text($begin_date, $end_date, TRUE), ENT_COMPAT, "utf-8");
     }
     else
     {
      $date_text = '';
     }
     if ($game['hide_email'])
     {
      $hide_email = "(Email скрыт и на сайте не показан)";
     }
     else
     {
      $hide_email = '';
     }
    $players_count = $game['players_count'] > 0 ? $game['players_count'] : 'Неизвестно';
    
    $game_type_name = html_entity_decode($game['game_type_name'], ENT_COMPAT, "utf-8");
    $polygon_name = html_entity_decode($game['polygon_name'], ENT_COMPAT, "utf-8");
    $game_name = $this -> game_data['name'];
    $int_text = $this -> get_int_table();
    $vk_text  = ($game['vk_club']) ? ("\nВКонтакте: " . format_vk_link($game['vk_club'])) : '';
    $lj_text = ($game['lj_comm']) ? ("\nЖЖ: " . format_lj_link ($game['lj_comm']))   : '';
    $fb_text = ($game['fb_comm']) ? ("\nFacebook: " . format_fb_link ($game['fb_comm']))   : '';
    $profile_link = get_game_profile_link($game['id'], true);
    return "Профиль: $profile_link

Название: $game_name
Статус: {$game['status_name']}
Регион: {$game['sub_region_name']}$uri$date_text
Тип игры: $game_type_name
Полигон: $polygon_name
Кол-во игроков: $players_count
Мастерская группа: {$game['mg']}
Email: {$game['email']}$hide_email$vk_text$lj_text$fb_text

$int_text";
  }
  
  function get_message()
  {
     $text = $this -> get_game_info_text();
     $update_text = $this -> updated ? "обновили" : "добавили";

    return SITENAME_SIGNATURE . "$update_text запись о вашей игре в календаре. Пожалуйста, проверьте эти сведения и напишите нам на {$this -> get_sender()}, если они ошибочны или неполны:
$text
Это письмо отправлено автоматически. Если письмо попало не туда, или вы больше не хотите получать таких писем, напишите {$this -> get_sender()} и мы разберемся.

--
C уважением, " . SITENAME_SIGNATURE;
  }
}

class GameReqModerateEmail extends GameUpdatedEmail
{
	function __construct($game_id)
	{
		parent::__construct($game_id, 0);
	}
	
	function get_recipient()
	{
		return NULL;
	}
	
	function get_subject()
	{
		return SITENAME_MAIN . ": требуется модерация {$this -> game_data['name']}";
	}
	
	  function get_message()
  {
     $text = $this -> get_game_info_text();

    return "Пользователь добавил запись об игре. Проверьте ее перед добавлением в календарь.
$text
--
C уважением, " . SITENAME_SIGNATURE;
  }
}

?>
