const $app = new function () {
    const providerMap = new Map(),
          serviceMap  = new Map();

    Object.assign(this, {
        use,
        run
    });

    function use(name, provider) {
        providerMap.set(name, provider);

        return this;
    }

    function run(callback) {
        callback.apply(null, [Object.fromEntries(providerMap.entries())]);
    }
};