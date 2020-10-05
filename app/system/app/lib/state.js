export default function (Vue) {
    const State = function (key, value) {
        const vm = this;

        const current = (new RegExp(`${key}=([^&]*)&?`)).exec(location.search);

        if (!value && current) {
            vm.$set(vm, key, current[1]);
        }

        if (value !== undefined) {
            history.replaceState({ key, value: this[key] }, '', modifyQuery(location.search, key, value));
        }

        this.$watch(key, (val) => {
            history.pushState({ key, val }, '', modifyQuery(location.search, key, val));
        });

        window.onpopstate = function (event) {
            if (event.state && event.state.key === key) {
                vm.$set(vm, key, event.state.value);
            }
        };
    };

    Object.defineProperty(Vue.prototype, '$state', {

        get() {
            return State.bind(this);
        }

    });
}

function modifyQuery(query, key, value) {
    query = query.substr(1);
    query = query.replace(new RegExp(`${key}=[^&]*&?`), '');

    if (query.length && query[query.length - 1] !== '&') {
        query = `${query}&`;
    }

    return `?${query}${[key, value].join('=')}`;
}
