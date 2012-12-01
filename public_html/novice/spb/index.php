<?php
	require_once 'funcs.php';
	require_once 'top_menu.php';

  write_header("Когда-Игра :: Собираешься впервые на игру?");
  $topmenu = new TopMenu();
  $topmenu -> pagename = 'Собираешься впервые на игру?';
  $topmenu -> show();
?>

<div style="margin:1em">
<h3>Собираешься впервые на игру?</h3>
<img src="/img/photo/atana.jpg" width="300" hspace="20" style="float: left" title="Александра" alt="Александра">
<p>Друзья!<br>
Мы будем рады помочь:
<ul>
  <li>выбрать интересную ролевую игру и подать на нее заявку
  <li>узнать что нужно для участия в игре  и как это достать: купить костюм или сшить самому, найти антураж, доспехи и оружие и пр.
  <li>найти, где можно научиться играть, танцевать или фехтовать
</ul>
Также вы можете нам писать по любым вопросам, мы будем рады все объяснить.<br>
Обращайтесь:<br>
Александра<br>
<a href="mailto:atana@bastilia.ru">atana@bastilia.ru</a> <br> 
<a href="http://vk.com/a_tsareva"><img src="/img/vk.png" width="16" height="16" title="http://vk.com/a_tsareva" alt="http://vk.com/a_tsareva" class="link_icon"/> a_tsareva</a>
</p>	  
</div>
<?php
  write_footer(TRUE);
?>