mw.hook( 'wikipage.content' ).add( ( $content ) => {
	$content.find( '.mw-KaTeX' ).each( function () {
		const elementClasses = this.className.split( ' ' );
		const katexOptions = {
			// user-specified
			displayMode: elementClasses.includes( 'mw-KaTeX-displayMode' ),
			leqno: elementClasses.includes( 'mw-KaTeX-leqno' ),
			fleqn: elementClasses.includes( 'mw-KaTeX-fleqn' ),
			// styling
			errorColor: mw.config.get( 'wgKaTeXErrorColor' ),
			// input and output
			output: mw.config.get( 'wgKaTeXOutput' ),
			trust: false,
			throwOnError: false
		};
		this.outerHTML = katex.renderToString( this.textContent, katexOptions )
			.replaceAll("color:transparent;", "color:transparent;visibility:hidden;"); // https://github.com/KaTeX/KaTeX/issues/3668
	} );
} );
