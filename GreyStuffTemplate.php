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
 * The DOM here is an utter trainwreck.
 */

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
	 */
	function execute() {
		$this->data['pageLanguage'] = $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();

		$this->html( 'headelement' );
		?>
		<div id="globalWrapper">
			<div id="header-container"<?php $this->html( 'userlangattributes' ); ?> class="noprint">
			<div id="header-top-container">
			<div id="header-top">
				<div id="p-logo" role="banner">
					<a class="mw-wiki-logo" href="<?php
					echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] )
					?>" <?php
					echo Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) )
					?>></a>
				</div>
				<div class="mw-portlet" id="p-banner">
					<div id="sitetitle" role="banner">
						<a href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>">
							<?php echo wfMessage( 'sitetitle' )->escaped() ?>
						</a>
					</div>
					<div id="sitesubtitle">
						<?php echo wfMessage( 'sitesubtitle' )->escaped() ?>
					</div>
				</div>
				<?php
				$this->outputPersonalNavigation();
				$this->outputSearch();
				?>
				<div class="visualClear"></div>
			</div>
			</div>
			</div>
			<div class="visualClear"></div>
			<div id="header-navigation-container" class="noprint">
			<div id="header-navigation">
				<?php
				$this->outputMainNavigation( true );
				?>
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
				<div id="content-header">
					<h1 id="firstHeading" class="firstHeading" lang="<?php
						$this->text( 'pageLanguage' );
						?>">

						<?php
						$this->html( 'title' ) ?>
					</h1>
					<div id="page-namespaces" class="noprint">
						<?php
						$this->outputPortlet( array(
							'id' => 'p-namespaces',
							'headerMessage' => 'namespaces',
							'content' => $this->data['content_navigation']['namespaces'],
						) );
						?>
					</div>
					<div id="page-tools" class="noprint">

						<?php
						if ( isset( $this->data['content_navigation']['actions']['watch'] ) ) {
							$this->data['content_navigation']['views']['watch'] = $this->data['content_navigation']['actions']['watch'];
							unset( $this->data['content_navigation']['actions']['watch'] );
						}
						if ( isset( $this->data['content_navigation']['actions']['unwatch'] ) ) {
							$this->data['content_navigation']['views']['unwatch'] = $this->data['content_navigation']['actions']['unwatch'];
							unset( $this->data['content_navigation']['actions']['unwatch'] );
						}
						$this->outputPortlet( array(
							'id' => 'p-views',
							'headerMessage' => 'views',
							'content' => $this->data['content_navigation']['views']
						) );
						$this->outputPortlet( array(
							'id' => 'p-actions',
							'headerMessage' => 'views',
							'content' => $this->data['content_navigation']['actions']
						) );
						?>
					</div>
				</div>
				<div class="visualClear"></div>
				<div id="bodyContent" class="mw-body">
					<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>

					<!-- start content -->
					<?php $this->html( 'bodytext' ) ?>
					<!-- end content -->
					<div class="visualClear"></div>
				</div>
				<div id="content-bottom-stuff" class="noprint">
					<?php if ( $this->data['catlinks'] ) { $this->html( 'catlinks' ); } ?>
					<?php if ( $this->data['dataAfterContent'] ) { $this->html( 'dataAfterContent' ); } ?>
				</div>
			</div>
			</div>
			<div id="footer-navigation" class="noprint">
				<?php
				$this->outputMainNavigation();
				?>
			</div>
			<div class="visualClear"></div>
			<div id="footer-container">
			<div id="footer-bottom-container">
				<?php
				$this->outputFooter();
				?>
			</div>
			</div>
		</div>
		<?php
		$this->printTrail();
		echo Html::closeElement( 'body' );
		echo Html::closeElement( 'html' );
	} // end of execute() method

	private function outputMainNavigation( $dropdown = false ) {
		?>
		<div class="navigation">
			<?php
			// From [[MediaWiki:Sidebar]]
			$sidebar = $this->getSidebar();
			$sidebar['SEARCH'] = false;
			$sidebar['TOOLBOX'] = false;
			$sidebar['LANGUAGES'] = false;

			foreach ( $sidebar as $boxName => $box ) {
				if ( $boxName === false ) {
					continue;
				}
				$this->outputPortlet( $box );
			}
			?>
		</div>
		<div class="navigation-tools">
			<?php
			$this->outputPortlet( array(
				'class' => 'p-variants',
				'headerMessage' => 'variants',
				'content' => $this->data['content_navigation']['variants'],
			) );
			$this->outputToolbox( $dropdown );
			?>
		</div>
		<?php
	}

	private function outputPersonalNavigation() {
		$user = $this->getSkin()->getUser();
		?>
		<div class="mw-portlet" id="p-personal" role="navigation">
		<?php
		// Display status, and make a dropdown if logged in
		if ( $user->isLoggedIn() ) {
			?>
			<h3 id="p-welcome">
			<?php
			echo wfMessage( 'greystuff-loggedinas', '<b>' . $user->getName() . '</b>' )->parse();
			?>
			</h3>
			<div class="p-body dropdown">
		<?php
		} else {
		?>
			<div class="p-body no-dropdown">
		<?php
		}
		?>
			<ul<?php $this->html( 'userlangattributes' ) ?>>
			<?php
				foreach ( $this->getPersonalTools() as $key => $item ) {
					if ( $key == 'userpage' ) {
						$item['links'][0]['text'] = wfMessage( 'greystuff-userpage' )->text();
					}
					if ( $key == 'mytalk' ) {
						$item['links'][0]['text'] = wfMessage( 'greystuff-talkpage' )->text();
					}
					echo $this->makeListItem( $key, $item );
				}
			?>
			</ul>
		</div>
		</div>
		<?php
	}

	private function outputSearch() {
	?>
		<div class="mw-portlet" id="p-search" role="search">
			<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
			<h3><label for="searchInput"><?php $this->msg( 'search' ) ?></label></h3>
			<div id="simpleSearch">
				<div id="searchInput-container-container">
				<div id="searchInput-container">
					<?php echo $this->makeSearchInput( array( 'id' => 'searchInput', 'type' => 'text' ) ); ?>
				</div>
				</div>
				<?php echo $this->makeSearchButton( 'go', array( 'id' => 'searchGoButton', 'class' => 'searchButton' ) ); ?>
				<?php // echo $this->makeSearchButton( 'fulltext', array( 'id' => 'mw-searchButton', 'class' => 'searchButton' ) ); ?>
				<input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
			</div>
		</form>
		</div>
	<?php
	}

	private function outputToolbox( $dropdown = false ) {
		?>
		<div class="mw-portlet" id="p-toolbox" role="navigation">
			<h3><?php $this->msg( 'toolbox' ) ?></h3>
			<div class="p-body <?php if ( $dropdown ) { ?> dropdown <?php } ?>">
				<ul>
				<?php
				foreach ( $this->getToolbox() as $key => $tbitem ) {
					echo $this->makeListItem( $key, $tbitem );
				}
				$title = $this->getSkin()->getTitle();
				// history
				if ( $this->getSkin()->getOutput()->isArticleRelated() ) {
					$link = Linker::link( $title, wfMessage( 'greystuff-history' )->text(), array(), array( 'action' => 'history' ) ); ?>
					<li id="t-history"><?php echo $link; ?></li>
					<?php
				}
				// purge
				$link = Linker::link( $title, wfMessage( 'greystuff-purge' )->text(), array(), array( 'action' => 'purge' ) ); ?>
				<li id="t-purge"><?php echo $link; ?></li>

				<?php
				Hooks::run( 'SkinTemplateToolboxEnd', array( &$this, true ) );
				?>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs a single portlet of any kind.
	 */
	private function outputPortlet( $box ) {
		if ( !$box['content'] ) {
			return;
		}

		?>
		<div
			role="navigation"
			class="mw-portlet"
			id="<?php echo Sanitizer::escapeId( $box['id'] ) ?>"
			<?php echo Linker::tooltip( $box['id'] ) ?>
		>
			<h3>
				<?php
				if ( isset( $box['headerMessage'] ) ) {
					$this->msg( $box['headerMessage'] );
				} else {
					echo htmlspecialchars( $box['header'] );
				}
				?>
			</h3>

			<div class="p-body dropdown">
				<?php
				if ( is_array( $box['content'] ) ) {
					echo '<ul>';
					foreach ( $box['content'] as $key => $item ) {
						echo $this->makeListItem( $key, $item );
					}
					echo '</ul>';
				} else {
					echo $box['content'];
				}?>
			</div>
		</div>
		<?php
	}

	private function outputFooter() {
		$validFooterIcons = $this->getFooterIcons( 'icononly' );
		$validFooterLinks = $this->getFooterLinks( 'flat' ); // Additional footer links

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
				echo $this->getSkin()->makeFooterIcon( $icon );
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
	}

} // end of class
