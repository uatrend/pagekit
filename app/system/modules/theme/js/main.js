import UIkit from 'uikit';
import {$, on, css, toNodes, isString, assign, html, remove} from 'uikit-util';
import Autocomplete from './components/autocomplete';
import Pagination from './components/pagination';
import HTMLEditor from './components/htmleditor';
import Menu from './menu';
import Sidebar from './sidebar';

Vue.ready(() => {

    var TopMenu = { object: Menu, target: '.tm-navbar-container' },
        SideMenu = { object: Sidebar, target: '.tm-sidebar-left' };

    Theme.$mount([TopMenu, SideMenu]);

    // main menu order
    on('#js-appnav', 'stop', function (e) {
        const data = {};

        toNodes(this.children).forEach((el, key) => {
            data[el.getAttribute('data-id')] = key;
        });

        Vue.http.post('admin/adminmenu', { order: data });
    });

    // show system messages
    UIkit.mixin({ data: { timeout: 1500 } }, 'notification');
    toNodes($('.pk-system-messages').children).forEach((message) => {
        const data = message.dataset;

        // remove success message faster
        if (data.status && data.status == 'success') {
            data.timeout = 1500;
        }

        UIkit.notification(html(message), data);
        remove(message);
    });


    // UIkit overrides
    UIkit.modal.alert = function (message, options) {
        options = assign({ bgClose: false, escClose: false, labels: UIkit.modal.labels }, options);

        return new Promise(
            (resolve => on(UIkit.modal.dialog((`${options.title ? `<div class="uk-modal-header"><h2>${options.title}</h2></div>` : ''}<div class="uk-modal-body">${isString(message) ? message : html(message)}</div> <div class="uk-modal-footer uk-text-right"> <button class="uk-button uk-button-primary uk-modal-close" autofocus>${options.labels.Ok}</button> </div> `), options).$el, 'hide', resolve)),
        );
    };

    UIkit.modal.confirm = function (message, options) {
        options = assign({ bgClose: false, escClose: true, labels: UIkit.modal.labels }, options);

        return new Promise(((resolve, reject) => {
            const confirm = UIkit.modal.dialog((` <form>${options.title ? `<div class="uk-modal-header"><h2>${options.title}</h2></div>` : ''} <div class="uk-modal-body">${isString(message) ? message : html(message)}</div> <div class="uk-modal-footer uk-text-right"> <button class="uk-button uk-button-text uk-margin-right uk-modal-close" type="button">${options.labels.cancel}</button> <button class="uk-button uk-button-primary" autofocus>${options.labels.ok}</button> </div> </form> `), options);

            let resolved = false;

            on(confirm.$el, 'submit', 'form', (e) => {
                e.preventDefault();
                resolve();
                resolved = true;
                confirm.hide();
            });
            on(confirm.$el, 'hide', () => {
                if (!resolved) {
                    reject();
                }
            });
        }));
    };

    UIkit.modal.prompt = function (message, value, onsubmit, options) {
        options = assign({ bgClose: false, escClose: true, labels: UIkit.modal.labels }, options);

        return new Promise(((resolve) => {
            const prompt = UIkit.modal.dialog((` <form class="uk-form-stacked">${options.title ? `<div class="uk-modal-header"><h2>${options.title}</h2></div>` : ''} <div class="uk-modal-body"> <label class="uk-form-label">${isString(message) ? message : html(message)}</label> <input class="uk-input" autofocus> </div> <div class="uk-modal-footer uk-text-right"> <button class="uk-button uk-button-text uk-margin-right uk-modal-close" type="button">${options.labels.cancel}</button> <button class="uk-button uk-button-primary">${options.labels.ok}</button> </div> </form> `), options);
            const input = $('input', prompt.$el);

            input.value = value;

            let resolved = false;

            on(prompt.$el, 'submit', 'form', (e) => {
                e.preventDefault();
                onsubmit(input.value);
                resolve(input.value);
                resolved = true;
                prompt.hide();
            });
            on(prompt.$el, 'hide', () => {
                if (!resolved) {
                    resolve(null);
                }
            });
        }));
    };

    UIkit.tooltip('.tm-toggle-sidebar button', { pos: 'right', delay: 200 });
});
