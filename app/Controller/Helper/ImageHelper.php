<?php
/**
 * Created by PhpStorm.
 * User: A
 * Date: 2015-04-05
 * Time: 7:09 PM
 */
class ImageHelper {
	public static function compressImage($fileName) {
		$cwd = getcwd ();
		$fileName = $cwd . '/' . $fileName;
		if (mb_eregi ( "^.*\\.jpe?g$", $fileName )) {
			shell_exec ( "/usr/bin/jpegtran -copy none -optimize -progressive -perfect -outfile \"$fileName.new\" \"$fileName\" && rm -f \"$fileName\" && mv \"$fileName.new\" \"$fileName\"" );
		} else if (mb_eregi ( "^.*\\.(png|gif)$", $fileName )) {
			shell_exec ( "/usr/bin/optipng -o5 \"$fileName\"" );
		}
	}
} 