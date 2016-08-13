(function() {
    tinymce.PluginManager.add('lgc_shortcodes_button', function( editor, url ) {
        editor.addButton( 'lgc_shortcodes_button', {
			title: lgc_add_columns,
            icon: 'icon dashicons-text',
            onclick: function() {
				editor.windowManager.open( {
					title: lgc_columns,
					body: [
					{
						type: 'listbox', 
						name: 'desktop_grid', 
						label: lgc_desktop, 
						'values': [
							{text: '50%', value: '50'},
							{text: '5%', value: '5'},
							{text: '10%', value: '10'},
							{text: '15%', value: '15'},
							{text: '20%', value: '20'},
							{text: '25%', value: '25'},
							{text: '30%', value: '30'},
							{text: '33%', value: '33'},
							{text: '35%', value: '35'},
							{text: '40%', value: '40'},
							{text: '45%', value: '45'},
							{text: '50%', value: '50'},
							{text: '55%', value: '55'},
							{text: '60%', value: '60'},
							{text: '65%', value: '65'},
							{text: '70%', value: '70'},
							{text: '75%', value: '75'},
							{text: '80%', value: '80'},
							{text: '85%', value: '85'},
							{text: '90%', value: '90'},
							{text: '95%', value: '95'},
							{text: '100%', value: '100'}
						]
					},
					{
						type: 'listbox', 
						name: 'tablet_grid', 
						label: lgc_tablet, 
						'values': [
							{text: '50%', value: '50'},
							{text: '5%', value: '5'},
							{text: '10%', value: '10'},
							{text: '15%', value: '15'},
							{text: '20%', value: '20'},
							{text: '25%', value: '25'},
							{text: '30%', value: '30'},
							{text: '33%', value: '33'},
							{text: '35%', value: '35'},
							{text: '40%', value: '40'},
							{text: '45%', value: '45'},
							{text: '50%', value: '50'},
							{text: '55%', value: '55'},
							{text: '60%', value: '60'},
							{text: '65%', value: '65'},
							{text: '70%', value: '70'},
							{text: '75%', value: '75'},
							{text: '80%', value: '80'},
							{text: '85%', value: '85'},
							{text: '90%', value: '90'},
							{text: '95%', value: '95'},
							{text: '100%', value: '100'}
						]
					},
					{
						type: 'listbox', 
						name: 'mobile_grid', 
						label: lgc_mobile, 
						'values': [
							{text: '100%', value: '100'},
							{text: '5%', value: '5'},
							{text: '10%', value: '10'},
							{text: '15%', value: '15'},
							{text: '20%', value: '20'},
							{text: '25%', value: '25'},
							{text: '30%', value: '30'},
							{text: '33%', value: '33'},
							{text: '35%', value: '35'},
							{text: '40%', value: '40'},
							{text: '45%', value: '45'},
							{text: '50%', value: '50'},
							{text: '55%', value: '55'},
							{text: '60%', value: '60'},
							{text: '65%', value: '65'},
							{text: '70%', value: '70'},
							{text: '75%', value: '75'},
							{text: '80%', value: '80'},
							{text: '85%', value: '85'},
							{text: '90%', value: '90'},
							{text: '95%', value: '95'},
							{text: '100%', value: '100'}
						]
					},
					{
						type: 'textbox',
						name: 'content',
						label: lgc_content,
						multiline: true,
						minWidth: 300,
						minHeight: 100,
					},
					{
						type: 'checkbox',
						name: 'last',
						label: lgc_last
					}
					],
					onsubmit: function( e ) {
						editor.insertContent( '[lgc_column grid="' + e.data.desktop_grid + '" tablet_grid="' + e.data.tablet_grid + '" mobile_grid="' + e.data.mobile_grid + '" last="' + e.data.last + '"]' + e.data.content + '[/lgc_column]');
					}
				});
			}
        });
    });
})();