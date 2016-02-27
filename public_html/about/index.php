<?php
	require_once 'funcs.php';
	require_once 'top_menu.php';

  write_header("Когда-Игра :: О нас");
  $topmenu = new TopMenu();
  $topmenu -> pagename = 'О нас';
  $topmenu -> show();
?>

<div style="margin:1em">
<h3>Контакты</h3>
<p>По всем вопросам пишите на почту: <a href="mailto:rpg@kogda-igra.ru">rpg@kogda-igra.ru</a> <br>
По вопросам размещения фотоотчетов пишите фотомодераторам <a href="mailto:photo@kogda-igra.ru">photo@kogda-igra.ru</a> <br>
Сообщество в ЖЖ: <?php echo show_lj_user('kogda-igra'); ?></p>
<h3>Команда сайта</h3>
<ul>
  
  <li><strong>Лео</strong> <?php echo show_user_link('leotsarev'); ?>, #<a href="http://bastilia.ru/">Бастилия</a>, общие вопросы</li>
  <li><strong>Атана</strong> <?php echo show_user_link('aleks'); ?>, #<a href="http://bastilia.ru/">Бастилия</a>, редактор</li>
  <li><strong>Фидель</strong> <?php echo show_user_link('akbars'); ?>, МГ «<a href="http://romulrem.com/">Ромул и Рем</a>», редактор по Москве</li>
  <li><strong>Медик</strong> <?php echo show_user_link('steamboy'); ?>, редактор по Москве</li>
  <li><strong>Крайк</strong> <?php echo show_user_link('krayk'); ?>, редактор по Москве</li>
  <li><strong>Анатоль</strong> <?php echo show_user_link('anatolle'); ?>, редактор по Уралу, Сибири, Дальнему Востоку</li>
  <li><strong>Йолаф</strong> <?php echo show_user_link('jolaf'); ?>, консультант</li>
</ul>
<p>В создании сайта в 2007—2012 годах принимал участие <strong>Митяй</strong> <?php echo show_user_link('mtr'); ?>, #<a href="http://bastilia.ru/">Бастилия</a>.</p>
<h3>По какому принципу мы добавляем игры?</h3>
<p>Мы добавляем игры по просьбе мастеров, а также ищем игры сами.
Если Вашу игру добавили в календарь, то на почту мастеров придет соответствующее письмо.
Если в течение недели никаких возражений нет, то мы считаем, что игра заявлена. Такие игры из календаря не удаляются.
Впрочем, если произошло какое-то недоразумение, то пишите — будем разбираться.</p>
<h3 id="regions">Когда-игра в регионах</h3>
<p>
Мы считаем для себя основными следующими регионы:
<ul>
 <li>Москва, Тверь и соседние области</li>
 <li>Санкт-Петербург и другие области Северо-Запада</li>
 <li>Урал</li>
 </ul>
 Это не значит, что мы не хотим работать с другими регионами — просто у нас нет необходимых ресурсов, чтобы поддерживать расписание актуальным.
<br>Если вы ищете игры в регионе, который у нас не представлен, рекомендуем вам следующие календари:
<ul>
 <li><a href="http://larp.ru/texts/rpg/rasps.php">расписание игр по Сибири</a></li>
</ul>
Если у вас есть желание, чтобы в вашем регионе был календарь kogda-igra.ru — пишите (<a href="mailto:rpg@kogda-igra.ru">rpg@kogda-igra.ru</a>). С нас — удобный движок, посетители и поддержка других редакторов, с вас — работа по поддержанию календаря в актуальном состоянии.</p>
<h3>API</h3>
<p>На нашем сайте есть <a href="/api">API для чтения данных календаря</a> в машиночитаемом формате.</p>
<h3 id="adv">Рекламные ссылки</h3>
<p>Иногда на нашем сайте появляются рекламные ссылки. Мы по собственному усмотрению рекламируем то, что нам кажется важным и интересным для ролевого сообщества,
в основном конвенты. Мы не рекламируем конкретных ролевых игр (кроме как в рамках акции «Самая популярная игра») и не рекламируем что-либо за деньги. </p>
<h3 id="vk_like">Самая популярная игра</h3>
<p>В блоке «самая популярная игра» показывается, что логично, самая популярная игра по версии посетителей нашего сайта. Популярность определяется по числу отметок «мне нравится» в профиле игры.</p>
<h3>Баннеры</h3>
<ol>
<li><a href="http://kogda-igra.ru/img/banners/kogra-igra-88x31.gif">Баннер 88х31 анимированный</a></li>
<li><a href="http://kogda-igra.ru/img/banners/kogra-igra-88x31.png">Баннер 88х31 неанимированный</a></li>
<li><a href="http://kogda-igra.ru/img/kogda-igra.gif">Баннер 130x66</a></li>
</ol>
<h3>Open Source</h3>
<p>
<a href="https://github.com/leotsarev/kogda-igra">Исходный код нашего сайта</a> — Open Source, согласно <a href="http://www.apache.org/licenses/LICENSE-2.0.txt">лицензии Apache</a>. Если вы не знаете, что это значит, не парьтесь.<br>
Если вдруг знаете, и хотите внести свой вклад, будем вам благодарны — присылайте свои pull request.<br>
Если вам чего-то непонятно, пишите <?php echo show_user_link('leotsarev'); ?>.</p>
<h3>Благодарности</h3>

<p>Хостинг предоставлен #<a href="http://bastilia.ru">Бастилией</a> и <a href="http://www.diphost.ru" title="Хороший хостинг"><img src="http://www.diphost.ru/b/na88x31_gray.gif" width="88" height="31" style="border: 0px solid" alt="Хороший хостинг" title="Хороший хостинг" /></a></p>
<p>Иконки предоставлены <a href="http://www.famfamfam.com/lab/icons/silk/">famfamfam.com</a> и <a href="http://thenounproject.com">The Noun Project</a></p>
</div>
<?php
  write_footer(TRUE);
?>