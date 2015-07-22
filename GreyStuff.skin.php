<?php

/**
 * GreyStuff skin stuff.
 *
 * @file
 * @ingroup Skins
 * @author Calimonius the Estrange
 * @author Jack Phoenix
 * @authors Whoever wrote monobook
 * @date 2014
 */

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @ingroup Skins
 */
class SkinGreyStuff extends SkinTemplate {
	public $skinname = 'greystuff', $stylename = 'greystuff',
		$template = 'GreyStuffTemplate', $useHeadElement = true;

	public function initPage( OutputPage $out ) {
		parent::initPage( $out );

		$out->addMeta( 'viewport', 'width=device-width' );

		// Add JS
		$out->addModules( 'skins.greystuff.js' );
	}

	/**
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );

		// Add CSS
		$out->addModuleStyles( array (
			'mediawiki.skinning.content.externallinks',
			'skins.greystuff'
		) );
	}
}
