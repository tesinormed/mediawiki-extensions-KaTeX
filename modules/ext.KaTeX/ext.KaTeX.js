mw.hook( 'wikipage.content' ).add( ( $content ) => {
	$content.find( 'script[type^="math/tex"]' ).each( function () {
		const displayMode = this.getAttribute( 'type' ) === 'math/tex;mode=display';
		const katexOptions = { displayMode: displayMode, throwOnError: false };
		this.outerHTML = katex.renderToString( this.textContent, katexOptions );
	} );
} );
