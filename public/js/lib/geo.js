const Geo = (function () {
    const ui = {
        country: null,
        region: null,
        city: null
    };

    return {
        init: init
    };

    /**
     * @param {Object} options
     */
    function init(options) {
        for (const key in options) {
            console.log(key);
        }
    }
})();
