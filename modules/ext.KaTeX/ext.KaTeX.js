mw.hook('wikipage.content').add(function ($content) {
	renderMathInElement($content.get(0), {
		delimiters: [
			{left: '[math]', right: '[/math]', display: false},
			{left: '[displayMath]', right: '[/displayMath]', display: true}
		],
		throwOnError: false
	});
});
