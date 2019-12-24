$app
    .use('$fetch', () => {
        const headers = new Headers({
            Accept: '*/*',
            'Access-Control-Allow-Methods': 'GET, POST, OPTIONS, PUT, PATCH, DELETE',
            'Access-Control-Allow-Headers': 'origin,X-Requested-With,content-type,accept',
            'Access-Control-Allow-Credentials': 'true'

        });

        return (method, url, body) => {
            return fetch(url, { method, headers, body: $object2FormData(new FormData, body) })
                .then(response => response.json());
        }
    })
    .run(({$fetch}) => {
        console.log($fetch);
    });