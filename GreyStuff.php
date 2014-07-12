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
	die( 'Not a valid entry point.' );
}

# Skin credits that will show up on Special:Version
$wgExtensionCredits['skin'][] = array(
	'path' => __FILE__,
	'name' => 'GreyStuff skin',
	'version' => '1.0.2',
	'author' => array( 'Calimonius the Estrange' ),
	'descriptionmsg' => 'greystuff-desc',
	'url' => 'https://www.mediawiki.org/wiki/Skin:GreyStuff'
);

# Autoload the skin class, make it a valid skin, set up i18n, set up CSS & JS
# (via ResourceLoader)
$skinID = basename( dirname( __FILE__ ) );
$dir = dirname( __FILE__ ) . '/';

# The first instance must be strtolower()ed so that useskin=aurora works and
# so that it does *not* force an initial capital (i.e. we do NOT want
# useskin=greystuff) and the second instance is used to determine the name of
# *this* file.
$wgValidSkinNames[strtolower( $skinID )] = 'GreyStuff';

$wgAutoloadClasses['SkinGreyStuff'] = $dir . 'GreyStuff.skin.php';
$wgExtensionMessagesFiles['SkinGreyStuff'] = $dir . 'GreyStuff.i18n.php';
$wgResourceModules['skins.greystuff'] = array(
	'styles' => array(
		'skins/GreyStuff/resources/normalise.css',
		'skins/GreyStuff/resources/externallinks.css',
		'skins/GreyStuff/resources/main.less',
	),
	'scripts' => '',
	'position' => 'top'
);

