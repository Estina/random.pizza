var App = App || {};

(function($) {

    App.Common = {

        init: function() {
            var controller = $('body').data('controller');
            if ('undefined' != typeof controller) {
                controller = controller.charAt(0).toUpperCase() + controller.slice(1);
                if (App[controller] && 'function' == typeof App[controller]['init']) {
                    App[controller]['init']();
                }
            }
        }
    };

    $(document).ready(App.Common.init);

})(this.jQuery);
