/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   design
 * @package    TIG_Postcode
 * @copyright  Copyright (c) 2012 Total Internet Group (http://www.totalinternetgroup.nl)
 * @author     Total Internet Group (http://www.totalinternetgroup.nl)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
TigPostcodeUpdater = Class.create();
TigPostcodeUpdater.prototype = {

    initialize: function (loadUrl, postcodeEl, housenumberEl, streetEl, cityEl, countryEl, regionEl, loaderEl)
    {
        this.loadUrl = loadUrl;
        this.postcodeEl = $(postcodeEl);
        this.housenumberEl = $(housenumberEl);
        this.streetEl = $(streetEl);
        this.cityEl = $(cityEl);
        this.countryEl = $(countryEl);
        this.regionEl = regionEl;
        this.loaderEl = loaderEl;
        this.errorEl = 'advice-validate:' + postcodeEl;

        Event.observe(this.countryEl, 'change', this.getData.bind(this));
        Event.observe(this.postcodeEl, 'change', this.getData.bind(this));
        Event.observe(this.housenumberEl, 'change', this.getData.bind(this));
    },

    getData: function() {
    	if (this.countryEl.value != 'NL') {
    		this._activate(this.streetEl);
        	this._activate(this.cityEl);
        	try {
	        	$$('#'+this.regionEl+' input')[0].enable();
	        	$$('#'+this.regionEl+' select')[0].enable();
	        	Element.show(this.regionEl);
	        	new Effect.Fade(this.errorEl, {duration : 0.3});
	        	this.postcodeEl.removeClassName('tig-validation-failed');
	        	this.postcodeEl.removeClassName('tig-validation-passed');
	        	this.housenumberEl.removeClassName('tig-validation-failed');
	        	this.housenumberEl.removeClassName('tig-validation-passed');
        	} catch(e){}
    		return false;
    	}
    	else {
        	this._deactivate(this.streetEl);
        	this._deactivate(this.cityEl);
        	try {
	        	Element.hide(this.regionEl);
	        	$$('#'+this.regionEl+' input')[0].disable();
	        	$$('#'+this.regionEl+' select')[0].disable();
        	} catch(e){}
    		var _action = this.nlAddress.bind(this); _action();
    	}
    },

    validation: function() {
    	this.postcodeEl.value = this._trim(this.postcodeEl.value);
    	this.housenumberEl.value = this._trim(this.housenumberEl.value);
    	if (this.postcodeEl.value == '' || this.housenumberEl.value == '') {
        	this.postcodeEl.removeClassName('tig-validation-passed');
        	this.housenumberEl.removeClassName('tig-validation-passed');
        	new Effect.Fade(this.errorEl, {duration : 0.3});
    		return false;
    	}
    	if (!/^[0-9]{4}\s?[a-z]{2}$/i.test(this.postcodeEl.value) || !/^[0-9]+[a-z]{0,3}(\/[0-9]+[a-z]{0,3})*$/i.test(this.housenumberEl.value)) {
    		this._activate(this.streetEl);
        	this._activate(this.cityEl);
        	this.postcodeEl.addClassName('tig-validation-failed');
        	this.postcodeEl.removeClassName('tig-validation-passed');
        	this.housenumberEl.addClassName('tig-validation-failed');
        	this.housenumberEl.removeClassName('tig-validation-passed');
        	new Effect.Appear(this.errorEl, {duration : 1 });
    		return false;
    	}
    	new Effect.Fade(this.errorEl, {duration : 1});
    	this.postcodeEl.removeClassName('tig-validation-failed');
    	this.postcodeEl.addClassName('tig-validation-passed');
    	this.housenumberEl.removeClassName('tig-validation-failed');
    	this.housenumberEl.addClassName('tig-validation-passed');
    	return true;
    },


    nlAddress: function() {
    	var _validation = this.validation.bind(this);
    	var _isValid = _validation();
    	if (!_isValid) return false;
    	Element.show(this.loaderEl);
    	$(this.loaderEl).fire("tigpostcodeupdater:start");

    	var redirectUrl = this.loadUrl;
    	if (window.location.protocol == "https:") {
    		redirectUrl = this.loadUrl.replace('http://','https://');
    	}

        new Ajax.Request(
			redirectUrl+'nladdress',
			{
				method: 'post',
				onFailure: this._nothingNlAddress.bind(this),
				onComplete: this._updateNlAddress.bind(this),
				parameters:
				{
					postcode: this.postcodeEl.value,
					housenumber: this.housenumberEl.value
				}
			}
	    );
    },


    _nothingNlAddress: function(response)
    {
    	this._activate(this.streetEl);
    	this._activate(this.cityEl);
    	Element.hide(this.loaderEl);
    	$(this.loaderEl).fire("tigpostcodeupdater:stop");
    },


    _updateNlAddress: function(response)
    {
    	var result = response.responseText.evalJSON();
    	if (result.status == 'success') {
    		this.streetEl.value = result.data.straatnaam;
    		this.cityEl.value = result.data.woonplaats;
    	}
    	else {
        	this._activate(this.streetEl);
        	this._activate(this.cityEl);
    	}
    	Element.hide(this.loaderEl);
    	$(this.loaderEl).fire("tigpostcodeupdater:stop");
    },

    _deactivate: function(el)
    {
    	el.setAttribute('readonly', 'readonly');
    	el.addClassName('readonly');
    },

    _activate: function(el)
    {
    	el.removeAttribute('readonly');
    	el.removeClassName('readonly');
    },

    _trim: function(str)
    {
    	return str.replace(/^\s*((?:[\S\s]*\S)?)\s*$/, '$1');
    }

}