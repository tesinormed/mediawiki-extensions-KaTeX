<?php

namespace MediaWiki\Extension\KaTeX;

use MediaWiki\Hook\ParserFirstCallInitHook;
use MediaWiki\Parser\Parser;
use PPFrame;

class Hooks implements ParserFirstCallInitHook {
	/** @inheritDoc */
	public function onParserFirstCallInit( $parser ) {
		$parser->setHook( 'math', [ $this, 'renderMath' ] );
	}

	/**
	 * @param null|string $text the TeX to render
	 * @param array $params parameters given to the parser tag
	 * @param Parser $parser the {@link Parser} instance
	 * @param PPFrame $frame
	 * @return string|array the outputted wikitext / HTML
	 */
	public function renderMath( ?string $text, array $params, Parser $parser, PPFrame $frame ): string|array {
		$parser->getOutput()->addModules( [ 'KaTeX', 'ext.KaTeX' ] );
		if ( array_key_exists( 'mode', $params ) && $params['mode'] == 'display' ) {
			return "[displayMath]{$text}[/displayMath]";
		} else {
			return "[math]{$text}[/math]";
		}
	}
}
