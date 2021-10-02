/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

// Register a templates definition set named "default".
CKEDITOR.addTemplates( 'default', {
	// The name of sub folder which hold the shortcut preview images of the
	// templates.
	imagesPath: CKEDITOR.getUrl( CKEDITOR.plugins.getPath( 'templates' ) + 'templates/images/' ),

	// The templates definitions.
	templates: [ {
		title: 'تصویر و عنوان',
		image: 'template1.gif',
		description: 'یک تصویر اصلی با یک عنوان و متن است که تصویر را احاطه کرده اند.',
		html: '<h3>' +
			// Use src=" " so image is not filtered out by the editor as incorrect (src is required).
			'<img src=" " alt="" style="margin-right: 10px" height="100" width="100" align="left" />' +
			'عنوان را اینجا تایپ کنید' +
			'</h3>' +
			'<p>' +
			'متن را اینجا تایپ کنید' +
			'</p>'
	},
	{
		title: 'قالب عجیب',
		image: 'template2.gif',
		description: 'یک قالب که دو ستون، هر کدام با یک عنوان و متن تعریف میکند.',
		html: '<table cellspacing="0" cellpadding="0" style="width:100%" border="0">' +
			'<tr>' +
				'<td style="width:50%">' +
					'<h3>عنوان 1</h3>' +
				'</td>' +
				'<td></td>' +
				'<td style="width:50%">' +
					'<h3>عنوان 2</h3>' +
				'</td>' +
			'</tr>' +
			'<tr>' +
				'<td>' +
					'متن 1' +
				'</td>' +
				'<td></td>' +
				'<td>' +
					'متن 2' +
				'</td>' +
			'</tr>' +
			'</table>' +
			'<p>' +
			'متن بیشتر...' +
			'</p>'
	},
	{
		title: 'متن و جدول',
		image: 'template3.gif',
		description: 'یک عنوان با متن و جدول.',
		html: '<div style="width: 80%">' +
			'<h3>' +
				'عنوان' +
			'</h3>' +
			'<table style="width:150px;float: right" cellspacing="0" cellpadding="0" border="1">' +
				'<caption style="border:solid 1px black">' +
					'<strong>عنوان جدول</strong>' +
				'</caption>' +
				'<tr>' +
					'<td>&nbsp;</td>' +
					'<td>&nbsp;</td>' +
					'<td>&nbsp;</td>' +
				'</tr>' +
				'<tr>' +
					'<td>&nbsp;</td>' +
					'<td>&nbsp;</td>' +
					'<td>&nbsp;</td>' +
				'</tr>' +
				'<tr>' +
					'<td>&nbsp;</td>' +
					'<td>&nbsp;</td>' +
					'<td>&nbsp;</td>' +
				'</tr>' +
			'</table>' +
			'<p>' +
				'متن را اینجا تایپ کنید' +
			'</p>' +
			'</div>'
	} ]
} );