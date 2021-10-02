/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights
 *          reserved. For licensing, see LICENSE.md or
 *          http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function(config) {
	// Define changes to default configuration here. For example:
	CKEDITOR.config.toolbar_Basic = [
			[ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-','Templates' ],
			[ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-',
					'SpellChecker' ],
			[ 'Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll'],			
			'/',
			[ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript',
					'Superscript' ,'RemoveFormat'],
			[ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent',
					'Blockquote','BidiRtl','BidiLtr','CreateDiv' ],
			[ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
			[ 'Link', 'Unlink', 'Anchor' ],
			[ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley',
					'SpecialChar', 'PageBreak','Iframe' ], '/',
			[ 'Styles', 'Format', 'Font', 'FontSize' ],
			[ 'TextColor', 'BGColor' ],
			[ 'Maximize', 'ShowBlocks', '-', 'About' ],
			[ 'abbr', 'inserthtml']];
				
	config.toolbar = 'Basic';
	config.language = 'fa';
	config.uiColor = '#CCEAEE';	
	config.contentsLangDirection = 'rtl';
	config.allowedContent = true;
	CKEDITOR.timestamp='AF40';
};
