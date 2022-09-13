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
 *
 */

/**
 * Main skin class
 * @ingroup Skins
 */
class GreyStuffTemplate extends BaseTemplate {

	/**
	 * @inheritDoc
	 */
	public function execute() {
		// Apparently not set by default?
		$this->data['pageLanguage'] = $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();

		// Move some content actions links
		if ( isset( $this->data['content_navigation']['actions']['watch'] ) ) {
			$this->data['content_navigation']['views2']['watch']
				= $this->data['content_navigation']['actions']['watch'];
			unset( $this->data['content_navigation']['actions']['watch'] );
		}
		if ( isset( $this->data['content_navigation']['actions']['unwatch'] ) ) {
			$this->data['content_navigation']['views2']['unwatch']
				= $this->data['content_navigation']['actions']['unwatch'];
			unset( $this->data['content_navigation']['actions']['unwatch'] );
		}
		if ( isset( $this->data['content_navigation']['views']['history'] ) ) {
			$this->data['sidebar']['TOOLBOX']['history'] = $this->data['content_navigation']['views']['history'];
			$this->data['sidebar']['TOOLBOX']['history']['text'] = $this->getMsg( 'greystuff-history' )->text();
			unset( $this->data['content_navigation']['views']['history'] );
		}

		// Open html, body elements, etc
		$html = '';

		$html .= Html::openElement( 'div', [ 'id' => 'globalWrapper' ] );

		$html .= Html::rawElement(
			'div',
			[ 'id' => 'header-container', 'lang' => $this->get( 'userlang' ), 'dir' => $this->get( 'dir' ) ],
			Html::rawElement( 'div', [ 'id' => 'header-top-container' ],
				Html::rawElement( 'div', [ 'id' => 'header-top' ],
					Html::element( 'a', [ 'href' => '#footer-navigation', 'id' => 'jump-to-end' ] ) .
					Html::rawElement( 'div', [ 'id' => 'main-banner' ], $this->getBanner() ) .
					Html::element( 'div', [ 'id' => 'menus-cover' ] ) .
					Html::element( 'div', [ 'id' => 'main-menu-toggle' ] ) .
					Html::element( 'div', [ 'id' => 'personal-menu-toggle' ] ) .
					Html::element( 'div', [ 'id' => 'tools-menu-toggle' ] ) .

					$this->getPersonalNavigation() .
					Html::element( 'div', [ 'class' => 'mobileClear' ] ) .
					$this->getSearch() .
					$this->getClear()
				)
			)
		);

		$html .= $this->getClear();

		$html .= Html::rawElement( 'div', [ 'id' => 'header-navigation-container' ],
			Html::rawElement( 'div', [ 'id' => 'header-navigation' ],
				$this->getMainNavigation()
			)
		);

		$html .= Html::rawElement( 'div', [ 'id' => 'content-container' ],
			Html::rawElement( 'div', [ 'id' => 'content', 'class' => 'mw-body-primary', 'role' => 'main' ],
				Html::element( 'a', [ 'id' => 'top' ] ) .
				$this->getSiteNotice() .
				$this->getSubtitle() .
				$this->getContentHeader() .
				// for double underline on the header
				Html::element( 'div', [ 'id' => 'content-header-inner' ] ) .

				$this->getClear() .

				Html::rawElement( 'div', [ 'id' => 'bodyContent', 'class' => 'mw-body-content' ],
					Html::rawElement( 'div', [ 'id' => 'siteSub' ], $this->getMsg( 'tagline' ) ) .
					$this->get( 'bodytext' ) .
					$this->getClear()
				) .
				$this->getAfterContent()
			)
		);

		$html .= Html::rawElement( 'div', [ 'id' => 'footer' ],
			Html::rawElement( 'div', [ 'id' => 'footer-banner' ], $this->getBanner( 'p-banner-footer' ) ) .
			Html::rawElement( 'div', [ 'id' => 'footer-navigation' ],
				Html::element( 'a', [ 'href' => '#header-container', 'id' => 'return-to-top' ] ) .
				$this->getMainNavigation( 'f' )
			) .
			$this->getClear() .
			$this->getFooterBlock( [ 'id' => 'footer-bottom' ] )
		);

		echo $html;
	}

