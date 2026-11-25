/**
 * Enregistre les blocs dynamiques DSITD dans l'éditeur.
 * Aperçu rendu par le serveur (ServerSideRender) : aucun build nécessaire.
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var ServerSideRender = wp.serverSideRender;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var RangeControl = wp.components.RangeControl;
	var Disabled = wp.components.Disabled;

	( window.dsitdBlocks || [] ).forEach( function ( name ) {
		wp.blocks.registerBlockType( name, {
			edit: function ( props ) {
				var blockProps = useBlockProps();
				var controls = null;
				if ( typeof props.attributes.nombre !== 'undefined' ) {
					controls = el(
						InspectorControls,
						null,
						el(
							PanelBody,
							{ title: 'Réglages' },
							el( RangeControl, {
								label: "Nombre d'éléments",
								min: 1,
								max: 10,
								value: props.attributes.nombre,
								onChange: function ( v ) {
									props.setAttributes( { nombre: v } );
								},
							} )
						)
					);
				}
				return el(
					'div',
					blockProps,
					controls,
					el(
						Disabled,
						null,
						el( ServerSideRender, {
							block: name,
							attributes: props.attributes,
							urlQueryArgs: props.context && props.context.postId ? { post_id: props.context.postId } : {},
						} )
					)
				);
			},
			save: function () {
				return null;
			},
		} );
	} );
} )( window.wp );
