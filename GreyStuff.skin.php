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

if ( !defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @ingroup Skins
 */
class SkinGreyStuff extends SkinTemplate {
	public $skinname = 'greystuff', $stylename = 'greystuff',
		$template = 'GreyStuffTemplate', $useHeadElement = true;

	/**
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ) {
		global $wgVersion;

		parent::setupSkinUserCss( $out );

		// Add css
		$out->addModuleStyles( array (
			'mediawiki.skinning.content.externallinks',
			'skins.greystuff'
		) );
	}
}
