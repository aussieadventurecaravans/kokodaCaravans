/**
 * ui-choose Universal selection plugin
 * based on jQuery
 */
; + function($) {
    "use strict";
    //Default instantiation configuration
    var defaults = {
        itemWidth: null,
        skin: '',
        multi: false,
        active: 'selected',
        full: false, //Whether to use the flex layout, each element has the same width
        colNum: null, //Number of lines displayed per line
        dataKey: 'ui-choose', //Instantiated data key value for easy follow-up data('ui-choose') take out ；
        change: null, //choose Callback when the value changes；
        click: null //choose Callback when the element is clicked，diabled Does not happen。
    };
    /**
     * ui-choose插件
     */
    $.fn.ui_choose = function(options) {
        var _this = $(this),
            _num = _this.length;
        // Instantiate the returned object directly when there is only one object to be instantiated；
        if (_num === 1) {
            return new UI_choose(_this, options);
        };
        //When there are multiple objects to be instantiated, the loop is instantiated without returning the object;
        if (_num > 1) {
            _this.each(function(index, el) {
                new UI_choose($(el), options);
            })
        }
        // When the number of elements is 0, instantiation is not performed.
    };

    /**
     * UI_choose Object
     * @param {[jQuery]} el  [jQuery The selected object, the single element passed in here]
     * @param {[object]} opt [Set parameters]
     */
    function UI_choose(el, opt) {
        this.el = el;
        this._tag = this.el.prop('tagName').toLowerCase();
        this._opt = $.extend({}, defaults, opt);

        return this._init();
    }

    // UI_choose Prototype chain extension。
    UI_choose.prototype = {

        // Init initialization;
        _init: function() {
            var _data = this.el.data(this._opt.dataKey);

            // Return directly if it has been instantiated
          /*
          if (_data)
                return _data;
            else
                this.el.data(this._opt.dataKey, this);
          */
            // Set whether to choose multiple
            if (this._tag == 'select') {
                this.multi = this.el.prop('multiple');
            } else {
                this.multi = this.el.attr('multiple') ? !!this.el.attr('multiple') : this._opt.multi;
            }

            //Different element formation according to different labels
            var _setFunc = this['_setHtml_' + this._tag];
            if (_setFunc) {
                _setFunc.call(this);
            }
            if (this._opt.full) {
                this._wrap.addClass('choose-flex');
            }
            this._wrap.addClass(this._opt.skin);
            if (this.multi && !this._opt.skin)
                this._wrap.addClass('choose-type-right');
            this._bindEvent(); // 绑定事件
        },

        // Organize and get related dom elements-ul;
        _setHtml_ul: function() {
            this._wrap = this.el;
            this._items = this.el.children('li');
            if (this._opt.itemWidth) {
                this._items.css('width', this._opt.itemWidth);
            }
        },

        // Organize and get related dom elements-select;
        _setHtml_select: function() {
            var _ohtml = '<ul class="ui-choose">';
            this.el.find('option').each(function(index, el) {
                var _this = $(el),
                    _text = _this.text(),
                    _value = _this.prop('value'),
                    _selected = _this.prop('selected') ? 'selected' : '',
                    _disabled = _this.prop('disabled') ? ' disabled' : '';
                _ohtml += '<li title="' + _text + '" data-value="' + _value + '" class="' + _selected + _disabled + '">' + _text + '</li> ';
            });
            _ohtml += '</ul>';
            this.el.after(_ohtml);

            this._wrap = this.el.next('ul.ui-choose');
            this._items = this._wrap.children('li');
            if (this._opt.itemWidth) {
                this._items.css('width', this._opt.itemWidth);
            }
            this.el.hide();
        },

        // Binding event；
        _bindEvent: function() {
            var _this = this;
            _this._wrap.on('click', 'li', function() {
                var _self = $(this);
                if (_self.hasClass('disabled'))
                    return;

                if (!_this.multi) { // single select
                    var _val = _self.attr('data-value') || _self.index();
                    _this.val(_val);
                    _this._triggerClick(_val, _self);
                } else { // multiple
                    _self.toggleClass(_this._opt.active);
                    var _val = [];
                    _this._items.each(function(index, el) {
                        var _el = $(this);
                        if (_el.hasClass(_this._opt.active)) {
                            var _valOrIndex = _this._tag == 'select' ? _el.attr('data-value') : _el.index();
                            _val.push(_valOrIndex);
                        }
                    });
                    _this.val(_val);
                    _this._triggerClick(_val, _self);
                }
            });
            return _this;
        },

        // change trigger；value：value ；item：Chosen option；
        _triggerChange: function(value, item) {
            item = item || this._wrap;
            this.change(value, item);
            if (typeof this._opt.change == 'function')
                this._opt.change.call(this, value, item);
        },

        // click 触发；value：值 ；item：选中的option；
        _triggerClick: function(value, item) {
            this.click(value, item);
            if (typeof this._opt.click == 'function')
                this._opt.click.call(this, value, item);
        },

        // Get or set a value:select
        _val_select: function(value) {
            // getValue
            if (arguments.length === 0) {
                return this.el.val();
            }
            // setValue
            var _oValue = this.el.val();
            if (!this.multi) { // single select
                var _selectedItem = this._wrap.children('li[data-value="' + value + '"]');
                if (!_selectedItem.length)
                    return this;
                this.el.val(value);
                _selectedItem.addClass(this._opt.active).siblings('li').removeClass(this._opt.active);
                if (value !== _oValue) {
                    this._triggerChange(value);
                }
            } else { // multiple select
                if (value == null || value == '' || value == []) {
                    this.el.val(null);
                    this._items.removeClass(this._opt.active);
                } else {
                    value = typeof value == 'object' ? value : [value];
                    this.el.val(value);
                    this._items.removeClass(this._opt.active);
                    for (var i in value) {
                        var _v = value[i];
                        this._wrap.children('li[data-value="' + _v + '"]').addClass(this._opt.active);
                    }
                }
                if (value !== _oValue) {
                    this._triggerChange(value);
                }
            }
            // multiple
            return this;
        },

        // Get or set a value:ul
        _val_ul: function(index) {
            // getValue
            if (arguments.length === 0) {
                var _oActive = this._wrap.children('li.' + this._opt.active);
                if (!this.multi) { // single select
                    return _oActive.index() == -1 ? null : _oActive.index();
                } else { // single select
                    if (_oActive.length == 0) {
                        return null;
                    }
                    var _this = this,
                        _val = [];
                    _oActive.each(function(index, el) {
                        var _el = $(el);
                        if (_el.hasClass(_this._opt.active)) {
                            _val.push(_el.index());
                        }
                    });
                    return _val;
                }
            }
            // setValue
            var _oIndex = this._val_ul();
            if (!this.multi) { // single select
                var _selectedItem = this._wrap.children('li').eq(index);
                if (!_selectedItem.length)
                    return this;
                _selectedItem.addClass(this._opt.active).siblings('li').removeClass(this._opt.active);
                if (index !== _oIndex) {
                    this._triggerChange(index, _selectedItem);
                }
            } else { // multiple select
                if (index == null || index == '' || index == []) {
                    this._items.removeClass(this._opt.active);
                } else {
                    index = typeof index == 'object' ? index : [index];
                    this._items.removeClass(this._opt.active);
                    for (var i in index) {
                        var _no = index[i];
                        this._wrap.children('li').eq(_no).addClass(this._opt.active);
                    }
                }
                if (index !== _oIndex) {
                    this._triggerChange(index);
                }
            }
            // multiple
            return this;
        },

        // Get or set a value
        val: function() {
            return this['_val_' + this._tag].apply(this, arguments);
        },

        // Value change event；
        change: function(value, item) {},

        // Click event；
        click: function(value, item) {},

        //hide
        hide: function() {
            this._wrap.hide();
            return this;
        },

        // display
        show: function() {
            this._wrap.show();
            return this;
        },

        // select all
        selectAll: function() {
            if (!this.multi)
                return this;
            if (this._tag == 'select') {
                this.el.find('option').not(':disabled').prop('selected', true);
                var _val = this.el.val();
                this.val(_val);
            } else {
                var _val = [];
                this._items.not('.disabled').each(function(index, el) {
                    _val.push(index);
                });
                this.val(_val);
            }
            return this;
        }
    };
}(jQuery);
