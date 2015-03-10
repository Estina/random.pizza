var App = App || {};

(function($) {

    App.Result = {

        init: function() {
            var d = new Date(parseInt($('#date-utc').val()) * 1000);
            $('#date-created').text(d.toLocaleString());
        }
    };

})(this.jQuery);