	/**
	 * Generate a block of navigation links with a header
	 *
	 * Re-copied out of splash, perhaps not the best idea. (Original comment: '<INSERT SCREAMING>')
	 *
	 * @param string $name
	 * @param array|string $content array of links for use with makeListItem, or a block of text
	 * @param null|string|array $msg
	 * @param array $setOptions random crap to rename/do/whatever
	 *
	 * @return string HTML
	 */
	protected function getPortlet( $name, $content, $msg = null, $setOptions = [] ) {
		// random stuff to override with any provided options
		$options = $setOptions + [
			// extra classes/ids
			'id' => 'p-' . $name,
			'class' => [ 'mw-portlet', 'emptyPortlet' => !$content ],
			'extra-classes' => [],
			// what to wrap the body list in, if anything
			'body-wrapper' => 'div',
			'body-id' => '',
			'body-class' => 'mw-portlet-body',
			'body-extra-classes' => [],
			// makeListItem options
			'list-item' => [ 'text-wrapper' => [ 'tag' => 'span' ] ],
			// option to stick arbitrary stuff at the beginning of the ul
			'list-prepend' => '',
			'extra-header' => false,
			'incontentlanguage' => false,
			'prefix' => 'p'
		];

		// Handle the different $msg possibilities
		if ( $msg === null ) {
			$msg = $name;
		} elseif ( is_array( $msg ) ) {
			$msgString = array_shift( $msg );
			$msgParams = $msg;
			$msg = $msgString;
		}
		if ( $options['incontentlanguage'] ) {
			$msgObj = $this->getMsg( $msg )->inContentLanguage();
		} else {
			$msgObj = $this->getMsg( $msg );
		}
		if ( $msgObj->exists() ) {
			if ( isset( $msgParams ) && !empty( $msgParams ) ) {
				$msgString = $this->getMsg( $msg, $msgParams )->parse();
			} else {
				$msgString = $msgObj->parse();
			}
		} else {
			$msgString = htmlspecialchars( $msg );
		}

		$labelId = Sanitizer::escapeIdForAttribute( "{$options['prefix']}-$name-label" );

		if ( is_array( $content ) ) {
			if ( !count( $content ) ) {
				return '';
			}

			$contentText = '';
			if ( $options['extra-header'] ) {
				$contentText .= Html::rawElement( 'h3', [], $msgString );
			}

			$contentText .= Html::openElement( 'ul',
				[ 'lang' => $this->get( 'userlang' ), 'dir' => $this->get( 'dir' ) ]
			);
			$contentText .= $options['list-prepend'];
			foreach ( $content as $key => $item ) {
				$contentText .= $this->makeListItem( $key, $item, $options['list-item'] );
			}
			$contentText .= Html::closeElement( 'ul' );
		} else {
			$contentText = $content;
		}

		// Special handling for role=search and other weird things
		$divOptions = [
			'role' => 'navigation',
			'class' => $this->mergeClasses( $options['class'], $options['extra-classes'] ),
			'id' => Sanitizer::escapeIdForAttribute( $options['id'] ),
			'title' => Linker::titleAttrib( $options['id'] ),
			'aria-labelledby' => $labelId,
		];

		$labelOptions = [
			'id' => $labelId,
			'lang' => $this->get( 'userlang' ),
			'dir' => $this->get( 'dir' )
		];

		// @phan-suppress-next-line PhanSuspiciousValueComparison
		if ( $options['body-wrapper'] !== 'none' ) {
			$bodyDivOptions = [ 'class' => $this->mergeClasses(
				$options['body-class'],
				$options['body-extra-classes']
			) ];
			if ( strlen( $options['body-id'] ) ) {
				$bodyDivOptions['id'] = $options['body-id'];
			}
			$body = Html::rawElement( $options['body-wrapper'], $bodyDivOptions,
				$contentText .
				$this->getSkin()->getAfterPortlet( $name )
			);
		} else {
			$body = $contentText . $this->getSkin()->getAfterPortlet( $name );
		}

		$html = Html::rawElement( 'div', $divOptions,
			Html::rawElement( 'h3', $labelOptions, $msgString ) .
			$body
		);

		return $html;
	}

