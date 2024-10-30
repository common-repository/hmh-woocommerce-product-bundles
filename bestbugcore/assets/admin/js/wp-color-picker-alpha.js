/**!
 * wp-color-picker-alpha
 *
 * Overwrite Automattic Iris for enabled Alpha Channel in wpColorPicker
 * Only run in input and is defined data alpha in true
 *
 * Version: 2.1.3
 * https://github.com/kallookoo/wp-color-picker-alpha
 * Licensed under the GPLv2 license.
 */
!function(a){if(!a.wp.wpColorPicker.prototype._hasAlpha){var b="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAAHnlligAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHJJREFUeNpi+P///4EDBxiAGMgCCCAGFB5AADGCRBgYDh48CCRZIJS9vT2QBAggFBkmBiSAogxFBiCAoHogAKIKAlBUYTELAiAmEtABEECk20G6BOmuIl0CIMBQ/IEMkO0myiSSraaaBhZcbkUOs0HuBwDplz5uFJ3Z4gAAAABJRU5ErkJggg==",c='<div class="wp-picker-holder" />',d='<div class="wp-picker-container" />',e='<input type="button" class="button button-small" />',f=void 0!==wpColorPickerL10n.current;if(f)var g='<a tabindex="0" class="wp-color-result" />';else var g='<button type="button" class="button wp-color-result" aria-expanded="false"><span class="wp-color-result-text"></span></button>',h="<label></label>",i='<span class="screen-reader-text"></span>';Color.fn.toString=function(){if(this._alpha<1)return this.toCSS("rgba",this._alpha).replace(/\s+/g,"");var a=parseInt(this._color,10).toString(16);return this.error?"":(a.length<6&&(a=("00000"+a).substr(-6)),"#"+a)},a.widget("wp.wpColorPicker",a.wp.wpColorPicker,{_hasAlpha:!0,_create:function(){if(a.support.iris){var j=this,k=j.element;if(a.extend(j.options,k.data()),"hue"===j.options.type)return j._createHueOnly();j.close=a.proxy(j.close,j),j.initialValue=k.val(),k.addClass("wp-color-picker"),f?(k.hide().wrap(d),j.wrap=k.parent(),j.toggler=a(g).insertBefore(k).css({backgroundColor:j.initialValue}).attr("title",wpColorPickerL10n.pick).attr("data-current",wpColorPickerL10n.current),j.pickerContainer=a(c).insertAfter(k),j.button=a(e).addClass("hidden")):(k.parent("label").length||(k.wrap(h),j.wrappingLabelText=a(i).insertBefore(k).text(wpColorPickerL10n.defaultLabel)),j.wrappingLabel=k.parent(),j.wrappingLabel.wrap(d),j.wrap=j.wrappingLabel.parent(),j.toggler=a(g).insertBefore(j.wrappingLabel).css({backgroundColor:j.initialValue}),j.toggler.find(".wp-color-result-text").text(wpColorPickerL10n.pick),j.pickerContainer=a(c).insertAfter(j.wrappingLabel),j.button=a(e)),j.options.defaultColor?(j.button.addClass("wp-picker-default").val(wpColorPickerL10n.defaultString),f||j.button.attr("aria-label",wpColorPickerL10n.defaultAriaLabel)):(j.button.addClass("wp-picker-clear").val(wpColorPickerL10n.clear),f||j.button.attr("aria-label",wpColorPickerL10n.clearAriaLabel)),f?k.wrap('<span class="wp-picker-input-wrap" />').after(j.button):(j.wrappingLabel.wrap('<span class="wp-picker-input-wrap hidden" />').after(j.button),j.inputWrapper=k.closest(".wp-picker-input-wrap")),k.iris({target:j.pickerContainer,hide:j.options.hide,width:j.options.width,mode:j.options.mode,palettes:j.options.palettes,change:function(c,d){j.options.alpha?(j.toggler.css({"background-image":"url("+b+")"}),f?j.toggler.html('<span class="color-alpha" />'):(j.toggler.css({position:"relative"}),0==j.toggler.find("span.color-alpha").length&&j.toggler.append('<span class="color-alpha" />')),j.toggler.find("span.color-alpha").css({width:"30px",height:"24px",position:"absolute",top:0,left:0,"border-top-left-radius":"2px","border-bottom-left-radius":"2px",background:d.color.toString()})):j.toggler.css({backgroundColor:d.color.toString()}),a.isFunction(j.options.change)&&j.options.change.call(this,c,d)}}),k.val(j.initialValue),j._addListeners(),j.options.hide||j.toggler.click()}},_addListeners:function(){var b=this;b.wrap.on("click.wpcolorpicker",function(a){a.stopPropagation()}),b.toggler.click(function(){b.toggler.hasClass("wp-picker-open")?b.close():b.open()}),b.element.on("change",function(c){(""===a(this).val()||b.element.hasClass("iris-error"))&&(b.options.alpha?(f&&b.toggler.removeAttr("style"),b.toggler.find("span.color-alpha").css("backgroundColor","")):b.toggler.css("backgroundColor",""),a.isFunction(b.options.clear)&&b.options.clear.call(this,c))}),b.button.on("click",function(c){a(this).hasClass("wp-picker-clear")?(b.element.val(""),b.options.alpha?(f&&b.toggler.removeAttr("style"),b.toggler.find("span.color-alpha").css("backgroundColor","")):b.toggler.css("backgroundColor",""),a.isFunction(b.options.clear)&&b.options.clear.call(this,c)):a(this).hasClass("wp-picker-default")&&b.element.val(b.options.defaultColor).change()})}}),a.widget("a8c.iris",a.a8c.iris,{_create:function(){if(this._super(),this.options.alpha=this.element.data("alpha")||!1,this.element.is(":input")||(this.options.alpha=!1),"undefined"!=typeof this.options.alpha&&this.options.alpha){var b=this,c=b.element,d='<div class="iris-strip iris-slider iris-alpha-slider"><div class="iris-slider-offset iris-slider-offset-alpha"></div></div>',e=a(d).appendTo(b.picker.find(".iris-picker-inner")),f=e.find(".iris-slider-offset-alpha"),g={aContainer:e,aSlider:f};"undefined"!=typeof c.data("custom-width")?b.options.customWidth=parseInt(c.data("custom-width"))||0:b.options.customWidth=100,b.options.defaultWidth=c.width(),(b._color._alpha<1||-1!=b._color.toString().indexOf("rgb"))&&c.width(parseInt(b.options.defaultWidth+b.options.customWidth)),a.each(g,function(a,c){b.controls[a]=c}),b.controls.square.css({"margin-right":"0"});var h=b.picker.width()-b.controls.square.width()-20,i=h/6,j=h/2-i;a.each(["aContainer","strip"],function(a,c){b.controls[c].width(j).css({"margin-left":i+"px"})}),b._initControls(),b._change()}},_initControls:function(){if(this._super(),this.options.alpha){var a=this,b=a.controls;b.aSlider.slider({orientation:"vertical",min:0,max:100,step:1,value:parseInt(100*a._color._alpha),slide:function(b,c){a._color._alpha=parseFloat(c.value/100),a._change.apply(a,arguments)}})}},_change:function(){this._super();var a=this,c=a.element;if(this.options.alpha){var d=a.controls,e=parseInt(100*a._color._alpha),f=a._color.toRgb(),g=["rgb("+f.r+","+f.g+","+f.b+") 0%","rgba("+f.r+","+f.g+","+f.b+", 0) 100%"],h=a.options.defaultWidth,i=a.options.customWidth,j=a.picker.closest(".wp-picker-container").find(".wp-color-result");d.aContainer.css({background:"linear-gradient(to bottom, "+g.join(", ")+"), url("+b+")"}),j.hasClass("wp-picker-open")&&(d.aSlider.slider("value",e),a._color._alpha<1?(d.strip.attr("style",d.strip.attr("style").replace(/rgba\(([0-9]+,)(\s+)?([0-9]+,)(\s+)?([0-9]+)(,(\s+)?[0-9\.]+)\)/g,"rgb($1$3$5)")),c.width(parseInt(h+i))):c.width(h))}var k=c.data("reset-alpha")||!1;k&&a.picker.find(".iris-palette-container").on("click.palette",".iris-palette",function(){a._color._alpha=1,a.active="external",a._change()})},_addInputListeners:function(a){var b=this,c=100,d=function(c){var d=new Color(a.val()),e=a.val();a.removeClass("iris-error"),d.error?""!==e&&a.addClass("iris-error"):d.toString()!==b._color.toString()&&("keyup"===c.type&&e.match(/^[0-9a-fA-F]{3}$/)||b._setOption("color",d.toString()))};a.on("change",d).on("keyup",b._debounce(d,c)),b.options.hide&&a.on("focus",function(){b.show()})}})}}(jQuery);