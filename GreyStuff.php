<?php
/**
 * GreyStuff skin - created from the bones of monobook and nimbus
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Skins
 * @author Calimonius the Estrange
 * @date 2013
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}

// Skin credits that will show up on Special:Version

$wgExtensionCredits['skin'][] = array(
	'path' => __FILE__,
	'name' => 'GreyStuff skin',
	'version' => '0.muffin.11',
	'author' => array( 'Calimonius the Estrange' ),
	'descriptionmsg' => 'greystuff-desc',
);

$skinID = basename( dirname( __FILE__ ) );
$dir = dirname( __FILE__ ) . '/';

# Autoload the skin class, make it a valid skin, set up i18n

# The first instance must be strtolower()ed so that useskin=nimbus works and
# so that it does *not* force an initial capital (i.e. we do NOT want
# useskin=GreyStuff) and the second instance is used to determine the name of
# *this* file.
$wgValidSkinNames[strtolower( $skinID )] = 'GreyStuff';

$wgAutoloadClasses['SkinGreyStuff'] = $dir . 'GreyStuff.skin.php';
$wgExtensionMessagesFiles['SkinGreyStuff'] = $dir . 'GreyStuff.i18n.php';
$wgResourceModules['skins.greystuff'] = array(
	'styles' => array(
		// MonoBook also loads these
		'skins/common/commonContent.css' => array( 'media' => 'screen' ),
		// Styles custom to the GreyStuff skin
		'skins/greystuff/main.css' => array( 'media' => 'screen' )
	),
	'scripts' => '',
	'position' => 'top'
);