	/**
	 * Helper function for getPortlet
	 *
	 * Merge all provided css classes into a single array
	 * Account for possible different input methods matching what Html::element stuff takes
	 *
	 * @param string|array $class base portlet/body class
	 * @param string|array $extraClasses any extra classes to also include
	 *
	 * @return array all classes to apply
	 */
	protected function mergeClasses( $class, $extraClasses ) {
		if ( !is_array( $class ) ) {
			$class = [ $class ];
		}
		if ( !is_array( $extraClasses ) ) {
			$extraClasses = [ $extraClasses ];
		}

		return array_merge( $class, $extraClasses );
	}

	/**
	 * Get first heading, with page tool stuff
	 *
	 * @return string html
	 */
	protected function getContentHeader() {
		$html = Html::openElement( 'div', [ 'id' => 'content-header' ] );

		$html .= Html::rawElement(
			'h1',
			[ 'id' => 'firstHeading', 'class' => 'firstHeading', 'lang' => $this->get( 'pageLanguage' ) ],
			$this->get( 'title' )
		);
		$html .= Html::element( 'div', [ 'class' => 'mobileClear' ] ) .
			$this->getIndicators() .
			Html::rawElement( 'div', [ 'id' => 'page-namespaces' ],
				$this->getPortlet( 'namespaces', $this->data['content_navigation']['namespaces'] )
			);

		$pageTools = Html::openElement( 'div', [ 'id' => 'page-tools' ] );
		$pageTools .= $this->getPortlet(
			'views',
			$this->data['content_navigation']['views']
		);
		$pageTools .= $this->getPortlet(
			'actions',
			$this->data['content_navigation']['actions'],
			null,
			[ 'body-extra-classes' => [ 'dropdown' ] ]
		);
		if ( isset( $this->data['content_navigation']['views2'] ) ) {
			$pageTools .= $this->getPortlet(
				'more-actions',
				$this->data['content_navigation']['views2'],
				'actions'
			);
		}
		$pageTools .= Html::closeElement( 'div' );

		$html .= $pageTools;
		$html .= Html::closeElement( 'div' );

		return $html;
	}

