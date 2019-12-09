class FormView {
    constructor(selector) {
        this._map = new Map();
        this._set = new Set();
        this.form = document.querySelector(selector);
        console.log(this.form, selector)
        window[selector] = this;
    }

    service(id, callable) {
        const object  = Object.fromEntries(this._map),
              service = callable.apply(null, [object]);

        this._map.set(id, service);

        return this;
    }

    controller(callable) {
        this._set.add(callable.apply(null, [this.form, Object.fromEntries(this._map)]));

        return this;
    }

    run(data) {
        console.log(data);
        this._set.forEach(callable => {
            callable.apply(null, [data]);
        });
    }
}

class UIElement {
    static visible(target, state) {
        target.style.display = state !== true ? 'none' : '';

        return this;
    }

    static bind(tagName, attributes, parent) {
        if (parent === undefined) {
            parent = document;
        }

        if (typeof attributes === 'string') {
            attributes = [attributes];
        }

        const target = parent.querySelector(`${tagName}[${attributes.join('][')}]`);

        return new this(target);
    }

    constructor(target) {
        this._target = target;
    }

    get _value() {
        return this._target.value;
    }

    set _value(value) {
        this._target.value = value;
    }

    value(value) {
        const {_target} = this;

        if (value === undefined) {
            return this._value;
        }

        this._value = value;
        _target.dispatchEvent(new CustomEvent('change', {target: _target}));

        return this;
    }

    on(event, handler) {
        this._target.addEventListener(event, handler);

        return this;
    }

    onChange(handler) {
        this.on('change', handler);

        return this;
    }

    visible(value) {
        this._target.style.display = value !== true ? 'none' : '';

        return this;
    }

    focus() {
        this._target.focus();
    }
}

class UIInput extends UIElement {
    static bind(...args) {
        return super.bind('input', ...args);
    }

    constructor(target) {
        super(target)
    }
}

class UISelect extends UIElement {
    static bind(...args) {
        return super.bind('select', ...args);
    }

    static createOption(value, text) {
        const option = document.createElement('option');
        option.value = value;
        option.text = text;

        return option;
    }

    constructor(target, value) {
        super(target);
        this.value(value !== undefined ? value : target.value);
    }

    addOption(value, text) {
        const option = UISelect.createOption(value, text);
        this._target.appendChild(option);

        return this;
    }

    clearOptions() {
        this._target.options.length = 0;

        return this;
    }

    reset(data, adapter, otherwise) {
        this
            .clearOptions()
            .addOption(0, '\u2014');

        if (data !== undefined) {
            data.map(object => {
                this.addOption.apply(this, adapter.call(undefined, object));
            });
        }

        if (otherwise !== undefined) {
            this.addOption(-1, otherwise);
        }

        return this;
    }
}

class UIRadioInput extends UIElement {
    static bind(attrs, parent) {
        if (parent === undefined) {
            parent = document;
        }

        if (typeof attrs === 'string') {
            attrs = [attrs];
        }

        const target = parent.querySelectorAll(`input[type="radio"][${attrs.join('][')}]`);

        return new this(target);
    }

    constructor(target) {
        super(target);
        this._valueMap = new Map();

        target.forEach(radio => {
            this._valueMap.set(radio.value, radio);
        });
    }

    on(event, handler) {
        this._target.forEach(t => {
            t.addEventListener(event, handler);
        });

        return this;
    }

    value(value) {
        const radio = this._valueMap.get(value.toString());
        radio.click();
        radio.dispatchEvent(new CustomEvent('change', {target: {value}}));
    }
}

