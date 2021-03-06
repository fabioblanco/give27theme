Fundify.Campaign = (function($) {
	function campaignGrid() {
		if ( ! $().masonry )
			return;

		var container = $( '#projects section' );

		if ( container.masonry() )
			container.masonry( 'reload' );
		
		container.imagesLoaded( function() {
			container.masonry({
				itemSelector : '.hentry'
			});
		});
	}

	function campaignTabs() {
		var tabs     = $( '.campaign-tabs' ),
		    overview = $( '.campaign-view-descrption' ),
		    tablinks = $( '.sort-tabs.campaign a' );
		
		tabs.children( 'div' ).hide();

		tabs.find( ':first-child' ).show();

		tablinks.click(function(e) {
			if ( $(this).hasClass( 'tabber' ) ) {
				var link = $(this).attr( 'href' );
					
				tabs.children( 'div' ).hide();
				overview.show();
				tabs.find( link ).show();
				
				$( 'body' ).animate({
					scrollTop: $(link).offset().top - 200
				});
			}
		});
	}

	function campaignPledgeLevels() {
		$( '.single-reward-levels li' ).click( function(e) {
			e.preventDefault();

			if ( $( this ).hasClass( 'inactive' ) )
				return false;

			var price = $( this ).data( 'price' );

			Fundify.App.fancyBox( $(this), {
				items : {
					src  : '#contribute-modal-wrap'
				},
				callbacks: {
					beforeOpen : function() {
						$( '#contribute-modal-wrap .edd_price_options' )
							.find( 'li[data-price="' + price + '"]' )
							.trigger( 'click' );
					}
				}
			});
		} );
	}

	function campaignWidget() {
		$( 'body.campaign-widget a' ).attr( 'target', '_blank' );
	}

	return {
		init : function() {
			campaignGrid();
			campaignTabs();
			campaignPledgeLevels();
			campaignWidget();
		},

		resizeGrid : function() {
			campaignGrid();
		}
	}
} )(jQuery);

Crowdfunding.Campaign = ( function($) {
  var customPriceField,
      priceOptions,
      submitButton,
      currentPrice,
      startPledgeLevel;

  var formatCurrencySettings = {};

  function priceOptionsHandler() {
    customPriceField.keyup(function() {
      var price = $( this ).asNumber( formatCurrencySettings );

      delay( function() {
        if ( price < startPledgeLevel )
          Crowdfunding.Campaign.findPrice( startPledgeLevel );
        else
          Crowdfunding.Campaign.findPrice( price );
      }, 1000);
    });

    priceOptions.click(function(e) {
      var pledgeLevel = $(this),
          price       = Crowdfunding.Campaign.parsePrice( $(this) );

      if ( pledgeLevel.hasClass( 'inactive' ) )
        return;

      $(this).find( 'input[type="radio"]' ).attr( 'checked', true );

      customPriceField
        .val( price )
        .formatCurrency( formatCurrencySettings );
    });
  }

  return {
    init : function() {
      formatCurrencySettings = {
        'decimalSymbol'    : atcfSettings.campaign.currency.decimal,
        'digitGroupSymbol' : atcfSettings.campaign.currency.thousands,
        'symbol'           : ''
      }
      
      currentPrice      = 0;
      customPriceField  = $( '#atcf_custom_price' );
      priceOptions      = $( '.atcf-price-option' );
      submitButton      = $( 'a.edd-add-to-cart' );
      
      Crowdfunding.Campaign.setBasePrice();
      priceOptionsHandler();
    },

    findPrice : function( price ) {
      var foundPrice  = {
        price : 0,
        el    : null
      };

      customPriceField
        .val( price )
        .formatCurrency( formatCurrencySettings );

      currentPrice = price;

      priceOptions.each( function( index ) {
        var price       = price = Crowdfunding.Campaign.parsePrice( $(this) );
        var pledgeLevel = parseFloat( price );

        if ( ( currentPrice >= pledgeLevel ) && ! $( this ).hasClass( 'inactive' ) ) {
          var is_greater = pledgeLevel > foundPrice.price;

          if ( is_greater ) {
            foundPrice = {
              price : pledgeLevel,
              el    : $(this)
            }
          }
        }
      });

      foundPrice.el.find( 'input[type="radio"]' ).attr( 'checked', true );
    },

    setBasePrice : function() {
      var basePrice = {
        price : 1000000000, // something crazy
        el    : null
      }

      priceOptions.each( function( index ) {
        if ( ! $( this ).hasClass( 'inactive' ) ) {
          var price = Crowdfunding.Campaign.parsePrice( $(this) );
          
          if ( parseFloat( price ) < parseFloat( basePrice.price ) ) {
            basePrice = {
              price : price,
              el     : $( this )
            }
          }
        }
      });

      startPledgeLevel = parseFloat( basePrice.price );

      if ( null != basePrice.el )
        basePrice.el.find( 'input[type="radio"]' ).attr( 'checked', true );
      
      if ( atcfSettings.campaign.isDonations != 1 ) {
        customPriceField
          .val( startPledgeLevel )
          .formatCurrency( formatCurrencySettings );
      }
    },

    parsePrice : function( el ) {
      var price = el.data( 'price' );

      price = price.split( '-' );
      price = price[0];

      return price;
    }
  }
}(jQuery));


jQuery(document).ready(function($) {
	Fundify.Campaign.init();
  if ( atcfSettings.pages.is_campaign )
    Crowdfunding.Campaign.init();
});