	/**
	 * Get all main navigation portlets, sectioned into navigation and navigation-tools divs
	 *
	 * @param string $idPrefix
	 *
	 * @return string html
	 */
	protected function getMainNavigation( $idPrefix = '' ) {
		$sidebar = $this->data['sidebar'];
		$toolbox = $sidebar['TOOLBOX'];
		$languageUrls = $sidebar['LANGUAGES'];
		$sidebar['SEARCH'] = false;
		$sidebar['TOOLBOX'] = false;
		$sidebar['LANGUAGES'] = false;

		// Add some extra links to the toolbox
		$skin = $this->getSkin();
		$title = $skin->getTitle();
		$toolbox['purge'] = [
			'text' => $this->getMsg( 'greystuff-purge' )->text(),
			'id' => 't-purge',
			'href' => $title->getLocalURL( [ 'action' => 'purge' ] ),
			'rel' => 'nofollow'
		];

		$html = '';
		if ( $idPrefix !== '' ) {
			foreach ( $toolbox as $item => $details ) {
				$toolbox[$item]['id'] = $idPrefix . $details['id'];
			}
		}

		// Main navigation, from [[MediaWiki:Sidebar]]
		$mainBlock = '';
		foreach ( $sidebar as $name => $content ) {
			if ( $content === false ) {
				continue;
			}
			// Numeric strings gets an integer when set as key, cast back - T73639
			$name = (string)$name;

			if ( $idPrefix !== '' ) {
				foreach ( $content as $item => $details ) {
					$content[$item]['id'] = $idPrefix . $details['id'];
				}
			}
			$mainBlock .= $this->getPortlet( $name, $content, null, [
				'body-extra-classes' => [ 'dropdown' ],
				'id' => $idPrefix . 'p-' . $name,
				'prefix' => $idPrefix . 'p'
			] );
		}

		// Site and page tools (toolbox, languages)
		$toolsBlock = '';
		if ( $languageUrls || $this->getSkin()->getAfterPortlet( 'lang' ) !== '' ) {
			$toolsBlock .= $this->getPortlet( 'lang', $languageUrls, 'otherlanguages', [
				'body-extra-classes' => [ 'dropdown' ],
				'id' => $idPrefix . 'p-lang',
				'prefix' => $idPrefix . 'p'
			] );
		}
		if ( isset( $this->data['variant_urls'] ) && $this->data['variant_urls'] !== false ) {
			$toolsBlock .= $this->getPortlet( 'variants', $this->data['variant_urls'], null, [
				'body-extra-classes' => [ 'dropdown' ],
				'id' => $idPrefix . 'p-variants',
				'prefix' => $idPrefix . 'p'
			] );
		}
		$toolsBlock .= $this->getPortlet( 'tbx', $toolbox, 'toolbox', [
			'body-extra-classes' => [ 'dropdown' ],
			'id' => $idPrefix . 'p-tbx',
			'prefix' => $idPrefix . 'p'
		] );

		$html .= Html::rawElement( 'div', [ 'class' => 'navigation' ], $mainBlock );
		$html .= Html::rawElement( 'div', [ 'class' => 'navigation-tools' ], $toolsBlock );

		return $html;
	}

	/**
	 * Get the banner for the site (including the logo image)
	 *
	 * We assume any meaningful subtitle will contain more than one character to allow for use
	 * cases such as '-' and the like (content that sets it to functionally nothing). May or may
	 * not be a valid assumption in practice.
	 *
	 * @param string $id
	 *
	 * @return string html
	 */
	protected function getBanner( $id = 'p-banner' ) {
		$config = $this->getSkin()->getContext()->getConfig();
		$logos = ResourceLoaderSkinModule::getAvailableLogos( $config );
		$html = '';

		if ( $config->get( 'GreyStuffUseLogoImage' ) ) {
			if ( !isset( $logos['icon'] ) ) {
				// Oldschool wgLogo via RL
				$html .= Html::rawElement( 'div', [ 'class' => 'p-logo', 'role' => 'banner' ],
					Html::element( 'a', array_merge( [
						'class' => 'mw-wiki-logo',
						'href' => $this->data['nav_urls']['mainpage']['href'] ],
						Linker::tooltipAndAccesskeyAttribs( 'p-logo' )
					) )
				);

				// The above needs to be a separate link due to how the image is applied, soo...
				$html .= Html::openElement( 'a', [ 'href' => $this->data['nav_urls']['mainpage']['href'] ] );
			} else {
				$html .= Html::openElement( 'a',  array_merge(
					[ 'href' => $this->data['nav_urls']['mainpage']['href'] ],
					Linker::tooltipAndAccesskeyAttribs( 'p-logo' )
				) );
				$html .= Html::element( 'img', [ 'src' => $logos['icon'], 'class' => 'p-logo' ] );
			}
		} else {
			// No image logo, but we still gotta open the link for the banner stuff...
			$html .= Html::openElement( 'a',  array_merge(
				[ 'href' => $this->data['nav_urls']['mainpage']['href'] ],
				Linker::tooltipAndAccesskeyAttribs( 'p-logo' )
			) );
		}

		$subtitleText = $this->getMsg( 'sitesubtitle' )->inContentLanguage()->text();
		$wordmarkText = $this->getMsg( 'sitetitle' )->inContentLanguage()->text();

		$bannerClass = [ 'mw-portlet', 'p-banner' ];
		if ( isset( $logos['tagline'] ) ) {
			$taglineData = $logos['tagline'];
			$subtitle = Html::rawElement( 'div', [ 'class' => 'sitesubtitle' ],
				Html::element( 'img', [
					'src' => $taglineData['src'],
					'height' => $taglineData['height'] ?? null,
					'width' => $taglineData['width'] ?? null,
					'alt' => $subtitleText
				] )
			);
			$bannerClass[] = 'full-banner';
		} elseif ( strlen( $subtitleText ) > 1 ) {
			$subtitle = Html::element( 'div', [ 'class' => 'sitesubtitle' ], $subtitleText );
			$bannerClass[] = 'full-banner';
		} else {
			$subtitle = '';
			$bannerClass[] = 'title-banner';
		}

		// Wordmark image! Fancy!
		if ( isset( $logos['wordmark'] ) ) {
			$wordmarkData = $logos['wordmark'];
			$wordmark = Html::element( 'img', [
				'src' => $wordmarkData['src'],
				'height' => $wordmarkData['height'] ?? null,
				'width' => $wordmarkData['width'] ?? null,
				'alt' => $wordmarkText
			] );
		} else {
			$wordmark = Html::element( 'div', [ 'class' => 'wordmark-text' ], $wordmarkText );
		}

		$html .= Html::rawElement( 'div', [ 'class' => $bannerClass, 'id' => $id ],
			Html::rawElement( 'div', [ 'class' => 'sitetitle', 'role' => 'banner' ],
				$wordmark . $subtitle
			)
		);

		$html .= Html::closeElement( 'a' );

		return $html;
	}