(function () {

    const view = new FormView('form[name="user_agency"]');

    view
        .service('$fetch', () => {
            const headers = new Headers({
                Accept: '*/*',
                // 'Authorization': "Bearer "+(JSON.parse(sessionStorage.getItem('token')).token),
                // 'Access-Control-Allow-Origin': this.apiURL,
                'Access-Control-Allow-Methods': 'GET, POST, OPTIONS, PUT, PATCH, DELETE',
                'Access-Control-Allow-Headers': 'origin,X-Requested-With,content-type,accept',
                'Access-Control-Allow-Credentials': 'true'

            });

            return (method, url, body) => {
                return fetch(url, {method, headers, body})
                    .then(response => response.json());
            }
        })
        .service('$object2FormData', () => {
            const object2FormData = (formData, data, parentKey) => {
                if (data && typeof data === 'object' && !(data instanceof Date)) {
                    Object.keys(data).forEach(key => {
                        object2FormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
                    });
                } else {
                    const value = data == null ? '' : data;

                    formData.append(parentKey, value);
                }

                return formData;
            };

            return object2FormData;
        })
        .service('$api', ({$fetch, $object2FormData}) => {
            return {
                agencies: (city, country) => {
                    if (country === undefined) {
                        country = 9908;
                    }
                    return $fetch('POST', '/api/agencies', $object2FormData(new FormData(), {
                        find_by: {country, city}
                    }));
                },
                citiesByCounty: (country) => {
                    return $fetch('POST', `/api/geo/countries/${country}/cities`, $object2FormData(new FormData(), {}));
                },
                submitForm: (form) => {
                    return $fetch(form.method, form.action, new FormData(form));
                }
            }
        })
        .controller((f, {$api}) => {
            const citySelect = UISelect.bind('name="uua[city]"', f),
                  idSelect   = UISelect.bind('name="uua[id]"', f),
                  nameInput  = UIInput.bind('name="uua[name]"', f),
                  idDiv      = idSelect._target.closest('div.form-row');

            const onCitySelectChange = (data) => {
                let v = 0;

                if (idSelect._target.hasAttribute('data-val')) {
                    v = idSelect._target.getAttribute('data-val');
                    idSelect._target.removeAttribute('data-val');
                }

                idSelect
                    .reset(data, ({id, name}) => [id, name], 'Другое')
                    .value(v);
            };

            citySelect.onChange(({target: {value}}) => {
                const {visible} = UIElement;
                value = parseInt(value);

                if (value > 0) {
                    visible(idDiv, true);
                    $api
                        .agencies(value)
                        .then(onCitySelectChange)
                } else {
                    onCitySelectChange();
                    visible(idDiv, false);
                }

            });

            idSelect.onChange(({target: {value}}) => {
                value = parseInt(value);

                if (value === -1) {
                    idSelect.visible(false);
                    nameInput
                        .visible(true)
                        .value('')
                        .focus();
                } else {
                    idSelect.visible(true);
                    nameInput.visible(false)
                }
            });

            nameInput.on('blur', ({target: {value}}) => {
                if (value === '') {
                    nameInput.visible(false);
                    idSelect
                        .visible(true)
                        .value(0);
                }
            });

            return ({city, id, name}) => {
                citySelect.value(city);
                idSelect._target.setAttribute('data-val', id);
                nameInput._target.setAttribute('data-val', name);
            }
        })
        .controller((f) => {
            const contractRadio     = UIRadioInput.bind('name="uua[contract]"', f),
                  contractTypeRadio = UIRadioInput.bind('name="uua[contract_type]"', f),
                  contractTypeDiv   = contractTypeRadio._target[0].closest('div.form-row');

            contractRadio.on('change', ({target: {value}}) => {
                let ctv = 0;
                if (contractTypeDiv.hasAttribute('data-val')) {
                    ctv = parseInt(contractTypeDiv.getAttribute('data-val'));
                    contractTypeDiv.removeAttribute('data-val');
                }
                UIElement.visible(contractTypeDiv, parseInt(value) === 1);
                contractTypeRadio.value(ctv);
            });

            return ({contract, contract_type}) => {
                contractTypeDiv.setAttribute('data-val', contract_type);
                contractRadio.value(contract);
            }
        })
        .controller((f, {$api}) => {
            const addBtn   = f.querySelector('input[type="button"]#add_foreign_agency'),
                  template = f.querySelector('template#foreign_agency'),
                  nodes    = new Set(),
                  intro    = new Map();

            let idx = 0;
            addBtn.addEventListener('click', () => {
                const node    = document.importNode(template.content, true),
                      close   = node.querySelector('button.close'),
                      country = UISelect.bind('name="ufa[0][country]"', node),
                      city    = UISelect.bind('name="ufa[0][city]"', node),
                      cityDiv = city._target.closest('div.form-row'),
                      id      = UISelect.bind('name="ufa[0][id]"', node),
                      idDiv   = id._target.closest('div.form-row'),
                      name    = UIInput.bind('name="ufa[0][name]"', node);

                const handleCountryChange = (data) => {
                    city
                        .reset(data, ({id, name}) => [id, name])
                        .value(0);
                };

                const handleCityChange = (data) => {
                    id
                        .reset(data, ({id, name}) => [id, name], 'Другое')
                        .value(0);
                };

                close.addEventListener('click', ({target}) => {
                    target.closest('div[rel="section"]').remove();
                });

                if (nodes.size === 0) {
                    close.remove();
                }

                country._target.name = `ufa[${idx}][country]`;
                country.on('change', ({target: {value}}) => {
                    value = parseInt(value);

                    if (value > 0) {
                        UIElement.visible(cityDiv, true);
                        $api
                            .citiesByCounty(value)
                            .then(handleCountryChange);
                    } else {
                        handleCountryChange();
                        UIElement.visible(cityDiv, false);
                    }
                });

                city._target.name = `ufa[${idx}][city]`;
                city.on('change', ({target: {value}}) => {
                    value = parseInt(value);

                    if (value > 0) {
                        UIElement.visible(idDiv, true);
                        $api
                            .agencies(value, country.value())
                            .then(handleCityChange);
                    } else {
                        handleCityChange();
                        UIElement.visible(idDiv, false);
                    }
                });

                id._target.name = `ufa[${idx}][id]`;
                id.on('change', ({target: {value}}) => {
                    value = parseInt(value);

                    if (value === -1) {
                        id.visible(false);
                        name
                            .visible(true)
                            .value('')
                            .focus();
                    } else {
                        id.visible(true);
                        name.visible(false)
                    }
                });

                name._target.name = `ufa[${idx}][name]`;
                name.on('blur', ({target: {value}}) => {
                    if (value === '') {
                        name.visible(false);
                        id
                            .visible(true)
                            .value(0);
                    }
                });

                (() => {
                    country.value(0);
                })();

                node.querySelector('input[name="mother_agency[]"]').value = idx;

                nodes.add({idx, node});
                template.before(node);
                idx++;
            });

            return ({ufa}) => {
                ufa.forEach((agency) => {
                    addBtn.dispatchEvent(new Event('click'));
                });
            }
        })
        .controller((form, {$api}) => {
            const textSuccess = form.querySelector('span.text-success');

            form.setAttribute('onsubmit', 'return false;');
            form.addEventListener('submit', ({target}) => {
                $api
                    .submitForm(target)
                    .then(d => {
                        console.table(d);
                        UIElement.visible(textSuccess, true);
                        setTimeout(() => UIElement.visible(textSuccess, false), 2000);
                    });
            });

            UIElement.visible(textSuccess, false);

            return () => {

            };
        });


    // register('profile_edit_agency', {$agency, $foreignAgency}, function (...deps) {
    //     const form = document.querySelector('form[name="profile_edit_agency"]');

    //
    //     deps.map(d => {
    //         d.apply(this, [form]);
    //     });
    //
    //     return () => {
    //
    //     };
    // });
    //
    //
    // function $agency(form) {
    //     const city            = form.querySelector('select[name="agency[city]"]'),
    //           id              = form.querySelector('select[name="agency[id]"]'),
    //           name            = form.querySelector('input[name="agency[name]"]'),
    //           contracts       = form.querySelectorAll('input[type="radio"][name="agency[contract]"]'),
    //           contractTypes   = form.querySelectorAll('input[name="agency[contract_type]"]'),
    //           contractTypeDiv = form.querySelector('input[name="agency[contract_type]"]').closest('div.form-row');
    //
    //     city.addEventListener('change', ({target: {value}}) => {
    //         toggleFormRow(id, value > 0);
    //         getAgencies(value);
    //         id.dispatchEvent(new Event('change'));
    //     });
    //
    //     id.addEventListener('change', ({target: {value}}) => {
    //         value = parseInt(value);
    //         toggleElement(id, value > -1);
    //         toggleFormRow(name, value === -1, 'label');
    //         value === -1 && name.focus();
    //     });
    //
    //     name.addEventListener('blur', ({target: {value}}) => {
    //         if (value === '') {
    //             name.parentElement.style.display = 'none';
    //             id.style.display = '';
    //             id.focus();
    //         }
    //     });
    //
    //     contracts.forEach((radio) => {
    //         radio.addEventListener('change', ({target: {value}}) => {
    //             if (parseInt(value) !== 1) {
    //                 contractTypeDiv.style.display = 'none';
    //             } else {
    //                 contractTypeDiv.style.display = '';
    //             }
    //         });
    //         if (parseInt(radio.value) === 0) {
    //             radio.checked = true;
    //             radio.dispatchEvent(new CustomEvent('change', {target: {value: 0}}));
    //         }
    //     });
    //
    //     city.dispatchEvent(new CustomEvent('change', {target: {value: 0}}));
    //
    //     contractTypes[0].checked = true;
    //     contractTypes[0].dispatchEvent(new Event('change'));
    //
    //     return {
    //         run() {
    //
    //         }
    //     }
    // }
    //
    // function $foreignAgency(form) {
    //     const $this    = this,
    //           template = form.querySelector('template#foreign_agency'),
    //           addBtn   = form.querySelector('input#add_foreign_agency');
    //
    //     addBtn.addEventListener('click', () => {
    //         this.add({});
    //     });
    //
    //     $this.add = ({enable_close_btn}) => {

    //     };
    //
    //     addBtn.dispatchEvent(new Event('click'));
    // }
    //
    // // const agencyRegion = form.querySelector('select#agencyRegion'),
    // //     agencyCity = form.querySelector('select#agencyCity'),
    // //     agency = form.querySelector('select#agency'),
    // //     agencyName = form.querySelector('input#agencyName'),
    // //     agencyContractType = form.querySelector('input[name="agency[contract_type]"]').closest('div.form-row'),
    // //     addForeignAgencyBtn = form.querySelector('input#addForeignAgency');
    // //
    // // const state = JSON.parse(form.querySelector('script[type="application/json"]').innerText);
    //
    //
    // // form
    // //     .querySelectorAll('input[type="radio"][name="agency[contract]"]')
    // //     .forEach(radio => radio.addEventListener('change', handleRadioChange));
    // //
    // // agencyRegion.addEventListener('change', ({target: {value}}) => {
    // //     value = parseInt(value);
    // //
    // //     if (value > 0) {
    // //         getCitiesByRegionId(value);
    // //     } else {
    // //         resetSelect(agencyCity);
    // //     }
    // //
    // //     changeAgencyCity(0);
    // //     toggleFormRow(agencyCity, value > 0);
    // // }, true);
    // //
    // // agencyCity.addEventListener('change', ({target: {value}}) => {
    // //     value = parseInt(value);
    // //
    // //     if (value > 0) {
    // //         getAgencies(value);
    // //     } else {
    // //         resetSelect(agency);
    // //     }
    // //
    // //     changeAgency(0);
    // //     toggleFormRow(agency, value > 0);
    // // }, true);
    //
    //
    // // // ---------------------------
    // // (() => {
    // //     changeAgencyRegion(agencyRegion.dataset.value);
    // //     renderForeignAgency();
    // // })();
    // //
    // // // ---------------------------
    // //
    // //
    // // function changeAgencyRegion(value) {
    // //     agencyRegion.value = value;
    // //     agencyRegion.dispatchEvent(new CustomEvent('change', {target: {value}}));
    // // }
    // //
    // // function changeAgencyCity(value) {
    // //     agencyCity.value = value;
    // //     agencyCity.dispatchEvent(new CustomEvent('change', {target: {value}}));
    // // }
    // //
    // // function changeAgency(value) {
    // //     agency.value = value;
    // //     agency.dispatchEvent(new CustomEvent('change', {target: {value}}));
    // // }
    //
    // function toggleFormRow(target, state, selector) {
    //     toggleElement(target.closest(selector !== undefined ? selector : 'div.form-row'), state)
    // }
    //
    // function toggleElement(element, state) {
    //     element.style.display = !state ? 'none' : '';
    // }
    //
    // // function getCitiesByRegionId(region_id) {
    // //     sendRequest('POST', '/geo', {
    // //         act: 'get_cities',
    // //         region_id
    // //     }).then(({cities}) => {
    // //         resetSelect(agencyCity, cities, ({city_id, name}) => [city_id, name]);
    // //     });
    // // }

})();