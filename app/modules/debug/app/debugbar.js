import DebugBarInstance from './debugbar.vue';

import TimeComponent from './components/time.vue';
import SystemComponent from './components/system.vue';
import Events from './components/events.vue';
import Routes from './components/routes.vue';
import Memory from './components/memory.vue';
import Database from './components/database.vue';
import Auth from './components/auth.vue';
import Log from './components/log.vue';
import Profile from './components/profile.vue';

const Debugbar = Vue.extend(DebugBarInstance);

Debugbar.component('TimeComponent', TimeComponent);
Debugbar.component('System', SystemComponent);
Debugbar.component('Events', Events);
Debugbar.component('Routes', Routes);
Debugbar.component('Memory', Memory);
Debugbar.component('Database', Database);
// Debugbar.component('request', require('./components/request.vue'));
Debugbar.component('Auth', Auth);
Debugbar.component('Log', Log);
Debugbar.component('Profile', Profile);

Vue.ready(() => {
    const debugbar = new Debugbar().$mount();
    UIkit.util.append(UIkit.util.$('body'), debugbar.$el);
});

export default Debugbar;
