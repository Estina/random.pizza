var App = App || {};

(function($) {

    App.Home = {

        setDefaultPlace: function() {
            this.setPlaceHtml(
                '<select id="place" name="place" class="form-control" disabled>' +
                '<option value="">select</option>' +
                '</select>'
            );
        },

        setPlaceHtml: function(html) {
            $('#place-ctr').html(html);
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

                _this.setDefaultPlace();
            });

            $('#city').on('change', function(){

                var cityId = $(this).val();

                if ('' == cityId) {
                    _this.setDefaultPlace();

                } else {

                    $.ajax({
                        type: 'get',
                        url: '/places/' + cityId,
                        success: function(response) {
                            _this.setPlaceHtml(response);
                        }
                    });
                }

            });
        }
    };

})(this.jQuery);


