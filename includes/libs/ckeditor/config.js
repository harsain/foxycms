/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	config.toolbar = 'Awcm';
	config.toolbar_Awcm =
	[
		{ name: 'document', items : [ 'Source' ] },
		{ name: 'clipboard', items : [ 'PasteText','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','SelectAll','Scayt' ] },
		{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','Iframe' ] },
         { name: 'links', items : [ 'Link','Unlink' ] },
         { name: 'tools', items : [ 'Maximize' ] },
		{ name: 'styles', items : [ 'Font','FontSize' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','RemoveFormat'
		,'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','BidiLtr','BidiRtl' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','Outdent','Indent','Blockquote' ] }
		
	];
	config.uiColor = '#e1e1e1';
};
