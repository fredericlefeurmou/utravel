!function(c){if(Array.prototype.forEach===null||Array.prototype.forEach===undefined){Array.prototype.forEach=function(e){var d;for(d=0;d<this.length;++d){e(this[d])}}}if(typeof ko!=="undefined"&&ko.bindingHandlers&&!ko.bindingHandlers.multiselect){ko.bindingHandlers.multiselect={init:function(g,h,i,f,d){var j=i().selectedOptions,e=ko.utils.unwrapObservable(h());c(g).multiselect(e);if(a(j)){j.subscribe(function(m){var k=[],l=[];m.forEach(function(n){switch(n.status){case"added":k.push(n.value);break;case"deleted":l.push(n.value);break}});if(k.length>0){c(g).multiselect("select",k)}if(l.length>0){c(g).multiselect("deselect",l)}},null,"arrayChange")}},update:function(i,j,k,h,d){var g=k().options,f=c(i).data("multiselect"),e=ko.utils.unwrapObservable(j());if(a(g)){g.subscribe(function(l){c(i).multiselect("rebuild")})}if(!f){c(i).multiselect(e)}else{f.updateOriginalOptions()}}}}function a(d){return ko.isObservable(d)&&!(d.destroyAll===undefined)}function b(d,e){this.options=this.mergeOptions(e);this.$select=c(d);this.originalOptions=this.$select.clone()[0].options;this.query="";this.searchTimeout=null;this.options.multiple=this.$select.attr("multiple")==="multiple";this.options.onChange=c.proxy(this.options.onChange,this);this.options.onDropdownShow=c.proxy(this.options.onDropdownShow,this);this.options.onDropdownHide=c.proxy(this.options.onDropdownHide,this);this.buildContainer();this.buildButton();this.buildDropdown();this.buildSelectAll();this.buildDropdownOptions();this.buildFilter();this.updateButtonText();this.updateSelectAll();this.$select.hide().after(this.$container)}b.prototype={defaults:{buttonText:function(e,d){if(e.length===0){return this.nonSelectedText+' <b class="caret"></b>'}else{if(e.length>this.numberDisplayed){return e.length+" "+this.nSelectedText+' <b class="caret"></b>'}else{var f="";e.each(function(){var g=(c(this).attr("label")!==undefined)?c(this).attr("label"):c(this).html();f+=g+", "});return f.substr(0,f.length-2)+' <b class="caret"></b>'}}},buttonTitle:function(e,d){if(e.length===0){return this.nonSelectedText}else{var f="";e.each(function(){f+=c(this).text()+", "});return f.substr(0,f.length-2)}},label:function(d){return c(d).attr("label")||c(d).html()},onChange:function(d,e){},onDropdownShow:function(d){},onDropdownHide:function(d){},buttonClass:"btn btn-default",dropRight:false,selectedClass:"active",buttonWidth:"auto",buttonContainer:'<div class="btn-group" />',maxHeight:false,checkboxName:"multiselect",includeSelectAllOption:false,includeSelectAllIfMoreThan:0,selectAllText:" Select all",selectAllValue:"multiselect-all",enableFiltering:false,enableCaseInsensitiveFiltering:false,filterPlaceholder:"Search",filterBehavior:"text",preventInputChangeEvent:false,nonSelectedText:"None selected",nSelectedText:"selected",numberDisplayed:3,templates:{button:'<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"></button>',ul:'<ul class="multiselect-container dropdown-menu"></ul>',filter:'<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',li:'<li><a href="javascript:void(0);"><label></label></a></li>',divider:'<li class="multiselect-item divider"></li>',liGroup:'<li class="multiselect-item group"><label class="multiselect-group"></label></li>'}},constructor:b,buildContainer:function(){this.$container=c(this.options.buttonContainer);this.$container.on("show.bs.dropdown",this.options.onDropdownShow);this.$container.on("hide.bs.dropdown",this.options.onDropdownHide)},buildButton:function(){this.$button=c(this.options.templates.button).addClass(this.options.buttonClass);if(this.$select.prop("disabled")){this.disable()}else{this.enable()}if(this.options.buttonWidth&&this.options.buttonWidth!=="auto"){this.$button.css({width:this.options.buttonWidth})}var d=this.$select.attr("tabindex");if(d){this.$button.attr("tabindex",d)}this.$container.prepend(this.$button)},buildDropdown:function(){this.$ul=c(this.options.templates.ul);if(this.options.dropRight){this.$ul.addClass("pull-right")}if(this.options.maxHeight){this.$ul.css({"max-height":this.options.maxHeight+"px","overflow-y":"auto","overflow-x":"hidden"})}this.$container.append(this.$ul)},buildDropdownOptions:function(){this.$select.children().each(c.proxy(function(e,f){var d=c(f).prop("tagName").toLowerCase();if(d==="optgroup"){this.createOptgroup(f)}else{if(d==="option"){if(c(f).data("role")==="divider"){this.createDivider()}else{this.createOptionValue(f)}}}},this));c("li input",this.$ul).on("change",c.proxy(function(h){var d=c(h.target);var g=d.prop("checked")||false;var e=d.val()===this.options.selectAllValue;if(this.options.selectedClass){if(g){d.parents("li").addClass(this.options.selectedClass)}else{d.parents("li").removeClass(this.options.selectedClass)}}var i=d.val();var j=this.getOptionByValue(i);var k=c("option",this.$select).not(j);var f=c("input",this.$container).not(d);if(e){if(g){this.selectall()}else{this.deselectall()}}if(!e){if(g){j.prop("selected",true);if(this.options.multiple){j.prop("selected",true)}else{if(this.options.selectedClass){c(f).parents("li").removeClass(this.options.selectedClass)}c(f).prop("checked",false);k.prop("selected",false);this.$button.click()}if(this.options.selectedClass==="active"){k.parents("a").css("outline","")}}else{j.prop("selected",false)}}this.$select.change();this.updateButtonText();this.updateSelectAll();this.options.onChange(j,g);if(this.options.preventInputChangeEvent){return false}},this));c("li a",this.$ul).on("touchstart click",function(i){i.stopPropagation();var d=c(i.target);if(i.shiftKey){var h=d.prop("checked")||false;if(h){var g=d.parents("li:last").siblings('li[class="active"]:first');var f=d.parents("li").index();var e=g.index();if(f>e){d.parents("li:last").prevUntil(g).each(function(){c(this).find("input:first").prop("checked",true).trigger("change")})}else{d.parents("li:last").nextUntil(g).each(function(){c(this).find("input:first").prop("checked",true).trigger("change")})}}}d.blur()});this.$container.off("keydown.multiselect").on("keydown.multiselect",c.proxy(function(g){if(c('input[type="text"]',this.$container).is(":focus")){return}if((g.keyCode===9||g.keyCode===27)&&this.$container.hasClass("open")){this.$button.click()}else{var h=c(this.$container).find("li:not(.divider):not(.disabled) a").filter(":visible");if(!h.length){return}var d=h.index(h.filter(":focus"));if(g.keyCode===38&&d>0){d--}else{if(g.keyCode===40&&d<h.length-1){d++}else{if(!~d){d=0}}}var f=h.eq(d);f.focus();if(g.keyCode===32||g.keyCode===13){var e=f.find("input");e.prop("checked",!e.prop("checked"));e.change()}g.stopPropagation();g.preventDefault()}},this))},createOptionValue:function(e){if(c(e).is(":selected")){c(e).prop("selected",true)}var d=this.options.label(e);var i=c(e).val();var h=this.options.multiple?"checkbox":"radio";var j=c(this.options.templates.li);c("label",j).addClass(h);c("label",j).append('<input type="'+h+'" name="'+this.options.checkboxName+'" />');var g=c(e).prop("selected")||false;var f=c("input",j);f.val(i);if(i===this.options.selectAllValue){j.addClass("multiselect-item multiselect-all");f.parent().parent().addClass("multiselect-all")}c("label",j).append(" "+d);this.$ul.append(j);if(c(e).is(":disabled")){f.attr("disabled","disabled").prop("disabled",true).parents("a").attr("tabindex","-1").parents("li").addClass("disabled")}f.prop("checked",g);if(g&&this.options.selectedClass){f.parents("li").addClass(this.options.selectedClass)}},createDivider:function(e){var d=c(this.options.templates.divider);this.$ul.append(d)},createOptgroup:function(d){var f=c(d).prop("label");var e=c(this.options.templates.liGroup);c("label",e).text(f);this.$ul.append(e);if(c(d).is(":disabled")){e.addClass("disabled")}c("option",d).each(c.proxy(function(g,h){this.createOptionValue(h)},this))},buildSelectAll:function(){var d=this.hasSelectAll();if(!d&&this.options.includeSelectAllOption&&this.options.multiple&&c("option",this.$select).length>this.options.includeSelectAllIfMoreThan){if(this.options.includeSelectAllDivider){this.$ul.prepend(c(this.options.templates.divider))}var f=c(this.options.templates.li);c("label",f).addClass("checkbox");c("label",f).append('<input type="checkbox" name="'+this.options.checkboxName+'" />');var e=c("input",f);e.val(this.options.selectAllValue);f.addClass("multiselect-item multiselect-all");e.parent().parent().addClass("multiselect-all");c("label",f).append(" "+this.options.selectAllText);this.$ul.prepend(f);e.prop("checked",false)}},buildFilter:function(){if(this.options.enableFiltering||this.options.enableCaseInsensitiveFiltering){var d=Math.max(this.options.enableFiltering,this.options.enableCaseInsensitiveFiltering);if(this.$select.find("option").length>=d){this.$filter=c(this.options.templates.filter);c("input",this.$filter).attr("placeholder",this.options.filterPlaceholder);this.$ul.prepend(this.$filter);this.$filter.val(this.query).on("click",function(e){e.stopPropagation()}).on("input keydown",c.proxy(function(e){clearTimeout(this.searchTimeout);this.searchTimeout=this.asyncFunction(c.proxy(function(){if(this.query!==e.target.value){this.query=e.target.value;c.each(c("li",this.$ul),c.proxy(function(g,h){var j=c("input",h).val();var k=c("label",h).text();var f="";if((this.options.filterBehavior==="text")){f=k}else{if((this.options.filterBehavior==="value")){f=j}else{if(this.options.filterBehavior==="both"){f=k+"\n"+j}}}if(j!==this.options.selectAllValue&&k){var i=false;if(this.options.enableCaseInsensitiveFiltering&&f.toLowerCase().indexOf(this.query.toLowerCase())>-1){i=true}else{if(f.indexOf(this.query)>-1){i=true}}if(i){c(h).show().removeClass("filter-hidden")}else{c(h).hide().addClass("filter-hidden")}}},this))}this.updateSelectAll()},this),300,this)},this))}}},destroy:function(){this.$container.remove();this.$select.show();this.$select.data("multiselect",null)},refresh:function(){c("option",this.$select).each(c.proxy(function(d,e){var f=c("li input",this.$ul).filter(function(){return c(this).val()===c(e).val()});if(c(e).is(":selected")){f.prop("checked",true);if(this.options.selectedClass){f.parents("li").addClass(this.options.selectedClass)}}else{f.prop("checked",false);if(this.options.selectedClass){f.parents("li").removeClass(this.options.selectedClass)}}if(c(e).is(":disabled")){f.attr("disabled","disabled").prop("disabled",true).parents("li").addClass("disabled")}else{f.prop("disabled",false).parents("li").removeClass("disabled")}},this));this.updateButtonText();this.updateSelectAll()},select:function(h){if(!c.isArray(h)){h=[h]}for(var d=0;d<h.length;d++){var f=h[d];var g=this.getOptionByValue(f);var e=this.getInputByValue(f);if(this.options.selectedClass){e.parents("li").addClass(this.options.selectedClass)}e.prop("checked",true);g.prop("selected",true)}this.updateButtonText()},clearSelection:function(){this.deselectall(false);this.updateButtonText();this.updateSelectAll()},deselect:function(d){if(!c.isArray(d)){d=[d]}for(var e=0;e<d.length;e++){var g=d[e];var h=this.getOptionByValue(g);var f=this.getInputByValue(g);if(this.options.selectedClass){f.parents("li").removeClass(this.options.selectedClass)}f.prop("checked",false);h.prop("selected",false)}this.updateButtonText()},selectall:function(){var h=c("li input[type='checkbox']:enabled",this.$ul),f=h.filter(":visible"),g=h.length,d=f.length;f.prop("checked",true);c("li:not(.divider):not(.disabled)",this.$ul).filter(":visible").addClass(this.options.selectedClass);if(g===d){c("option:enabled",this.$select).prop("selected",true)}else{var e=f.map(function(){return c(this).val()}).get();c("option:enabled",this.$select).filter(function(i){return c.inArray(c(this).val(),e)!==-1}).prop("selected",true)}},deselectall:function(d){var g=c("li input[type='checkbox']:enabled",this.$ul),d=typeof d==="undefined"?true:d,f=void (0);if(d){var e=void (0);f=g.filter(":visible");f.prop("checked",false);e=f.map(function(){return c(this).val()}).get();c("option:enabled",this.$select).filter(function(h){return c.inArray(c(this).val(),e)!==-1}).prop("selected",false);c("li:not(.divider):not(.disabled)",this.$ul).filter(":visible").removeClass(this.options.selectedClass)}else{g.prop("checked",false);c("option:enabled",this.$select).prop("selected",false);c("li:not(.divider):not(.disabled)",this.$ul).removeClass(this.options.selectedClass)}},rebuild:function(){this.$ul.html("");this.options.multiple=this.$select.attr("multiple")==="multiple";this.buildSelectAll();this.buildDropdownOptions();this.buildFilter();this.updateButtonText();this.updateSelectAll()},dataprovider:function(d){var e="";d.forEach(function(f){e+='<option value="'+f.value+'">'+f.label+"</option>"});this.$select.html(e);this.rebuild()},enable:function(){this.$select.prop("disabled",false);this.$button.prop("disabled",false).removeClass("disabled")},disable:function(){this.$select.prop("disabled",true);this.$button.prop("disabled",true).addClass("disabled")},setOptions:function(d){this.options=this.mergeOptions(d)},mergeOptions:function(d){return c.extend(true,{},this.defaults,d)},hasSelectAll:function(){return c("li."+this.options.selectAllValue,this.$ul).length>0},updateSelectAll:function(){if(this.hasSelectAll()){var h=c("li:not(.multiselect-item):not(.filter-hidden) input:enabled",this.$ul),g=h.length,e=h.filter(":checked").length,d=c("li."+this.options.selectAllValue,this.$ul),f=d.find("input");if(e>0&&e===g){f.prop("checked",true);d.addClass(this.options.selectedClass)}else{f.prop("checked",false);d.removeClass(this.options.selectedClass)}}},updateButtonText:function(){var d=this.getSelected();c("button",this.$container).html(this.options.buttonText(d,this.$select));c("button",this.$container).attr("title",this.options.buttonTitle(d,this.$select))},getSelected:function(){return c("option",this.$select).filter(":selected")},getOptionByValue:function(g){var d=c("option",this.$select);var h=g.toString();for(var e=0;e<d.length;e=e+1){var f=d[e];if(f.value===h){return c(f)}}},getInputByValue:function(g){var f=c("li input",this.$ul);var h=g.toString();for(var d=0;d<f.length;d=d+1){var e=f[d];if(e.value===h){return c(e)}}},updateOriginalOptions:function(){this.originalOptions=this.$select.clone()[0].options},asyncFunction:function(g,f,d){var e=Array.prototype.slice.call(arguments,3);return setTimeout(function(){g.apply(d||window,e)},f)}};c.fn.multiselect=function(d,e){return this.each(function(){var g=c(this).data("multiselect");var f=typeof d==="object"&&d;if(!g){g=new b(this,f);c(this).data("multiselect",g)}if(typeof d==="string"){g[d](e);if(d==="destroy"){c(this).data("multiselect",false)}}})};c.fn.multiselect.Constructor=b;c(function(){c("select[data-role=multiselect]").multiselect()})}(window.jQuery);