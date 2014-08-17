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
		parent::setupSkinUserCss( $out );

		# Add css
		$out->addModuleStyles( 'skins.greystuff' );
	}
}

/**
 * Main skin class
 * @ingroup Skins
 */
class GreyStuffTemplate extends BaseTemplate {

	/**
	 * Template filter callback for GreyStuff skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	function execute() {
		global $wgHostLink, $wgDefaultSkin;
		$user = $this->getSkin()->getUser();

		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

		$this->html( 'headelement' );
		?>
		<div id="globalWrapper">
		<div id="header-container"<?php $this->html( 'userlangattributes' ); ?>>
			<div id="header-top-container">
				<div id="header-top">
					<div class="portlet" id="p-personal" role="navigation">
						<?php
						# Display status, and make a dropdown if logged in
						if ( $user->isLoggedIn() ) {
							?>
							<div id="p-welcome">
							<?php
							echo wfMessage( 'greystuff-loggedinas', '<b>' . $user->getName() . '</b>' )->parse();
							?>
							</div>
							<div class="pBody dropdown">
						<?php
						} else {
						?>
							<div class="pBody no-dropdown">
						<?php
						}
						?>
							<ul<?php $this->html( 'userlangattributes' ) ?>>
							<?php
								foreach ( $this->getPersonalTools() as $key => $item ) {
									echo $this->makeListItem( $key, $item );
								}
							?>
							</ul>
						</div>
					</div>
					<?php $this->searchBox(); ?>
					<div class="portlet" id="p-header">
						<div id="sitetitle" role="banner">
							<a href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>">
								<?php echo wfMessage( 'sitetitle' )->escaped() ?>
							</a>
						</div>
						<div id="sitesubtitle">
							<?php echo wfMessage( 'sitesubtitle' )->escaped() ?>
						</div>
					</div>
				</div>
			</div>
			<div id="header-navigation-container">
				<div id="header-navigation">
					<div id="header-tools">
						<?php
						$this->languageBox();
						$this->toolbox();
						?>
					</div>
					<div id="navigation">
						<?php
						$this->renderPortals( $this->data['sidebar'] );
						?>
					</div>
				</div>
			</div>
		</div>
		<div id="content-container">
			<div id="content" class="mw-body-primary" role="main">
				<a id="top"></a>
				<?php
				if ( $this->data['sitenotice'] ) {
				?>
					<div id="siteNotice">
					<?php
						$this->html( 'sitenotice' )
					?>
					</div>
				<?php
				}
				if ( $this->data['subtitle'] || $this->data['undelete'] || $this->data['newtalk'] ) {
				?>
					<div id="content-top-stuff">
						<div id="contentSub"<?php $this->html( 'userlangattributes' ) ?>>
							<?php $this->html( 'subtitle' ) ?>
						</div>
						<?php
						if ( $this->data['undelete'] ) {
							?>
							<div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
							<?php
						}
						if ( $this->data['newtalk'] ) {
							?>
							<div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>
				<h1 id="firstHeading" class="firstHeading" lang="<?php
					$this->data['pageLanguage'] = $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();
					$this->text( 'pageLanguage' );
					?>">
					<?php $this->cactions(); ?>
					<span dir="auto"><?php $this->html( 'title' ) ?></span>
				</h1>
				<div id="bodyContent" class="mw-body">
					<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>

					<!-- start content -->
					<?php $this->html( 'bodytext' ) ?>
					<!-- end content -->
					<div class="visualClear"></div>
				</div>
				<div id="content-bottom-stuff">
					<?php if ( $this->data['catlinks'] ) { $this->html( 'catlinks' ); } ?>
					<?php if ( $this->data['dataAfterContent'] ) { $this->html( 'dataAfterContent' ); } ?>
				</div>
			</div>
		</div>
		<div id="footer-container">
			<div id="footer-bottom-container">
			<?php
				$validFooterIcons = $this->getFooterIcons( "icononly" );
				$validFooterLinks = $this->getFooterLinks( "flat" ); // Additional footer links

				if ( count( $validFooterIcons ) + count( $validFooterLinks ) > 0 ) {
					?>
					<div id="footer-bottom" role="contentinfo"<?php $this->html( 'userlangattributes' ) ?>>
					<?php
					$footerEnd = '</div>';
				} else {
					$footerEnd = '';
				}
				foreach ( $validFooterIcons as $blockName => $footerIcons ) {
					?>
					<div id="f-<?php echo htmlspecialchars( $blockName ); ?>ico" class="footer-icons">
					<?php
					foreach ( $footerIcons as $icon ) {
						?>
						<?php echo $this->getSkin()->makeFooterIcon( $icon );
					}
					?>
					</div>
					<?php
				}
				if ( count( $validFooterLinks ) > 0 ) {
					?>
					<ul id="f-list">
					<?php
					foreach ( $validFooterLinks as $aLink ) {
						?>
						<li id="<?php echo $aLink ?>">
							<?php $this->html( $aLink ) ?>
						</li>
						<?php
					}
					?>
					</ul>
					<?php
				}
				?>
				<div class="visualClear"></div>
				<?php
				echo $footerEnd;
				?>
			</div>
		</div>
	</div>
	<?php
		$this->printTrail();
		echo Html::closeElement( 'body' );
		echo Html::closeElement( 'html' );
		wfRestoreWarnings();
	} // end of execute() method

	/*************************************************************************************************/


