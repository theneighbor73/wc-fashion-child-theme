<?php

/**
 * Recursively require all PHP files
 * @param string  $directory
 */

function cpsc_require_directory($directory)
{
	if (! is_dir($directory)) {
		return;
	}

	$iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator(
			$directory
		)
	);

	foreach ($iterator as $file) {

		if (
			$file->getExtension() === 'php'
			&&
			$file->getFilename() !== 'README.php'
		) {
			require_once $file->getPathname();
		}
	}
}

/*
|--------------------------------------------------------------------------
| Bootstrap theme
|--------------------------------------------------------------------------
*/

$all_child_directories = array(
	'/inc/core', // Must load first
	'/inc',
	'/features/product-search-validation',
	'/features/customizer/logo',
	'/woocommerce/hooks',
);

foreach ($all_child_directories as $load_directory) {
	cpsc_require_directory(
		get_stylesheet_directory()
			. $load_directory
	);
};
