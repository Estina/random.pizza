var App = App || {};

(function($) {

    App.Form = {

        Country: {

            getCode: function() {
                return $('#country').val();
            },

            onChange: function(callback) {
                $('#country').on('change', callback);
            },

            markError: function() {
                $('#country').parent().parent().addClass('has-error');
            },

            removeError: function() {
                $('#country').parent().parent().removeClass('has-error');
            }
        },

        City: {

            isEmpty: function() {
                return null == $('#city').val();
            },

            setEmpty: function() {
                $('#city-ctr').html(
                    '<select id="city" name="city" class="form-control" disabled multiple="multiple">' +
                    '<option value="">select</option>' +
                    '</select>'
                );
            },

            setValues: function(countryCode) {
                $.ajax({
                    type: 'get',
                    url: '/cities/' + countryCode + '/1',
                    success: function(response) {
                        $('#city-ctr').html(response);
                        $('#city').focus().select();
                    }
                });
            },

            onChange: function(callback) {
                $("#city-ctr").delegate("#city", "change", callback);
            },

            markError: function() {
                $('#city-ctr').parent().addClass('has-error');
            },

            removeError: function() {
                $('#city-ctr').parent().removeClass('has-error');
            }

        },

        AddPizzas: {

            onClick: function(callback) {
                $('#add').on('click', callback);
            },

            getMore: function() {
                var lastIndex = $(document.getElementById('pizza-ctr')).find('.pizza-row').last().data('index');
                $.ajax({
                    type: 'get',
                    url: '/form/more/' + lastIndex,
                    success: function(response) {
                        $('#pizza-ctr').append(response);
                    }
                });
            }
        },

        SubmitButton: {
            onClick: function(callback) {
                $('#submit').on('click', callback);
            },

            enable: function() {
                $('#submit').removeAttr('disabled').removeClass('btn-disabled').addClass('btn-success');
            },

            disable: function() {
                $('#submit').attr('disabled', true).addClass('btn-disabled').removeClass('btn-success');
            }
        },

        validateForm: function() {

            var valid = true;

            if ('' == this.Country.getCode()) {
                this.Country.markError();
                valid = false;
            }

            if (this.City.isEmpty()) {
                this.City.markError();
                valid = false;
            }

            var restaurant = $(document.getElementById('restaurant'));
            if ('' == restaurant.val()) {
                restaurant.parent().parent().addClass('has-error');
                valid = false;
            } else {
                restaurant.parent().parent().removeClass('has-error');
            }

            $('.pizza-row').each(function() {
                if ('' != $(this).find(':text').val() && 0 == $(this).find(':checked').length) {
                    $(this).find('.form-group').addClass('has-error');
                    valid = false;
                } else {
                    $(this).find('.form-group').removeClass('has-error');
                }
            });

            return valid;
        },

        init: function() {
            var _this = this;

            if ('' !== this.Country.getCode()) {
                _this.City.setValues(_this.Country.getCode());
            }

            this.Country.onChange(function() {
                _this.Country.removeError();
                if ('' == _this.Country.getCode()) {
                    _this.City.setEmpty();
                    _this.SubmitButton.disable();
                } else {
                    _this.City.setValues(_this.Country.getCode());
                    _this.SubmitButton.enable();
                }
            });

            this.City.onChange(function() {
                _this.City.removeError();
            });

            this.AddPizzas.onClick(function() {
                _this.AddPizzas.getMore();
            });

            this.SubmitButton.onClick(function() {
                if (_this.validateForm()) {
                    $(document.getElementById('restaurant-form')).submit();
                }
            });

        }
    };

})(this.jQuery);


