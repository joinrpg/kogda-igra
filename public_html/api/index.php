<?php
	require_once 'funcs.php';
	require_once 'top_menu.php';
	
  $topmenu = new TopMenu();
  $topmenu -> pagename = 'Автоматизированные запросы';
  $topmenu -> show();
  ?>
  <p>Сайт kogda-igra поддерживает прием простых автоматических запросов. Данные не претендуют на полноту, 
  но возможно развитие по запросам пользователей. Если вам нужны эти данные — пишите (<?php echo $mailto_editors ?>), разовьем до полноценного API.</p>
  <p>Данное API используется для создания кросс-ссылок с сайтом <a href="http://allrpg.info">allrpg.info</a>, но может быть использовано всеми желающими.</p>
  <p>На все запросы дается ответ — объект в формате <a href="http://json.org/json-ru.html">JSON</a>. Если данные не найдены, возвращается пустой объект.</p>
  <p><b>Получение данных об игре по ее id.</b><br>
  <b>URI</b>: <u>/api/game/<i>(id)</i></u><br>
  Выдает объект с данными об игре по ее номеру (<code>id</code>). 
  <code>Id</code> игры можно заметить в URI профиля. Т.е. <a href="http://kogda-igra.ru/game/152">http://kogda-igra.ru/game/152</a> — <code>id=152</code>. 
  Выдаются все общедоступные данные. Специальные случаи:
  <ul>
    <li>Если игра закрыта от просмотра неавторизованными пользователями,
  выдается ответ вида <code>{"id":"<i>(id)</i>", "access-denied":"1"}</code>. </li>
    <li>Если <code>id</code> не верный, выдается пустой объект.</li>
    <li>Если запись об игре слита с другой записью, то выдается перенаправление: <code>{"id":"<i>(id)</i>", "redirect_id":"<i>(новый id)</i>"}</code>. В этом случае рекомендуется обновить ссылку так, чтобы она указывала на новую запись.</li>
    </ul>
  </p>
  <p><b>Получение данных об обновлениях с определенного timestamp.</b><br>
  <b>URI</b>: <u>/api/changed/<i>(timestamp)</i></u><br>
  Выдает все обновленные игры с <code>timestamp</code> (UNIX timestamp).
  
  
  </p>
  <p><b>Поиск игры по идентификатору в базе Allrpg.info.</b><br>
  <b>URI</b>: <u>/api/allrpg-info/<i>(allrpg-info-id)</i></u><br>
  По номеру профиля в базе сайта allrpg.info (<code>allrpg-info-id</code>) ищет соответствующую игру в базе kogda-igra. Если игра, ссылающаяся на указанный id, есть
  то выдается ответ вида <code>{"id":"440","allrpg_info_id":"1356","profile_uri":"\/game\/440"}</code>, где <code>id</code> — идентификатор в базе kogda-igra, 
  <code>allrpg_info_id</code> — идентификатор в базе allrpg.info, <code>profile_uri</code> — ссылка на профиль в формате «для людей».
  </p>
  <?php
  write_footer(TRUE);
?>