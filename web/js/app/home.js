var App = App || {};

(function($) {

    App.Home = {

        setDefaultRestaurant: function() {
            this.setRestaurantHtml(
                '<select id="restaurant" name="restaurant" class="form-control" disabled>' +
                '<option value="">select</option>' +
                '</select>'
            );
        },

        setRestaurantHtml: function(html) {
            $('#restaurant-ctr').html(html);
        },

        setCityHtml: function(html) {
            $('#city-ctr').html(html);
        },

        init: function() {
            var _this = this;
            $('#country').on('change', function(){

                var countryCode = $(this).val();

                if ('' == countryCode) {
                    _this.setCityHtml(
                        '<select id="city" name="city" class="form-control" disabled>' +
                        '<option value="">select</option>' +
                        '</select>'
                    );

                } else {

                    $.ajax({
                        type: 'get',
                        url: '/cities/' + countryCode,
                        success: function(response) {
                            _this.setCityHtml(response);
                        }
                    });
                }

                _this.setDefaultRestaurant();
            });

            $("#city-ctr").delegate("#city", "change", function() {
                var cityId = $(this).val();

                if ('' == cityId) {
                    _this.setDefaultRestaurant();

                } else {

                    $.ajax({
                        type: 'get',
                        url: '/restaurants/' + cityId,
                        success: function(response) {
                            _this.setRestaurantHtml(response);
                        }
                    });
                }
            });
        }
    };

})(this.jQuery);


