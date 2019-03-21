Vue.ready(() => {
    UIkit.util.findAll('time').forEach((time) => {
        new Vue({}).$mount(time);
    });
});
