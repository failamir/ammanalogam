class WidgetHandlerClass extends elementorModules.frontend.handlers.Base {
		
	getDefaultSettings() {
		return {
			selectors: {
				tablist: '[role="tablist"]',
				tabTitle: '.elementor-tab-title',
				tabContent: '.elementor-tab-content',
			},
			classes: {
				active: 'elementor-active',
			},
			showTabFn: 'slideDown',
			hideTabFn: 'slideUp',
			toggleSelf: true,
			hidePrevious: true,
			autoExpand: true,
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );

		return {
			$tabTitles: this.findElement( selectors.tabTitle ),
			$tabContents: this.findElement( selectors.tabContent ),
		};
	}

	activateDefaultTab() {
		const settings = this.getSettings();

		if ( ! settings.autoExpand || ( 'editor' === settings.autoExpand && ! this.isEdit ) ) {
			return;
		}

		const defaultActiveTab = this.getEditSettings( 'activeItemIndex' ) || 1,
			originalToggleMethods = {
				showTabFn: settings.showTabFn,
				hideTabFn: settings.hideTabFn,
			};

		// Toggle tabs without animation to avoid jumping
		this.setSettings( {
			showTabFn: 'slideDown',
     		hideTabFn: 'slideUp',
		} );

		this.changeActiveTab( defaultActiveTab );

		// Return back original toggle effects
		this.setSettings( originalToggleMethods );
	}

	deactivateActiveTab( tabIndex ) {
		const settings = this.getSettings(),
			activeClass = settings.classes.active,
			activeFilter = tabIndex ? '[data-tab="' + tabIndex + '"]' : '.' + activeClass,
			$activeTitle = this.elements.$tabTitles.filter( activeFilter ),
			$activeContent = this.elements.$tabContents.filter( activeFilter );

		$activeTitle.add( $activeContent ).removeClass( activeClass );

		$activeTitle.attr({
			tabindex: '-1',
			'aria-selected': 'false',
			'aria-expanded': 'false'
		});
		$activeContent[settings.hideTabFn]();
	}

	activateTab( tabIndex ) {
		const settings        = this.getSettings(),
			activeClass       = settings.classes.active,
			$requestedTitle   = this.elements.$tabTitles.filter( '[data-tab="' + tabIndex + '"]' ),
			$requestedContent = this.elements.$tabContents.filter( '[data-tab="' + tabIndex + '"]' ),
			animationDuration = 'show' === settings.showTabFn ? 0 : 400;

		$requestedTitle.add( $requestedContent ).addClass( activeClass );
		$requestedTitle.attr( {
			tabindex: '0',
			'aria-selected': 'true',
			'aria-expanded': 'true'
		});
		$requestedContent[settings.showTabFn](animationDuration, () => elementorFrontend.elements.$window.trigger('resize'));
	}

	isActiveTab( tabIndex ) {
		return this.elements.$tabTitles.filter( '[data-tab="' + tabIndex + '"]' ).hasClass( this.getSettings( 'classes.active' ) );
	}

	bindEvents() {
		this.elements.$tabTitles.on( {
			keydown: ( event ) => {
				// Support for old markup that includes an `<a>` tag in the tab
		        if (jQuery(event.target).is('a') && `Enter` === event.key) {
		          event.preventDefault();
		        } // We listen to keydowon event for these keys in order to prevent undesired page scrolling

				if ( 'Enter' === event.key ) {
					event.preventDefault();
					this.changeActiveTab( event.currentTarget.getAttribute( 'data-tab' ) );
				}
			},
			click: ( event ) => {
				event.preventDefault();
				this.changeActiveTab( event.currentTarget.getAttribute( 'data-tab' ) );
			},
		} );
	}

	onInit( ...args ) {
		super.onInit( ...args );
		
		if ( this.$element.hasClass( 'elementor-default-active-yes' ) ) {
			this.activateDefaultTab();
		}
	}

	onEditSettingsChange( propertyName ) {
		if ( 'activeItemIndex' === propertyName ) {
			this.activateDefaultTab();
		}
	}

	changeActiveTab( tabIndex ) {
		const isActiveTab = this.isActiveTab( tabIndex ),
			settings = this.getSettings();

		if ( ( settings.toggleSelf || ! isActiveTab ) && settings.hidePrevious ) {
			this.deactivateActiveTab();
		}

		if ( ! settings.hidePrevious && isActiveTab ) {
			this.deactivateActiveTab( tabIndex );
		}

		if ( ! isActiveTab ) {
			this.activateTab( tabIndex );
		}
	}
}

jQuery( window ).on( 'elementor/frontend/init', () => {
	 const addHandler = ( $element ) => {
		 elementorFrontend.elementsHandler.addHandler( WidgetHandlerClass, {
			$element,
		 } );
	 };

	elementorFrontend.hooks.addAction( 'frontend/element_ready/litho-accordion.default', addHandler );
} );