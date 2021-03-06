const config = window.$captcha;
let requestResolve;
let requestReject;

if (config.grecaptcha) {
    Vue.asset({ js: ['https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit'] });

    let resolveLoad;
    const loadPromise = new Vue.Promise(((resolve) => {
        resolveLoad = resolve;
    }));
    window.onRecaptchaLoad = function () {
        const div = document.createElement('div');

        document.body.appendChild(div);

        grecaptcha.render(div, {
            sitekey: config.grecaptcha,
            callback: onSubmit,
            'expired-callback': onExpire,
            'error-callback': onError,
            size: 'invisible'
        });
        resolveLoad();
    };

    Vue.http.interceptors.push((request, response) => {
        if (!config.routes || request.method.toLowerCase() !== 'post' || !config.routes.some((route) => {
            const exp = new RegExp(route.replace(/{.+?}/, '.+?'));
            return exp.test(request.url);
        })) {
            return response; // TODO
        }

        if (!request.body.gRecaptchaResponse) {
            return new Vue.Promise(
                (resolve, reject) => {
                    requestResolve = function (gRecaptchaResponse) {
                        let body;
                        grecaptcha.reset();
                        try {
                            body = JSON.parse(request.getBody());
                        } catch (e) {
                            body = request.getBody();
                        }
                        body.gRecaptchaResponse = gRecaptchaResponse;
                        request.body = body;
                        resolve(Vue.http(request));
                    };
                    requestReject = (error) => reject({ data: error });

                    loadPromise.then(() => grecaptcha.execute());
                }
            );
        }
    });
}

function onSubmit(gRecaptchaResponse) {
    requestResolve(gRecaptchaResponse);
}

function onExpire() {
    requestReject('reCAPTCHA expired. Please try again.'); // TODO: Translation
}

function onError() {
    requestReject('An error occured during reCAPTCHA execution. Please try again.'); // TODO: Translation
}