	/**
	 * @param $sidebar array
	 */
	protected function renderPortals( $sidebar ) {
		$sidebar['SEARCH'] = false;
		$sidebar['TOOLBOX'] = false;
		$sidebar['LANGUAGES'] = false;

		foreach ( $sidebar as $boxName => $content ) {
			if ( $content === false ) {
				continue;
			}

			$this->customBox( $boxName, $content );
		}
	}

	function searchBox() {
	?>
		<div id="p-search" class="portlet" role="search">
			<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
			<div id="simpleSearch">
				<?php echo $this->makeSearchInput( array( 'id' => 'searchInput', 'type' => 'text' ) ); ?>
				<?php echo $this->makeSearchButton( 'go', array( 'id' => 'searchGoButton', 'class' => 'searchButton' ) ); ?>
				<?php # echo $this->makeSearchButton( 'fulltext', array( 'id' => 'mw-searchButton', 'class' => 'searchButton' ) ); ?>
				<input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
			</div>
		</form>
		</div>
	<?php
	}

	/**
	 * Prints the cactions bar.
	 * Shared between Monobook and Modern and stolen for greystuff
	 */
	function cactions() {
	?>
		<div id="p-cactions" class="portlet" role="navigation">
			<div class="pBody">
				<ul>
				<?php
				foreach ( $this->data['content_actions'] as $key => $tab ) {
					echo $this->makeListItem( $key, $tab );
				}
				?>
				</ul>
			</div>
		</div>
	<?php
	}
	/*************************************************************************************************/
	function toolbox() {
	?>
		<div class="portlet" id="p-tb" role="navigation">
			<h3><?php $this->msg( 'toolbox' ) ?></h3>
			<div class="pBody">
				<ul>
				<?php
					foreach ( $this->getToolbox() as $key => $tbitem ) {
						echo $this->makeListItem( $key, $tbitem );
					}
					$title = $this->getSkin()->getTitle();
					# history
					if ( $this->getSkin()->getOutput()->isArticleRelated() ) {
						$link = Linker::link( $title, wfMessage( 'greystuff-history' )->text(), array(), array( 'action' => 'history' ) ); ?>
						<li id="t-history"><?php echo $link; ?></li>
						<?php
					}
					# purge
					$link = Linker::link( $title, wfMessage( 'greystuff-purge' )->text(), array(), array( 'action' => 'purge' ) ); ?>
					<li id="t-purge"><?php echo $link; ?></li>

					<?php
					wfRunHooks( 'MonoBookTemplateToolboxEnd', array( &$this ) );
					wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this, true ) );
				?>
				</ul>
			</div>
		</div>
	<?php
	}

	/*************************************************************************************************/
	function languageBox() {
		if ( $this->data['language_urls'] ) {
		?>
			<div id="p-lang" class="portlet" role="navigation">
				<h3<?php $this->html( 'userlangattributes' ) ?>><?php $this->msg( 'otherlanguages' ) ?></h3>
				<div class="pBody">
					<ul>
					<?php
					foreach ( $this->data['language_urls'] as $key => $langlink ) {
						?>
						<?php echo $this->makeListItem( $key, $langlink );
					}
					?>
					</ul>
				</div>
			</div>
		<?php
		}
	}

	/*************************************************************************************************/
	/**
	 * @param $bar string
	 * @param $cont array|string
	 */
	function customBox( $bar, $cont ) {
		$portletAttribs = array( 'class' => 'generated-sidebar portlet', 'id' => Sanitizer::escapeId( "p-$bar" ), 'role' => 'navigation' );
		$tooltip = Linker::titleAttrib( "p-$bar" );
		if ( $tooltip !== false ) {
			$portletAttribs['title'] = $tooltip;
		}
		echo Html::openElement( 'div', $portletAttribs );
		$msgObj = wfMessage( $bar );
		?>

		<h3><?php echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $bar ); ?></h3>
		<div class='pBody'>
			<?php   if ( is_array( $cont ) ) { ?>
			<ul>
			<?php
				foreach ( $cont as $key => $val ) {
					echo $this->makeListItem( $key, $val );
				}
			?>
			</ul>
			<?php
		} else {
			# allow raw HTML block to be defined by extensions
			print $cont;
		}
		?>
		</div>
	</div>
	<?php
	}

} // end of class
