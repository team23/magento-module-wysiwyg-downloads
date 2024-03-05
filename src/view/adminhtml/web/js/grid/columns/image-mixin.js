define([], () => {
    'use strict';

    return function(Image) { // eslint-disable-line prefer-arrow/prefer-arrow-functions
        return Image.extend({
            defaults: {
                bodyTmpl: 'Team23_WysiwygDownloads/grid/columns/image',
            },

            initialize: function() {
                this._super();

                return this;
            },
        });
    };
});
