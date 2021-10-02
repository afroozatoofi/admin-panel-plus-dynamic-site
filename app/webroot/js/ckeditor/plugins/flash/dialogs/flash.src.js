/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * @editor Farhad Enayati 
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

( function() {
	// It is possible to set things in three different places.
	// 1. As attributes in the object tag.
	// 2. As param tags under the object tag.
	// 3. As attributes in the embed tag.
	// It is possible for a single attribute to be present in more than one place.
	// So let's define a mapping between a sementic attribute and its syntactic
	// equivalents.
	// Then we'll set and retrieve attribute values according to the mapping,
	// instead of having to check and set each syntactic attribute every time.
	//
	// Reference: http://kb.adobe.com/selfservice/viewContent.do?externalId=tn_12701
	var ATTRTYPE_OBJECT = 1,
		ATTRTYPE_PARAM = 2;		
	
	//var REST = '/'+CKEDITOR.getUrl('').split('://')[1].split('/',2)[1];
	var PLAYER = '/flash/player_flv_multi.swf';	
	var CONFIG = '/flash/config.txt?t=1';
	//PLAYER = PLAYER.replace(PLAYER.split('/',1)[0],'');
	//PLAYER = PLAYER.split('?')[0]
	
	var FLASH = 'flv=$FILE$&config='+CONFIG+'&loop=$LOOP$&autoplay=$PLAY$';

	var attributesMap = {			
		id: [ {
			type: ATTRTYPE_OBJECT, name: 'id'
		} ],					
		src: [ {
			type: ATTRTYPE_PARAM, name: 'src'
		}],
		align: [ {
			type: ATTRTYPE_OBJECT, name: 'align'
		} ],		
		width: [ {
			type: ATTRTYPE_OBJECT, name: 'width', 'default': '700'
		} ],
		height: [ {
			type: ATTRTYPE_OBJECT, name: 'height', 'default': '400'
		}],
		hSpace: [ {
			type: ATTRTYPE_OBJECT, name: 'hSpace'
		}],
		vSpace: [ {
			type: ATTRTYPE_OBJECT, name: 'vSpace'
		}],
		style: [ {
			type: ATTRTYPE_OBJECT, name: 'style'
		}],
		type: [ {
			type: ATTRTYPE_OBJECT, name: 'type' , 'default' : 'application/x-shockwave-flash'
		} ],
		data: [ {
			type: ATTRTYPE_OBJECT, name: 'data' , 'default' : PLAYER
		} ]		
	};
	
	var names = ['movie','play','loop','FlashVars','allowFullScreen'];
	for ( var i = 0; i < names.length; i++ )
		attributesMap[ names[ i ] ] = [ {
		type: ATTRTYPE_PARAM, name: names[ i ]
	} ];
	attributesMap['movie'][ 0 ][ 'default' ] = PLAYER;
	attributesMap['FlashVars'][ 0 ][ 'default' ] = '-';		
	attributesMap['allowFullScreen'][ 0 ][ 'default' ] = 'true';		
	
	var defaultToPixel = CKEDITOR.tools.cssLength;

	function loadValue( objectNode, embedNode, paramMap ) {
		var attributes = attributesMap[ this.id ];
		if ( !attributes )
			return;

		var isCheckbox = ( this instanceof CKEDITOR.ui.dialog.checkbox );		
		for ( var i = 0; i < attributes.length; i++ ) {
			var attrDef = attributes[ i ];	
			switch ( attrDef.type ) {
				case ATTRTYPE_OBJECT:
					if ( !objectNode )
						continue;
					if ( objectNode.getAttribute( attrDef.name ) !== null ) {
						var value = objectNode.getAttribute( attrDef.name );											
						this.setValue( value );						
					}
					break;
				case ATTRTYPE_PARAM:
					if ( !objectNode )
						continue;					
					if ( attrDef.name in paramMap ) {
						value = paramMap[ attrDef.name ];
						if ( isCheckbox )
							this.setValue( value.toLowerCase() == '1' );
						else
							this.setValue( value );
						return;
					} else if ( isCheckbox )
						this.setValue( !!attrDef[ 'default' ] );								
			}
		}
	}

	function commitValue( objectNode, embedNode, paramMap ) {		
		var attributes = attributesMap[ this.id ];
		if ( !attributes )
			return;		
		var isRemove = ( this.getValue() === ''),
			isCheckbox = ( this instanceof CKEDITOR.ui.dialog.checkbox );
				
		for ( var i = 0; i < attributes.length; i++ ) {
			var attrDef = attributes[ i ];			
			switch ( attrDef.type ) {
				case ATTRTYPE_OBJECT:
					if ( !objectNode )
						continue;					
					var value = this.getValue();
					if (isRemove && !attrDef[ 'default' ])
						objectNode.removeAttribute( attrDef.name );
					else if(!value && attrDef[ 'default' ])
						objectNode.setAttribute( attrDef.name, attrDef[ 'default' ] );
					else
						objectNode.setAttribute( attrDef.name, value );
					break;
				case ATTRTYPE_PARAM:
					if ( !objectNode )
						continue;
					value = this.getValue();
					if(!value && attrDef[ 'default' ])
						value = attrDef[ 'default' ];					
					if(value === true && isCheckbox){
						value = '1';
					}					
					if ( isRemove && !attrDef[ 'default' ]) {
						if (attrDef.name in paramMap )							
							paramMap[ attrDef.name ].remove();
					} else {			
						if(attrDef.name == 'FlashVars'){
							var play = '0',loop = '0',file='';
							
							for(ii = 0;ii<objectNode.getChildCount();ii++){
								item = objectNode.getChildren().getItem(ii)['$'];								
								n = item.getAttribute('name');
								v = item.getAttribute('value');
								switch(n){
								case 'play':
									play = v;
									break;
								case 'loop':
									loop = v;
									break;
								case 'src':
									file = v;																		
									break;								
								}
								value = FLASH.replace('$PLAY$',play).replace('$LOOP$',loop).replace('$FILE$',file); 
							}							
						}						
						if ( attrDef.name in paramMap ){							
							paramMap[ attrDef.name ].setAttribute( 'value', value );
						} else {							
							var param = CKEDITOR.dom.element.createFromHtml( '<cke:param></cke:param>', objectNode.getDocument() );							
							param.setAttributes( { name: attrDef.name, value: value } );
							if(value){									
								if ( objectNode.getChildCount() < 1 )
									param.appendTo( objectNode );
								else
									param.insertBefore( objectNode.getFirst() );								
							}
						}
					}					
			}					
		}
	}

	CKEDITOR.dialog.add( 'flash', function( editor ) {				
		var previewPreloader,
			previewAreaHtml = '<div>' + CKEDITOR.tools.htmlEncode( editor.lang.common.preview ) + '<br/><br/>' +			
			'<div id="cke_FlashPreviewBox' + CKEDITOR.tools.getNextNumber() + '" class="FlashPreviewBox"></div></div>';

		return {
			title: editor.lang.flash.title,
			minWidth: 420,
			minHeight: 310,
			onShow: function() {
				// Clear previously saved elements.				
				this.fakeImage = this.objectNode = this.embedNode = null;
				previewPreloader = new CKEDITOR.dom.element( 'embed', editor.document );

				// Try to detect any embed or object tag that has Flash parameters.
				var fakeImage = this.getSelectedElement();
				if ( fakeImage && fakeImage.data( 'cke-real-element-type' ) && fakeImage.data( 'cke-real-element-type' ) == 'flash' ) {
					this.fakeImage = fakeImage;

					var realElement = editor.restoreRealElement( fakeImage ),
						objectNode = null,						
						paramMap = {};
					if ( realElement.getName() == 'cke:object' ) {
						objectNode = realElement;											
						var paramList = objectNode.getElementsByTag( 'param', 'cke' );
						for ( var i = 0, length = paramList.count(); i < length; i++ ) {
							var item = paramList.getItem( i ),
								name = item.getAttribute( 'name' ),
								value = item.getAttribute( 'value' );
							paramMap[ name ] = value;
						}									
					}
					this.objectNode = objectNode;					
					this.setupContent( objectNode, null, paramMap, fakeImage );					
				}
			},
			onOk: function() {
				var objectNode = null,					
					paramMap = null;				
				
				if ( !this.fakeImage ) {
					objectNode = CKEDITOR.dom.element.createFromHtml( '<cke:object></cke:object>', editor.document );
				} else {
					objectNode = this.objectNode;					
				}
				
				paramMap = {};							
				var paramList = objectNode.getElementsByTag( 'param', 'cke' );
				for ( var i = 0, length = paramList.count(); i < length; i++ ){					
					var item = paramList.getItem( i ),
						name = item.getAttribute( 'name' ),
						value = item.getAttribute( 'value' );					
					paramMap[name] = paramList.getItem( i );
				}
				var extraStyles = {},
					extraAttributes = {};
				this.commitContent( objectNode, null, paramMap, extraStyles, extraAttributes );
				
				paramList = objectNode.getElementsByTag( 'param', 'cke' )
				var source = '';
				for ( var i = 0, length = paramList.count(); i < length; i++ ){					
					var item = paramList.getItem( i );
					if(item.getAttribute( 'name' ) == 'src'){
						source = item.getAttribute( 'value' );
						break;
					}					
				}				
				// Refresh the fake image.				
				var newFakeImage = editor.createFakeElement( objectNode, 'cke_flash', 'flash', true );				
				newFakeImage.setAttributes( extraAttributes );
				newFakeImage.setStyles( extraStyles );
				if ( this.fakeImage ) {
					newFakeImage.replace( this.fakeImage );
					editor.getSelection().selectElement( newFakeImage );
				} else
					editor.insertElement( newFakeImage );
				if(source){				
					var prg = CKEDITOR.dom.element.createFromHtml('<p></p>');
					var img = '<img alt="دریافت ویدئو" >';
					var linkElement = CKEDITOR.dom.element.createFromHtml('<a class="post-video-link" href="'+source+'" target="_blank">'+img+'</a>');					
					
					editor.insertElement(prg);
					editor.insertElement(linkElement);
					editor.insertElement(prg);
				}
			},

			onHide: function() {
				this.objectNode = null;
				if ( this.preview )
					this.preview.setHtml( '' );
			},

			contents: [
				{
				id: 'info',
				label: editor.lang.common.generalTab,
				accessKey: 'I',
				elements: [
					{
					type: 'vbox',
					padding: 0,
					children: [
						{
						type: 'hbox',
						widths: [ '280px', '110px' ],
						align: 'right',
						children: [
							{
							id: 'src',
							type: 'text',
							style: 'width:410px',
							label: editor.lang.common.url,
							required: true,
							validate: CKEDITOR.dialog.validate.notEmpty( editor.lang.flash.validateSrc ),
							setup: loadValue,
							commit: commitValue,
							onLoad: function() {
								var dialog = this.getDialog(),
									updatePreview = function( src ) {
										// Query the preloader to figure out the url impacted by based href.
										previewPreloader.setAttribute( 'src', src );
										var html = '<object type="application/x-shockwave-flash" data="'+PLAYER+'" width="100%" height="100%">';
										html += '<param name="allowFullScreen" value="true" />';
										html += '<param name="movie" value="'+PLAYER+'" />';
										html += '<param name="FlashVars" value="flv='+(CKEDITOR.tools.htmlEncode(src))+'&config='+CONFIG+'" /></object>';
										dialog.preview.setHtml(html);
									};
								// Preview element
								dialog.preview = dialog.getContentElement( 'info', 'preview' ).getElement().getChild( 3 );

								// Sync on inital value loaded.
								this.on( 'change', function( evt ) {
									if ( evt.data && evt.data.value )
										updatePreview( evt.data.value );
								} );
								// Sync when input value changed.
								this.getInputElement().on( 'change', function( evt ) {
									updatePreview( this.getValue() );
								}, this );
							}
						},
							{
							type: 'button',
							id: 'browse',
							filebrowser: 'info:src',
							hidden: true,
							style: 'display:inline-block;margin-top:14px;',
							label: editor.lang.common.browseServer
						}
						]
					}
					]
				},
					{
					type: 'hbox',
					widths: [ '25%', '25%', '25%', '25%', '25%' ],
					children: [
						{
						type: 'text',
						id: 'width',
						requiredContent: 'embed[width]',
						style: 'width:95px',
						label: editor.lang.common.width,
						validate: CKEDITOR.dialog.validate.htmlLength( editor.lang.common.invalidHtmlLength.replace( '%1', editor.lang.common.width ) ),
						setup: loadValue,
						commit: commitValue
					},
						{
						type: 'text',
						id: 'height',
						requiredContent: 'embed[height]',
						style: 'width:95px',
						label: editor.lang.common.height,
						validate: CKEDITOR.dialog.validate.htmlLength( editor.lang.common.invalidHtmlLength.replace( '%1', editor.lang.common.height ) ),
						setup: loadValue,
						commit: commitValue
					},
						{
						type: 'text',
						id: 'hSpace',
						requiredContent: 'embed[hspace]',
						style: 'width:95px',
						label: editor.lang.flash.hSpace,
						validate: CKEDITOR.dialog.validate.integer( editor.lang.flash.validateHSpace ),
						setup: loadValue,
						commit: commitValue
					},
						{
						type: 'text',
						id: 'vSpace',
						requiredContent: 'embed[vspace]',
						style: 'width:95px',
						label: editor.lang.flash.vSpace,
						validate: CKEDITOR.dialog.validate.integer( editor.lang.flash.validateVSpace ),
						setup: loadValue,
						commit: commitValue
					},
					{
						type: 'text',
						id: 'type',
						style: 'display:none',												
						setup: loadValue,							
						commit: commitValue							
					},
					{
						type: 'text',
						id: 'data',
						style: 'display:none',												
						setup: loadValue,							
						commit: commitValue							
					}
					]
				},

					{
					type: 'vbox',
					children: [
						{
						type: 'html',
						id: 'preview',
						style: 'width:95%;',
						html: previewAreaHtml
					}
					]
				}
				]
			},
				{
				id: 'Upload',
				hidden: true,
				filebrowser: 'uploadButton',
				label: editor.lang.common.upload,
				elements: [
					{
					type: 'file',
					id: 'upload',
					label: editor.lang.common.upload,
					size: 38
				},
					{
					type: 'fileButton',
					id: 'uploadButton',
					label: editor.lang.common.uploadSubmit,
					filebrowser: 'info:src',
					'for': [ 'Upload', 'upload' ]
				}
				]
			},
				{
				id: 'properties',
				label: editor.lang.flash.propertiesTab,
				elements: [															
					{
					type: 'fieldset',
					label: CKEDITOR.tools.htmlEncode( editor.lang.flash.flashvars ),
					children: [
						{
						type: 'vbox',
						padding: 0,
						children: [							
							{
							type: 'checkbox',
							id: 'play',
							label: editor.lang.flash.chkPlay,							
							setup: loadValue,
							commit: commitValue
						},
							{
							type: 'checkbox',
							id: 'loop',
							label: editor.lang.flash.chkLoop,							
							setup: loadValue,							
							commit: commitValue
						},
						{
							type: 'text',
							id: 'movie',
							style: 'display:none',												
							setup: loadValue,							
							commit: commitValue
						},
						{
							type: 'text',
							id: 'allowFullScreen',
							style: 'display:none',												
							setup: loadValue,							
							commit: commitValue
						},						
						{
							type: 'text',
							id: 'FlashVars',
							style: 'display:none',												
							setup: loadValue,							
							commit: commitValue							
						}						
						]
					}
					]
				}
				]
			},
				{
				id: 'advanced',
				label: editor.lang.common.advancedTab,
				elements: [
					{
					type: 'hbox',
					children: [
						{
						type: 'text',
						id: 'id',
						requiredContent: 'object[id]',
						label: editor.lang.common.id,
						setup: loadValue,
						commit: commitValue
					}
					]
				}, {					
					type: 'text',
					id: 'class',
					requiredContent: 'embed(cke-xyz)',
					label: editor.lang.common.cssClass,
					setup: loadValue,
					commit: commitValue					
				},
					{
					type: 'text',
					id: 'style',
					requiredContent: 'embed{cke-xyz}',
					validate: CKEDITOR.dialog.validate.inlineStyle( editor.lang.common.invalidInlineStyle ),
					label: editor.lang.common.cssStyle,
					setup: loadValue,
					commit: commitValue
				}				
				]
			}
			]
		};
	} );
} )();