	/**
	 * Get user dropdown portlet
	 *
	 * @return string html
	 */
	protected function getPersonalNavigation() {
		$skin = $this->getSkin();
		$user = $skin->getUser();
		$personalTools = $skin->getPersonalToolsForMakeListItem( $this->get( 'personal_urls' ) );

		$html = '';
		$extraTools = [];

		// Remove Echo badges
		if ( isset( $personalTools['notifications-alert'] ) ) {
			$extraTools['notifications-alert'] = $personalTools['notifications-alert'];
			unset( $personalTools['notifications-alert'] );
		}
		if ( isset( $personalTools['notifications-notice'] ) ) {
			$extraTools['notifications-notice'] = $personalTools['notifications-notice'];
			unset( $personalTools['notifications-notice'] );
		}

		if ( $user->isRegistered() ) {
			$headerMsg = [ 'greystuff-loggedinas', $user->getName() ];
		} else {
			$headerMsg = 'greystuff-notloggedin';
		}

		$html .= Html::openElement( 'div', [ 'id' => 'p-personal-container' ] );

		if ( isset( $personalTools['userpage'] ) ) {
			$personalTools['userpage']['links'][0]['text'] = $this->getMsg( 'greystuff-userpage' )->text();
		}
		if ( isset( $personalTools['mytalk'] ) ) {
			$personalTools['mytalk']['links'][0]['text'] = $this->getMsg( 'greystuff-talkpage' )->text();
		}
		if ( isset( $personalTools['anonuserpage'] ) ) {
			// Pointless; already used as the dropdown header
			unset( $personalTools['anonuserpage'] );
		}

		// Re-add Echo badges
		if ( !empty( $extraTools ) ) {
			$iconList = '';
			foreach ( $extraTools as $key => $item ) {
				$iconList .= $skin->makeListItem( $key, $item );
			}

			$html .= Html::rawElement(
				'div',
				[ 'id' => 'p-personal-extra', 'class' => 'p-body' ],
				Html::rawElement( 'ul', [], $iconList )
			);
		}

		$html .= $this->getPortlet( 'personal', $personalTools, $headerMsg,
			[ 'body-extra-classes' => [ 'dropdown' ] ]
		);

		$html .= Html::closeElement( 'div' );

		return $html;
	}

