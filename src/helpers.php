<?php declare(strict_types=1);

/**
 * Helper to securely output data using htmlspecialchars function default UTF-8 encoding
 * @param string $var
 * @return string
 */
function html(string $var):string
{
	return htmlspecialchars($var, ENT_QUOTES,"UTF-8");
}