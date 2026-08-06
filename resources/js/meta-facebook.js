window.metaSignup = {

    open() {

        if (typeof FB === 'undefined') {
            alert('Facebook SDK not loaded.');
            return;
        }

        FB.login(function (response) {

            console.log('FB Login Response:', response);

            if (!response) {
                return;
            }

            if (response.status !== 'connected') {
                console.log('User cancelled login.');
                return;
            }

            // Embedded Signup returns an authorization code
            if (response.authResponse && response.authResponse.code) {

                Livewire.dispatch('meta-signup-completed', {
                    code: response.authResponse.code
                });

                return;
            }

            console.log('Authorization code not returned.', response);

        }, {

            config_id: window.metaConfig.configId,

            response_type: 'code',

            override_default_response_type: true,

            extras: {

                setup: {},

                feature: 'whatsapp_embedded_signup',

                sessionInfoVersion: 3

            }

        });

    }

};

window.fbAsyncInit = function () {

    FB.init({

        appId: window.metaConfig.appId,

        cookie: true,

        xfbml: false,

        version: 'v24.0',

        autoLogAppEvents: true

    });

    console.log('Facebook SDK Initializeds');

    document.dispatchEvent(
        new CustomEvent('facebook-sdk-ready')
    );

};

(function (d, s, id) {

    let js;
    const fjs = d.getElementsByTagName(s)[0];

    if (d.getElementById(id)) {
        return;
    }

    js = d.createElement(s);
    js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";

    fjs.parentNode.insertBefore(js, fjs);

}(document, 'script', 'facebook-jssdk'));

document.addEventListener('livewire:init', () => {

    Livewire.on('open-meta-signup', () => {

        metaSignup.open();

    });

});