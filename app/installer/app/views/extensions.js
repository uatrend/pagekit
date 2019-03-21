window.Extensions = _.merge(require('../components/package-manager'), { name: 'extensions', el: '#extensions' });

Vue.ready(window.Extensions);
