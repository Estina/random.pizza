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
                return $('#city').val();
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
                        cityId: _this.City.getId(),
                        restaurantId: _this.Restaurant.getId(),
                        qty: $('#qty').val(),
                        meat: $('#meat').prop('checked') ? 1 : 0,
                        vegetarian: $('#vegetarian').prop('checked') ? 1 : 0,
                        fish: $('#fish').prop('checked') ? 1 : 0,
                        hot: $('#hot').prop('checked') ? 1 : 0
                    },
                    success: function (response) {
                        console.log(response);
                        //window.location.href = "/" + response;
                    }
                });
            });

        }
    };

})(this.jQuery);


