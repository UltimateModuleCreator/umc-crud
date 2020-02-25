define(
    ['jquery', 'jquery/ui'],
    function ($) {
        'use strict';
        $.widget('crud.heartbeat', {
            options: {
                url: ''
            },
            _create: function () {
                var self = this;
                var running = false;
                if (this.options.url) {
                    setInterval(
                        function () {
                            if (!running) {
                                running = true;
                                $.get({
                                    url: self.options.url,
                                    complete: function () {
                                        running = false;
                                    }
                                });
                            }
                        },
                        60000
                    );
                }
            }
        });
        return $.crud.heartbeat;
    }
);
