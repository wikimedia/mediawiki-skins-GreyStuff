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

	public function execute() {
		// Apparently not set by default?
		$this->data['pageLanguage'] = $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();

		// Move some content actions links
		if ( isset( $this->data['content_navigation']['actions']['watch'] ) ) {
			$this->data['content_navigation']['views']['watch'] = $this->data['content_navigation']['actions']['watch'];
			unset( $this->data['content_navigation']['actions']['watch'] );
		}
		if ( isset( $this->data['content_navigation']['actions']['unwatch'] ) ) {
			$this->data['content_navigation']['views']['unwatch'] = $this->data['content_navigation']['actions']['unwatch'];
			unset( $this->data['content_navigation']['actions']['unwatch'] );
		}

		// Open html, body elements, etc
		$html = $this->get( 'headelement' );

		$html .= Html::openElement( 'div', [ 'id' => 'globalWrapper' ] );

		$html .= Html::rawElement( 'div', [ 'id' => 'header-container', 'lang' => $this->get( 'userlang' ), 'dir' => $this->get( 'dir' ) ],
			Html::rawElement( 'div', [ 'id' => 'header-top-container' ],
				Html::rawElement( 'div', [ 'id' => 'header-top' ],
					Html::rawElement( 'div', [ 'id' => 'main-banner' ], $this->getBanner() ) .
					Html::element( 'div', [ 'id' => 'menus-cover' ] ) .
					Html::element( 'div', [ 'id' => 'main-menu-toggle' ] ) .
					Html::element( 'div', [ 'id' => 'personal-menu-toggle' ] ) .
					Html::element( 'div', [ 'id' => 'tools-menu-toggle' ] ) .

					$this->getPersonalNavigation() .
					$this->clear( 'mobile' ) .
					$this->getSearch() .
					$this->clear()
				)
			)
		) ;

		$html .= $this->clear();

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
				Html::rawElement( 'div', [ 'id' => 'content-header' ],
					Html::rawElement( 'h1', [ 'id' => 'firstHeading', 'class' => 'firstHeading', 'lang' => $this->get( 'pageLanguage' ) ],
						$this->get( 'title' )
					) .
					$this->clear( 'mobile' ) .
					Html::rawElement( 'div', [ 'id' => 'page-namespaces' ],
						$this->getPortlet( 'namespaces', $this->data['content_navigation']['namespaces'] )
					) .
					Html::rawElement( 'div', [ 'id' => 'page-tools' ],
						$this->getPortlet( 'views', $this->data['content_navigation']['views'] ) .
						$this->getPortlet( 'actions', $this->data['content_navigation']['actions'] )
					)
				) .
				// for double underline on the header
				Html::element( 'div', [ 'id' => 'content-header-inner' ] ) .

				$this->clear() .

				Html::rawElement( 'div', [ 'id' => 'bodyContent', 'class' => 'mw-body-content' ],
					Html::rawElement( 'div', [ 'id' => 'siteSub' ], $this->getMsg( 'tagline' ) ) .
					$this->get( 'bodytext' ) .
					$this->clear()
				) .
				$this->getAfterContent()
			)
		);

		$html .= Html::rawElement( 'div', [ 'id' => 'footer' ],
			Html::rawElement( 'div', [ 'id' => 'footer-banner' ], $this->getBanner() ) .
			Html::rawElement( 'div', [ 'id' => 'footer-navigation' ],
				$this->getMainNavigation()
			) .
			$this->clear() .
			Html::rawElement( 'div', [ 'id' => 'footer-bottom' ],
				$this->getFooter()
			)
		);

		// BaseTemplate::printTrail() stuff (has no get version)
		$html .= MWDebug::getDebugHTML( $this->getSkin()->getContext() );
		$html .= $this->get( 'bottomscripts' ); /* JS call to runBodyOnloadHook */
		$html .= $this->get( 'reporttime' );

		$html .= Html::closeElement( 'body' );
		$html .= Html::closeElement( 'html' );

		echo $html;
	}

	/**
	 * Generate a single portlet of any kind
	 *
	 * $content array format is expected to follow the format used by SkinTemplate, which is a mess
	 * $msg array fomat is expected to follow [ message name, parameter 1, parameter 2, etc ]
	 *
	 * @param string $name
	 * @param array $content
	 * @param bool $dropdown
	 * @param null|string|array $msg
	 *
	 * @return string html
	 */
	protected function getPortlet( $name, $content, $dropdown = false, $msg = null ) {
		if ( $msg === null ) {
			$msg = $name;
		} elseif ( is_array( $msg ) ) {
			$msgString = array_shift( $msg );
			$msgParams = $msg;
			$msg = $msgString;
		}
		$msgObj = wfMessage( $msg );
		if ( $msgObj->exists() ) {
			if ( isset( $msgParams ) && !empty( $msgParams ) ) {
				$msgString = $this->getMsg( $msg, $msgParams );
			} else {
				$msgString = $msgObj->parse();
			}
		} else {
			$msgString = htmlspecialchars( $msg );
		}

		$labelId = Sanitizer::escapeId( "p-$name-label" );

		if ( is_array( $content ) ) {
			$contentText = Html::openElement( 'ul' );
			foreach ( $content as $key => $item ) {
				$contentText .= $this->makeListItem( $key, $item, [ 'text-wrapper' => [ 'tag' => 'span' ] ] );
			}
			$contentText .= Html::closeElement( 'ul' );
		} else {
			$contentText = $content;
		}

		$html = Html::rawElement( 'div', [
				'role' => 'navigation',
				'class' => 'mw-portlet',
				'id' => Sanitizer::escapeId( "p-$name" ),
				'title' => Linker::titleAttrib( 'p-' . $name ),
				'aria-labelledby' => $labelId
			],
			Html::rawElement( 'h3', [
					'id' => $labelId,
					'lang' => $this->get( 'userlang' ),
					'dir' => $this->get( 'dir' )
				],
				$msgString
			) .
			Html::rawElement( 'div', [ 'class' => [
					'p-body',
					$dropdown ? 'dropdown' : ''
				] ],
				$contentText .
				$this->getAfterPortlet( $name )
			)
		);

		return $html;
	}

	/**
	 * Get all main navigation portlets, sectioned into navigation and navigation-tools divs
	 *
	 * @return string html
	 */
	protected function getMainNavigation() {
		$html = '';

		$sidebar = $this->getSidebar();
		$sidebar['SEARCH'] = false;
		$sidebar['TOOLBOX'] = false;
		$sidebar['LANGUAGES'] = false;

		// Main navigation, from [[MediaWiki:Sidebar]]
		$mainBlock = '';
		foreach ( $sidebar as $name => $content ) {
			if ( $content === false ) {
				continue;
			}
			// Numeric strings gets an integer when set as key, cast back - T73639
			$name = (string)$name;

			$mainBlock .= $this->getPortlet( $name, $content['content'], true );
		}

		// Add some extra links to the toolbox
		$toolbox = $this->getToolbox();
		if ( $this->getSkin()->getOutput()->isArticleRelated() ) {
			$toolbox['history'] = $this->data['content_actions']['history'];
			$toolbox['history']['text'] = $this->getMsg( 'greystuff-history' );
			$toolbox['history']['id'] = 't-history';
		}
		$toolbox['purge'] = [
			'text' => $this->getMsg( 'greystuff-purge' ),
			'id' => 't-purge',
			'href' => $this->getSkin()->getTitle()->getLocalURL( [ 'action' => 'purge' ] ),
			'rel' => 'nofolow'
		];

		// Site and page tools (toolbox, languages)
		$toolsBlock = '';
		if ( $this->data['language_urls'] !== false ) {
			$toolsBlock .= $this->getPortlet( 'lang', $this->data['language_urls'], true, 'otherlanguages' );
		}
		if ( isset( $this->data['variant_urls'] ) && $this->data['variant_urls'] !== false ) {
			$toolsBlock .= $this->getPortlet( 'variants', $this->data['variant_urls'], true );
		}
		$toolsBlock .= $this->getPortlet( 'tbx', $toolbox, true, 'toolbox' );

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
	 * @return string html
	 */
	protected function getBanner() {
		$html = Html::rawElement( 'div', [ 'class' => 'p-logo', 'role' => 'banner' ],
			Html::element( 'a', array_merge( [
				'class' => 'mw-wiki-logo',
				'href' => htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ],
				Linker::tooltipAndAccesskeyAttribs( 'p-logo' )
			) )
		);

		$subtitleText = $this->getMsg( 'sitesubtitle' );
		if ( strlen( $subtitleText ) > 1 ) {
			$subtitle = Html::element( 'div', [ 'class' => 'sitesubtitle' ], $subtitleText );
			$bannerClass = 'full-banner';
		} else {
			$subtitle = '';
			$bannerClass = 'title-banner';
		}
		$html .= Html::rawElement( 'div', [ 'class' => [ 'mw-portlet', $bannerClass ], 'class' => 'p-banner' ],
			Html::rawElement( 'div', [ 'class' => 'sitetitle', 'role' => 'banner' ],
				Html::element( 'a', [ 'href' => htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ],
					$this->getMsg( 'sitetitle' )
				)
			) .
			$subtitle
		);

		return $html;
	}

	/**
	 * Get user dropdown portlet
	 *
	 * @return string html
	 */
	protected function getPersonalNavigation() {
		$user = $this->getSkin()->getUser();
		$personalTools = $this->getPersonalTools();

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

		if ( $user->isLoggedIn() ) {
			$headerMsg = [ 'greystuff-loggedinas', $user->getName() ];
		} else {
			$headerMsg = 'greystuff-notloggedin';
		}

		$html .= Html::openElement( 'div', [ 'id' => 'p-personal-container' ] );

		if ( isset( $personalTools['userpage'] ) ) {
			$personalTools['userpage']['links'][0]['text'] = $this->getMsg( 'greystuff-userpage' );
		}
		if ( isset( $personalTools['mytalk'] ) ) {
			$personalTools['mytalk']['links'][0]['text'] = $this->getMsg( 'greystuff-talkpage' );
		}

		// Re-add Echo badges
		if ( !empty( $extraTools ) ) {
			$iconList = '';
			foreach ( $extraTools as $key => $item ) {
				$iconList .= $this->makeListItem( $key, $item );
			}

			$html .= Html::rawElement(
				'div',
				[ 'id' => 'p-personal-extra', 'class' => 'p-body' ],
				Html::rawElement( 'ul', [], $iconList )
			);
		}

		$html .= $this->getPortlet( 'personal', $personalTools, true, $headerMsg );

		$html .= Html::closeElement( 'div' );

		return $html;
	}

	/**
	 * Get the search block
	 *
	 * @return string html
	 */
	protected function getSearch() {
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
						$this->makeSearchInput( [ 'id' => 'searchInput', 'type' => 'text' ] )
					)
				) .
				$this->makeSearchButton( 'fulltext', [ 'id' => 'mw-searchButton', 'class' => 'searchButton mw-fallbackSearchButton' ] ) .
				$this->makeSearchButton( 'go', [ 'id' => 'searchGoButton', 'class' => 'searchButton' ] ) .
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
				$html .= Html::rawElement( 'div', [ 'class' =>  'usermessage' ],
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
				$html .= $this->get( 'catlinks' );
			}
			if ( $this->data['dataAfterContent'] ) {
				$html .= $this->get( 'dataAfterContent' );
			}
			$html .= Html::closeElement( 'div' );
		}

		return $html;
	}

	/**
	 * Get page footer
	 *
	 * @return string html
	 */
	protected function getFooter( $iconStyle = 'icononly', $linkStyle = 'flat' ) {
		$validFooterIcons = $this->getFooterIcons( $iconStyle );
		$validFooterLinks = $this->getFooterLinks( $linkStyle );

		$html = '';

		if ( count( $validFooterIcons ) + count( $validFooterLinks ) > 0 ) {
			$html .= Html::openElement( 'div', [
				'id' => 'footer-bottom',
				'role' => 'contentinfo',
				'lang' => $this->get( 'userlang' ),
				'dir' => $this->get( 'dir' )
			] );
			$footerEnd = Html::closeElement( 'div' );
		} else {
			$footerEnd = '';
		}
		foreach ( $validFooterIcons as $blockName => $footerIcons ) {
			$html .= Html::openElement( 'div', [
				'id' => 'f-' . Sanitizer::escapeId( $blockName ) . 'ico',
				'class' => 'footer-icons'
			] );
			foreach ( $footerIcons as $icon ) {
				$html .= $this->getSkin()->makeFooterIcon( $icon );
			}
			$html .= Html::closeElement( 'div' );
		}
		if ( count( $validFooterLinks ) > 0 ) {
			$html .= Html::openElement( 'ul', [ 'id' => 'f-list' ] );
			foreach ( $validFooterLinks as $aLink ) {
				$html .= Html::rawElement( 'li', [ 'id' => Sanitizer::escapeId( $aLink ) ], $this->get( $aLink ) );
			}
			$html .= Html::closeElement( 'ul' );
		}
		return $html . $this->clear() . $footerEnd;
	}

	/**
	 * BaseTemplate::renderAfterPortlet, but sans immediate pooping
	 * Allows extensions to hook into known portlets and add stuff to them (an archaic approach;
	 * assumes standardised, consistent portlet handling/naming, when the only standard portlets
	 * that exist consistently are 'tbx' and 'personal', and tbx is already a mess )
	 *
	 * @param string $name
	 * @return string html
	 */
	protected function getAfterPortlet( $name ) {
		$content = '';
		Hooks::run( 'BaseTemplateAfterPortlet', [ $this, $name, &$content ] );

		if ( $content !== '' ) {
			return Html::rawElement( 'div', [ 'class' => [ 'after-portlet', 'after-portlet-' . $name ] ], $content );
		}

		return $content;
	}

	/**
	 * @param string $prefix generally 'mobile' or 'visual' for visualClear or mobileClear classes
	 * @return string html
	 */
	protected function clear( $prefix = 'visual' ) {
		return Html::element( 'div', [ 'class' => $prefix . 'Clear' ] );
	}
}
