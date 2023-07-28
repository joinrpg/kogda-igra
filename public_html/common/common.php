<?php
function validate_year ($year)
{
	if (settype ($year, 'int') && ($year > 1980) && ($year < 2030))
	{
				return $year;
	}
	return 0;
}
?>