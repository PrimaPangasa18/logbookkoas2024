/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */
 CKEDITOR.editorConfig = function( config ) {
 	config.toolbar = [
 		{ name: 'document', items: [ 'Source', '-', 'NewPage', 'Preview', 'Print' ] },
 		{ name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
 		{ name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll' ] },
    { name: 'paragraph', items: [ 'NumberedList', 'BulletedList','-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
 		{ name: 'insert', items: [ 'Table', 'SpecialChar' ] },
 		'/',
 		{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
 		{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
    { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] }
 	];
  config.forcePasteAsPlainText = true;



  config.height='150px';
  config.startupFocus = true;
 };
CKEDITOR.config.uiColor = '#A3E3FF';
CKEDITOR.config.extraPlugins = 'confighelper';
CKEDITOR.config.removePlugins = 'elementspath';
CKEDITOR.config.autoParagraph = false;
CKEDITOR.config.extraPlugins = 'htmlwriter';
CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