	/**
	 * Get the search block
	 *
	 * @return string html
	 */
	protected function getSearch() {
		$skin = $this->getSkin();
		$html = '';

		$html .= Html::openElement( 'div', [ 'class' => 'mw-portlet', 'id' => 'p-search', 'role' => 'search' ] );

		$html .= Html::rawElement(
			'h3',
			[ 'lang' => $this->get( 'userlang' ), 'dir' => $this->get( 'dir' ) ],
			Html::rawElement( 'label', [ 'for' => 'searchInput' ], $this->getMsg( 'search' ) )
		);

		$html .= Html::rawElement( 'form', [ 'action' => $this->get( 'wgScript' ), 'id' => 'searchform' ],
			Html::rawElement( 'div', [ 'id' => 'simpleSearch' ],
				Html::rawElement( 'div', [ 'id' => 'searchInput-container-container' ],
					Html::rawElement( 'div', [ 'id' => 'searchInput-container' ],
						$skin->makeSearchInput( [ 'id' => 'searchInput', 'type' => 'text' ] )
					)
				) .
				$skin->makeSearchButton( 'go', [ 'id' => 'searchGoButton', 'class' => 'searchButton' ] ) .
				Html::hidden( 'title', $this->get( 'searchtitle' ) )
			)
		);

		$html .= Html::closeElement( 'div' );

		return $html;
	}

	/**
	 * @return string html
	 */
	protected function getSiteNotice() {
		$html = '';

		if ( $this->data['sitenotice'] ) {
			$html = Html::rawElement( 'div', [ 'id' => 'siteNotice' ], $this->get( 'sitenotice' ) );
		}

		return $html;
	}

	/**
	 * Gets the page subtitle (block immediately below first heading), not to be confused with
	 * the site subtitle (which also may appear here in some skins)
	 *
	 * @return string html
	 */
	protected function getSubtitle() {
		$html = '';

		if ( $this->data['subtitle'] || $this->data['undelete'] || $this->data['newtalk'] ) {
			$html .= Html::openElement( 'div', [ 'id' => 'content-top-stuff' ] );
			$html .= Html::rawElement( 'div', [
					'id' => 'contentSub',
					'lang' => $this->get( 'userlang' ),
					'dir' => $this->get( 'dir' )
				],
				$this->get( 'subtitle' )
			);
			if ( $this->data['undelete'] ) {
				$html .= Html::rawElement( 'div', [ 'id' => 'contentSub2' ],
					$this->get( 'undelete' )
				);
			}
			if ( $this->data['newtalk'] ) {
				$html .= Html::rawElement( 'div', [ 'class' => 'usermessage' ],
					$this->get( 'newtalk' )
				);
			}
			$html .= Html::closeElement( 'div' );
		}

		return $html;
	}

	/**
	 * Get the data after content, catlinks, and potential other stuff that may appear within
	 * the content block but after the main content
	 *
	 * @return string html
	 */
	protected function getAfterContent() {
		$html = '';

		if ( $this->data['catlinks'] || $this->data['dataAfterContent'] ) {
			$html .= Html::openElement( 'div', [ 'id' => 'content-bottom-stuff' ] );
			if ( $this->data['catlinks'] ) {
				$html .= $this->get( 'catlinks' ) . $this->getClear();
			}
			if ( $this->data['dataAfterContent'] ) {
				$html .= $this->get( 'dataAfterContent' );
			}
			$html .= Html::closeElement( 'div' );
		}

		return $html;
	}

