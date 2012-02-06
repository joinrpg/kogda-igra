<?php
	require_once 'funcs.php';
  header("HTTP/1.0 404 Not Found", true, 404);
  write_header("Когда-Игра :: 404");
?>

<div style="margin:1em">
<h1>Страница не найдена</h1>
<p>К сожалению, ссылка какая-то левая. Впрочем, у нас есть <a href="/">календарь игр</a>, пусть он вас утешит.</p>
</div>
<?php
  write_footer(TRUE);
?>