/**
 * Wantrix - Meta WhatsApp Embedded Signup
 */

window.metaSignup = {

    authorizationCode: null,
    pendingSession: null,

    /**
     * Open Meta WhatsApp Embedded Signup
     */
    open() {

        if (typeof FB === 'undefined') {

            console.error(
                'Facebook SDK is not loaded.'
            );

            alert(
                'Facebook SDK is not loaded.'
            );

            return;
        }

        console.log(
            'Opening Meta WhatsApp Embedded Signup...'
        );

        console.log(
            'Meta configuration:',
            {
                appId: window.metaConfig?.appId,
                configId: window.metaConfig?.configId
            }
        );

        /**
         * IMPORTANT:
         *
         * This configuration intentionally matches
         * the standalone test that you confirmed works.
         */
        FB.login(

            function (response) {

                console.log(
                    'FB Login Response:',
                    response
                );

                if (!response) {

                    console.error(
                        'No Facebook response received.'
                    );

                    return;
                }

                if (
                    response.status !== 'connected'
                ) {

                    console.log(
                        'Facebook login cancelled.'
                    );

                    return;
                }

                if (!response.authResponse) {

                    console.error(
                        'Facebook authResponse missing.',
                        response
                    );

                    return;
                }

                console.log(
                    'Facebook authResponse received.'
                );

                /**
                 * ----------------------------------------
                 * Authorization code
                 * ----------------------------------------
                 */

                let code = null;

                /**
                 * Newer Embedded Signup responses may
                 * place the code inside signedRequest.
                 */
                if (
                    response.authResponse.signedRequest
                ) {

                    try {

                        const signedRequest =
                            response.authResponse.signedRequest;

                        const parts =
                            signedRequest.split('.');

                        if (parts.length === 2) {

                            const payload =
                                parts[1];

                            const base64 =
                                payload
                                    .replace(/-/g, '+')
                                    .replace(/_/g, '/');

                            /**
                             * Add missing Base64 padding.
                             */
                            const paddedBase64 =
                                base64.padEnd(
                                    base64.length +
                                    (4 - base64.length % 4) % 4,
                                    '='
                                );

                            const binary =
                                atob(paddedBase64);

                            const bytes =
                                Uint8Array.from(
                                    binary,
                                    c => c.charCodeAt(0)
                                );

                            const json =
                                new TextDecoder()
                                    .decode(bytes);

                            const signedData =
                                JSON.parse(json);

                            console.log(
                                'Meta signed request:',
                                {
                                    hasCode:
                                        !!signedData.code
                                }
                            );

                            if (signedData.code) {

                                code =
                                    signedData.code;
                            }

                        }

                    } catch (error) {

                        console.error(
                            'Unable to decode Meta signed request.',
                            error
                        );

                    }
                }

                /**
                 * Fallback:
                 *
                 * Some responses may provide the
                 * authorization code directly.
                 */
                if (
                    !code &&
                    response.authResponse.code
                ) {

                    code =
                        response.authResponse.code;
                }

                if (code) {

                    console.log(
                        'Meta authorization code received.'
                    );

                    window.metaSignup.authorizationCode =
                        code;

                    window.metaSignup.tryFinish();

                } else {

                    console.warn(
                        'Authorization code not found yet.'
                    );

                }

            },

            /**
             * IMPORTANT:
             *
             * Keep this identical to the
             * working standalone test.
             */
            {
                config_id:
                    window.metaConfig.configId,

                response_type: 'code',

                override_default_response_type: true,

                extras: {
                    setup: {},
                }
            }

        );

    },


    /**
     * ----------------------------------------
     * Try to finish the Embedded Signup flow
     * ----------------------------------------
     */
    tryFinish() {

        const code =
            window.metaSignup.authorizationCode;

        const session =
            window.metaSignup.pendingSession;

        if (!code) {

            console.log(
                'Waiting for authorization code...'
            );

            return;
        }

        if (!session) {

            console.log(
                'Waiting for Embedded Signup session information...'
            );

            return;
        }

        console.log(
            'Embedded Signup completed.',
            {
                event: session.event,
                wabaId:
                    session.data?.waba_id,
                phoneNumberId:
                    session.data?.phone_number_id,
                businessId:
                    session.data?.business_id
            }
        );

        Livewire.dispatch(
            'meta-code-received',
            {
                code: code,

                wabaId:
                    session.data?.waba_id ?? null,

                phoneNumberId:
                    session.data?.phone_number_id ?? null,

                businessId:
                    session.data?.business_id ?? null
            }
        );

        /**
         * Clear temporary state.
         */
        window.metaSignup.authorizationCode = null;

        window.metaSignup.pendingSession = null;
    }

};


/**
 * ==================================================
 * Meta Embedded Signup Session Listener
 * ==================================================
 */

window.addEventListener(
    'message',
    function (event) {

        /**
         * Meta can use different Facebook subdomains.
         *
         * Do NOT restrict this to only:
         *
         * https://www.facebook.com
         *
         */
        if (
            !event.origin.endsWith(
                'facebook.com'
            )
        ) {
            return;
        }

        let data;

        try {

            if (
                typeof event.data === 'string'
            ) {

                data =
                    JSON.parse(event.data);

            } else {

                data =
                    event.data;
            }

        } catch (error) {

            /**
             * Ignore normal OAuth messages.
             */
            return;
        }

        if (!data) {
            return;
        }

        if (
            data.type !==
            'WA_EMBEDDED_SIGNUP'
        ) {

            return;
        }

        console.log(
            'Meta Embedded Signup event:',
            data
        );

        /**
         * Successful completion.
         */
        if (
            data.event === 'FINISH' ||
            data.event === 'FINISH_ONLY_WABA'
        ) {

            window.metaSignup.pendingSession =
                data;

            window.metaSignup.tryFinish();

            return;
        }

        /**
         * User cancelled.
         */
        if (data.event === 'CANCEL') {

            console.warn(
                'Meta Embedded Signup cancelled:',
                data.data
            );

            return;
        }

        /**
         * Meta returned an error.
         */
        if (data.event === 'ERROR') {

            console.error(
                'Meta Embedded Signup error:',
                data.data
            );

            return;
        }

    }
);


/**
 * ==================================================
 * Facebook SDK Initialization
 * ==================================================
 */

window.fbAsyncInit = function () {

    console.log(
        'Facebook SDK loaded'
    );

    FB.init({

        appId:
            window.metaConfig.appId,

        autoLogAppEvents:
            true,

        xfbml:
            true,

        version:
            'v26.0'

    });

    console.log(
        'Facebook SDK Initialized'
    );

    console.log(
        'Facebook App ID:',
        window.metaConfig.appId
    );

    console.log(
        'Meta Config ID:',
        window.metaConfig.configId
    );

    document.dispatchEvent(
        new CustomEvent(
            'facebook-sdk-ready'
        )
    );

};


/**
 * ==================================================
 * Load Facebook SDK
 * ==================================================
 */

(function (d, s, id) {

    let js;

    const fjs =
        d.getElementsByTagName(s)[0];

    if (
        d.getElementById(id)
    ) {

        return;
    }

    js =
        d.createElement(s);

    js.id =
        id;

    js.src =
        'https://connect.facebook.net/en_US/sdk.js';

    fjs.parentNode.insertBefore(
        js,
        fjs
    );

})(
    document,
    'script',
    'facebook-jssdk'
);


/**
 * ==================================================
 * Livewire event
 * ==================================================
 */

document.addEventListener(
    'livewire:init',
    function () {

        Livewire.on(
            'open-meta-signup',
            function () {

                window.metaSignup.open();

            }
        );

    }
);