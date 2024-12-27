<?php

namespace MediaWiki\Extension\KaTeX;

use MediaWiki\Hook\ParserFirstCallInitHook;
use MediaWiki\Parser\Parser;
use PPFrame;

class Hooks implements ParserFirstCallInitHook {
	/** @inheritDoc */
	public function onParserFirstCallInit( $parser ) {
		$parser->setHook( 'math', [ $this, 'renderTag' ] );
		$parser->setHook( 'chem', [ $this, 'renderTag' ] );
	}

	/**
	 * @param null|string $text the text to render
	 * @param array $params parameters given to the parser tag
	 * @param Parser $parser the {@link Parser} instance
	 * @param PPFrame $frame
	 * @return string|array the outputted wikitext / HTML
	 */
	public function renderTag( ?string $text, array $params, Parser $parser, PPFrame $frame ): string|array {
		// add KaTeX and its configuration
		$parser->getOutput()->addModules( [ 'KaTeX', 'ext.KaTeX' ] );

		// trim any whitespace around the actual text
		$text = trim( $text );

		if ( array_key_exists( 'mode', $params ) && $params['mode'] == 'display' ) {
			// using display mode
			return "[displayMath]{$text}[/displayMath]";
		} else {
			// using inline mode
			return "[math]{$text}[/math]";
		}
	}
}
