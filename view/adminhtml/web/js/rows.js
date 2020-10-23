define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/dynamic-rows/dynamic-rows'
], function (_, uiRegistry, rows) {
    'use strict';

    return rows.extend({
        defaults: {
            dndConfig: {
                enabled:false
            }
        },

        /**
         * Init
         */
        initialize: function () {
            this._super();
            return this;
        },
    });
});
