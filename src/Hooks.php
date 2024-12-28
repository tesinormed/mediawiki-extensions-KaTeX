<?php

namespace MediaWiki\Extension\KaTeX;

use MediaWiki\Config\Config;
use MediaWiki\Config\ConfigFactory;
use MediaWiki\Hook\ParserFirstCallInitHook;
use MediaWiki\Html\Html;
use MediaWiki\Parser\Parser;
use PPFrame;

class Hooks implements ParserFirstCallInitHook {
	private Config $extensionConfig;

	public function __construct( ConfigFactory $configFactory ) {
		$this->extensionConfig = $configFactory->makeConfig( 'katex' );
	}

	/** @inheritDoc */
	public function onParserFirstCallInit( $parser ): void {
		$parser->setHook( 'tex', [ $this, 'renderTag' ] );
		$parser->setHook( 'quantity', [ $this, 'renderQuantityTag' ] );
		// compatibility with Extension:Math
		$parser->setHook( 'math', [ $this, 'renderTag' ] );
		$parser->setHook( 'ce', [ $this, 'renderChemistryTag' ] );
		$parser->setHook( 'chem', [ $this, 'renderChemistryTag' ] );
	}

	/**
	 * @param null|string $text the text to render
	 * @param array $params parameters given to the parser tag
	 * @param Parser $parser the {@link Parser} instance
	 * @param PPFrame $frame
	 * @return string|array the outputted wikitext / HTML
	 */
	public function renderTag( ?string $text, array $params, Parser $parser, PPFrame $frame ): string|array {
		if ( $text === null ) {
			// nothing to render
			return [ '', 'markerType' => 'nowiki' ];
		}

		// add KaTeX to the page
		$parser->getOutput()->addModules( [ 'ext.KaTeX' ] );
		$parser->getOutput()->setJsConfigVar( 'wgKaTeXErrorColor', $this->extensionConfig->get( 'KaTeXErrorColor' ) );
		$parser->getOutput()->setJsConfigVar( 'wgKaTeXOutput', $this->extensionConfig->get( 'KaTeXOutput' ) );

		// HTML tag attributes
		$elementClasses = [ 'mw-KaTeX' ];
		if ( array_key_exists( 'mode', $params ) && $params['mode'] == 'display' ) {
			$elementClasses[] = 'mw-KaTeX-displayMode';
		}
		if ( array_key_exists( 'leqno', $params ) ) {
			$elementClasses[] = 'mw-KaTeX-leqno';
		}
		if ( array_key_exists( 'fleqn', $params ) ) {
			$elementClasses[] = 'mw-KaTeX-fleqn';
		}

		// outputted HTML
		$element = Html::element(
			'span',
			[ 'class' => $elementClasses ],
			// trim any whitespace around the actual TeX so we don't get errors
			trim( $text )
		);

		// make sure this won't get mangled
		return [ $element, 'markerType' => 'nowiki' ];
	}

	public function renderChemistryTag( ?string $text, array $params, Parser $parser, PPFrame $frame ): string|array {
		// from mhchem
		return $this->renderTag( '\ce{' . $text . '}', $params, $parser, $frame );
	}

	public function renderQuantityTag( ?string $text, array $params, Parser $parser, PPFrame $frame ): string|array {
		// from mhchem
		return $this->renderTag( '\pu{' . $text . '}', $params, $parser, $frame );
	}
}
