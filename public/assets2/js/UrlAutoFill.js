// auto fill url input
class UrlAutoFill {
    constructor(data) {
        var _this = this;
        _this.keyup = null;
        _this.data = data;
        _this.values = [];                

        if (!_this.keyup) {
            _this.keyup = $(document).on('keyup', '[type=url]', function(event) {
                if(event.keyCode !== 13 && event.keyCode !== 38 && event.keyCode !== 40 ) {
                    _this.input = $(this);
                    _this.updateValues();
                    _this.showDropdown();
                }

                if(event.keyCode == 38) {
                    _this.moveUp();
                    event.preventDefault();
                }
                if(event.keyCode == 40) {
                    _this.moveDown();
                    event.preventDefault();
                }
                if(event.keyCode == 13) {                    
                    event.preventDefault();
                    if (_this.current) {
                        _this.select(_this.current);
                    }
                    return false;
                }
            })

            _this.keyword = function() {
                return $('[type=url]').val();
            }

            $(document).on('click', '.urlfill-dropdown li', function() {
                _this.select($(this));
            })

            $(document).on('focus click', '[type=url]', function(event) {
                _this.input = $(this);
                _this.updateValues();
                setTimeout(function() {
                    _this.showDropdown();
                }, 100);
            });

            $(document).on('focusout', '[type=url]', function(event) {
                setTimeout(function() {
                    _this.hideDropdown();
                }, 100);
            });
        }                    
    }

    moveUp() {
        var _this = this;
        if (_this.dropdown) {
            if (!_this.current || !_this.current.prev().length) {
                _this.setCurrent(_this.dropdown.find('li').last());
            } else {
                _this.setCurrent(_this.current.prev());
            }
        }
    }

    moveDown() {
        var _this = this;
        if (_this.dropdown) {
            if (!_this.current || !_this.current.next().length) {
                _this.setCurrent(_this.dropdown.find('li').first());
            } else {
                _this.setCurrent(_this.current.next());
            }
        }
    }

    setCurrent(li) {
        var _this = this;
        _this.current = li;

        _this.dropdown.find('li').removeClass('current');
        _this.current.addClass('current');
    }

    updateValues() {
        var _this = this;
        _this.values = [];

        var matches = [];
        _this.data.forEach(function(item) {
            if (_this.keyword() != '' && item.value.toLowerCase().indexOf(_this.keyword().toLowerCase()) === 0) {
                matches.push(item.value);
                _this.values.push(item);
            }
        });

        _this.data.forEach(function(item) {
            if (!matches.includes(item.value)) {
                _this.values.push(item);
            }
        });
    }

    showDropdown() {
        var _this = this;
        
        // remove current dropdown
        _this.hideDropdown();

        if (_this.values.length) {
            _this.dropdown = $('<ul class="urlfill-dropdown">');

            _this.values.forEach(function(item) {
                _this.dropdown.append($('<li data="'+item.text+'">').html(item.value));
            });

            _this.input.after(_this.dropdown);
            // select first
            _this.setCurrent(_this.dropdown.find('li').first());
        }
    }

    hideDropdown() {
        var _this = this;
        
        // remove current dropdown
        if (_this.dropdown) {
            _this.dropdown.remove();
            _this.dropdown = null;
        }
    }

    select(item) {
        var _this = this;
        var value = item.html();

        console.log(item);

        _this.input.val(value);
        _this.input.focusout();

        _this.input.closest('.tox-form__group').next().find('input').val(item.attr('data'));
    }
}        