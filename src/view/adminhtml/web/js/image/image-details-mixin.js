define([], () => {
    'use strict';

    return function(Image) { // eslint-disable-line prefer-arrow/prefer-arrow-functions
        return Image.extend({
            defaults: {
                template: 'Team23_WysiwygDownloads/image/image-details',
            },

            initialize: function() {
                this._super();

                return this;
            },
        });
    };
});
