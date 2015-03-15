var App = App || {};

(function($) {

    App.Home = {

        Country: {

            getCode: function() {
                return $('#country').val();
            },

            onChange: function(callback) {
                $('#country').on('change', callback);
            }
        },

        City: {

            getId: function() {
                return $('#city').val();
            },

            setEmpty: function() {
                $('#city-ctr').html(
                    '<select id="city" name="city" class="form-control" disabled>' +
                    '<option value="">select</option>' +
                    '</select>'
                );
            },

            setValues: function(countryCode) {
                $.ajax({
                    type: 'get',
                    url: '/cities/' + countryCode,
                    success: function(response) {
                        $('#city-ctr').html(response);
                        $('#city').focus().select();
                    }
                });
            },

            onChange: function(callback) {
                $("#city-ctr").delegate("#city", "change", callback);
            }

        },

        Restaurant: {

            getId: function() {
                return $('#restaurant').val();
            },

            setEmpty: function() {
                $('#restaurant-ctr').html(
                    '<select id="restaurant" name="restaurant" class="form-control" disabled>' +
                    '<option value="">select</option>' +
                    '</select>'
                );
            },

            hasValues: function() {
                return $('#restaurant-ctr').find('option').length > 0;
            },

            setValues: function(cityId, callback) {
                $.ajax({
                    type: 'get',
                    url: '/restaurants/' + cityId,
                    success: function(response) {
                        $('#restaurant-ctr').html(response);
                        $('#restaurant').focus().select();
                        callback();
                    }
                });
            },

            onChange: function(callback) {
                $("#restaurant-ctr").delegate("#restaurant", "change", callback);
            }
        },

        Generator: {

            enable: function() {
                $('#generate').removeAttr('disabled').removeClass('btn-disabled').addClass('btn-success');
            },

            disable: function() {
                $('#generate').attr('disabled', true).addClass('btn-disabled').removeClass('btn-success');
            },

            onSubmit: function(callback) {
                $('#generate').on('click', callback);
            }


        },

        Settings: {

            qty: function() {
                return $('#qty').val();
            },

            meat: function() {
                return $('#meat').prop('checked') ? 1 : 0;
            },

            vegetarian: function() {
                return $('#vegetarian').prop('checked') ? 1 : 0;
            },

            fish: function() {
                return $('#fish').prop('checked') ? 1 : 0;
            },

            hot: function() {
                return $('#hot').prop('checked') ? 1 : 0;
            }
        },

        init: function() {
            var _this = this;

            if ('' !== this.Country.getCode()) {
                _this.City.setValues(_this.Country.getCode());
            }

            this.Country.onChange(function() {
                if ('' == _this.Country.getCode()) {
                    _this.City.setEmpty();
                } else {
                    _this.City.setValues(_this.Country.getCode());
                }
                _this.Restaurant.setEmpty();
            });

            this.City.onChange(function() {
                if ('' == _this.City.getId()) {
                    _this.Restaurant.setEmpty();
                    _this.Generator.disable();
                } else {
                    _this.Restaurant.setValues(_this.City.getId(), function() {
                        if (_this.Restaurant.hasValues()) {
                            _this.Generator.enable();
                        } else {
                            _this.Generator.disable();
                        }
                    });
                }
            });

            this.Restaurant.onChange(function(){
                if ('' !== _this.Restaurant.getId()) {
                    $('#qty').focus().select();
                }
            });

            this.Generator.onSubmit(function() {
                $.ajax({
                    type: 'POST',
                    url: '/generate',
                    data: {
                        countryCode: _this.Country.getCode(),
                        cityId: _this.City.getId(),
                        restaurantId: _this.Restaurant.getId(),
                        qty: _this.Settings.qty(),
                        meat: _this.Settings.meat(),
                        vegetarian: _this.Settings.vegetarian(),
                        fish: _this.Settings.fish(),
                        hot: _this.Settings.hot()
                    },
                    success: function (response) {
                        if ('undefined' != typeof response.error) {
                            var alert = $('.alert');
                            alert.removeClass('hidden');
                            alert.find('span.message').text(response.error);
                        } else {
                            window.location.href = "/" + response.href;
                        }

                    }
                });
            });

        }
    };

})(this.jQuery);


