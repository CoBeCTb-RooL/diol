/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	 config.filebrowserBrowseUrl = '/include/kcfinder/browse.php?opener=ckeditor&type=files';
	   config.filebrowserImageBrowseUrl = '/include/kcfinder/browse.php?opener=ckeditor&type=images';
	   config.filebrowserFlashBrowseUrl = '/include/kcfinder/browse.php?opener=ckeditor&type=flash';
	   config.filebrowserUploadUrl = '/include/kcfinder/upload.php?opener=ckeditor&type=files';
	   config.filebrowserImageUploadUrl = '/include/kcfinder/upload.php?opener=ckeditor&type=images';
	   config.filebrowserFlashUploadUrl = '/include/kcfinder/upload.php?opener=ckeditor&type=flash';
	   
	   //config.skin = 'office2013';
	   config.skin = 'bootstrapck';
	   //config.skin = 'kama';
	   
	   config.toolbarGroups = [
	                   		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
	                   		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
	                   		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
	                   		{ name: 'forms', groups: [ 'forms' ] },
	                   		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
	                   		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
	                   		{ name: 'links', groups: [ 'links' ] },
	                   		{ name: 'insert', groups: [ 'insert' ] },
	                   		{ name: 'styles', groups: [ 'styles' ] },
	                   		{ name: 'colors', groups: [ 'colors' ] },
	                   		{ name: 'tools', groups: [ 'tools' ] },
	                   		{ name: 'others', groups: [ 'others' ] },
	                   		{ name: 'about', groups: [ 'about' ] }
	                   	];

	                   	config.removeButtons = 'Save,NewPage,Print,Cut,Copy,Paste,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,BidiLtr,BidiRtl,Language,Smiley,PageBreak,Iframe,Undo,Redo';
	   
};