	/**
	 * Better renderer for the footer icons and getFooterLinks
	 *
	 * @param array $setOptions Miscellaneous other options
	 * * 'id' for footer id
	 * * 'class' for footer class
	 * * 'order' to determine whether icons or links appear first: 'iconsfirst' or links, though in
	 *   practice we currently only check if it is or isn't 'iconsfirst'
	 * * 'link-prefix' to set the prefix for all link and block ids; most skins use 'f' or 'footer',
	 *   as in id='f-whatever' vs id='footer-whatever'
	 * * 'link-style' to pass to getFooterLinks: "flat" to disable categorisation of links in a
	 *   nested array
	 *
	 * @return string html
	 */
	protected function getFooterBlock( $setOptions = [] ) {
		// Set options and fill in defaults
		$options = $setOptions + [
			'id' => 'footer',
			'class' => 'mw-footer',
			'order' => 'iconsfirst',
			'link-prefix' => 'footer',
			'link-style' => null
		];

		// phpcs:ignore Generic.Files.LineLength.TooLong
		'@phan-var array{id:string,class:string,order:string,link-prefix:string,link-style:?string} $options';

		$validFooterIcons = $this->get( 'footericons' );
		$validFooterLinks = $this->getFooterLinks( $options['link-style'] );

		$html = '';

		$html .= Html::openElement( 'div', [
			'id' => $options['id'],
			'class' => $options['class'],
			'role' => 'contentinfo',
			'lang' => $this->get( 'userlang' ),
			'dir' => $this->get( 'dir' )
		] );

		$iconsHTML = '';
		if ( count( $validFooterIcons ) > 0 ) {
			$skin = $this->getSkin();
			$iconsHTML .= Html::openElement( 'ul', [ 'id' => "{$options['link-prefix']}-icons" ] );
			foreach ( $validFooterIcons as $blockName => &$footerIcons ) {
				$iconsHTML .= Html::openElement( 'li', [
					'id' => Sanitizer::escapeIdForAttribute(
						"{$options['link-prefix']}-{$blockName}ico"
					),
					'class' => 'footer-icons'
				] );
				foreach ( $footerIcons as $footerIconKey => $icon ) {
					if ( !isset( $footerIcon['src'] ) ) {
						unset( $footerIcons[$footerIconKey] );
					}
					$iconsHTML .= $skin->makeFooterIcon( $icon );
				}
				$iconsHTML .= Html::closeElement( 'li' );
			}
			$iconsHTML .= Html::closeElement( 'ul' );
		}

		$linksHTML = '';
		if ( count( $validFooterLinks ) > 0 ) {
			if ( $options['link-style'] === 'flat' ) {
				$linksHTML .= Html::openElement( 'ul', [
					'id' => "{$options['link-prefix']}-list",
					'class' => 'footer-places'
				] );
				foreach ( $validFooterLinks as $link ) {
					$linksHTML .= Html::rawElement(
						'li',
						[ 'id' => Sanitizer::escapeIdForAttribute( $link ) ],
						$this->get( $link )
					);
				}
				$linksHTML .= Html::closeElement( 'ul' );
			} else {
				$linksHTML .= Html::openElement( 'div', [ 'id' => "{$options['link-prefix']}-list" ] );
				foreach ( $validFooterLinks as $category => $links ) {
					$linksHTML .= Html::openElement( 'ul',
						[ 'id' => Sanitizer::escapeIdForAttribute(
							"{$options['link-prefix']}-{$category}"
						) ]
					);
					foreach ( $links as $link ) {
						$linksHTML .= Html::rawElement(
							'li',
							[ 'id' => Sanitizer::escapeIdForAttribute(
								"{$options['link-prefix']}-{$category}-{$link}"
							) ],
							$this->get( $link )
						);
					}
					$linksHTML .= Html::closeElement( 'ul' );
				}
				$linksHTML .= Html::closeElement( 'div' );
			}
		}

		if ( $options['order'] === 'iconsfirst' ) {
			$html .= $iconsHTML . $linksHTML;
		} else {
			$html .= $linksHTML . $iconsHTML;
		}

		$html .= $this->getClear() . Html::closeElement( 'div' );

		return $html;
	}
}
