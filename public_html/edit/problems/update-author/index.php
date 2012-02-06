<?php
	require_once 'funcs.php';
  require_once 'logic/review.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}
	
	if (!array_key_exists ('author', $_GET))
	{
    return_to_main();
	}
	
	$username =  array_key_exists ('username', $_GET)  ? $_GET['username'] : false;
	$author = $_GET['author'];
	write_header('Исправление автора');
	echo '<h1>Рецензии :: Исправление автора</h1>';
	show_greeting();
	if ($username)
	{
     update_author_to_user ($author, $username);
     echo "<p>Операция завершена. </p>";
	}
	else
	{
?>
  <form action="" method="get" id="update_author">
  <input type="hidden" name="author" value="<?php echo $author; ?>">
  <label><strong>Имя автора</strong>:</label> <?php echo $author; ?><br>
  <label for="username"><strong>Имя ЖЖ</strong>:</label><input type="text" name="username" id="username" value="<?php echo $author; ?>"><br>
  <input type="submit" value="Обновить">
  </form>
<?php
	}
	
	write_footer();

	?>