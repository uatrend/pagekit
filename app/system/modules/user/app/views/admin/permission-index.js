import Permissions from '../../lib/permissions';

Vue.ready(_.merge(Permissions, { name: 'user-permissions', el: '#permissions', mixins: [Theme.Mixins.Helper], theme: { hideEls: ['#permissions > h2'] } }));
