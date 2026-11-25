/**
 * Panneau « Informations DSITD » : champs propres à chaque type de contenu.
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var PluginDocumentSettingPanel = ( wp.editor && wp.editor.PluginDocumentSettingPanel ) || wp.editPost.PluginDocumentSettingPanel;
	var TextControl = wp.components.TextControl;
	var SelectControl = wp.components.SelectControl;
	var useSelect = wp.data.useSelect;
	var useEntityProp = wp.coreData.useEntityProp;

	var champs = {
		dsitd_plateforme: [
			{
				key: 'dsitd_statut',
				label: 'Statut',
				type: 'select',
				options: [ '', 'En production', 'Phase pilote', 'Accès réservé', 'En projet' ],
			},
			{ key: 'dsitd_public', label: 'Pour qui ?', type: 'text' },
			{ key: 'dsitd_url_acces', label: "Adresse d'accès (https://…)", type: 'url' },
			{ key: 'dsitd_url_tutoriel', label: 'Lien du tutoriel', type: 'url' },
		],
		dsitd_astuce: [ { key: 'dsitd_duree', label: 'Durée (minutes)', type: 'number' } ],
		dsitd_alerte: [
			{
				key: 'dsitd_flash_jours',
				label: 'Jours dans le bandeau « Flash DSITD » (0 = aucun)',
				type: 'number',
			},
		],
	};

	function Panneau() {
		var postType = useSelect( function ( select ) {
			return select( 'core/editor' ).getCurrentPostType();
		}, [] );
		var entity = useEntityProp( 'postType', postType, 'meta' );
		var meta = entity[ 0 ] || {};
		var setMeta = entity[ 1 ];
		var liste = champs[ postType ];
		if ( ! liste ) {
			return null;
		}
		return el(
			PluginDocumentSettingPanel,
			{ name: 'dsitd-infos', title: 'Informations DSITD', initialOpen: true },
			liste.map( function ( c ) {
				var valeur = meta[ c.key ];
				var onChange = function ( v ) {
					var maj = {};
					maj[ c.key ] = c.type === 'number' ? parseInt( v || 0, 10 ) : v;
					setMeta( Object.assign( {}, meta, maj ) );
				};
				if ( c.type === 'select' ) {
					return el( SelectControl, {
						key: c.key,
						label: c.label,
						value: valeur || '',
						options: c.options.map( function ( o ) {
							return { label: o || '—', value: o };
						} ),
						onChange: onChange,
						__nextHasNoMarginBottom: true,
					} );
				}
				return el( TextControl, {
					key: c.key,
					label: c.label,
					type: c.type,
					value: valeur === undefined || valeur === null ? '' : String( valeur ),
					onChange: onChange,
					__nextHasNoMarginBottom: true,
				} );
			} )
		);
	}

	wp.plugins.registerPlugin( 'dsitd-meta-panel', { render: Panneau } );
} )( window.wp );
