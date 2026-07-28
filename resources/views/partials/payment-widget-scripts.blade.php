{{-- Paiement FeexPay via le SDK JavaScript V2. --}}
<div id="render"></div>
<script src="https://api-v2.feexpay.me/feexpay-javascript-sdk/index.js"></script>
<script>
(function () {
    const FEEXPAY_KEY = @json(config('services.feexpay.api_key'));
    const FEEXPAY_SHOP_ID = @json(config('services.feexpay.shop_id'));
    const FEEXPAY_MODE = @json(config('services.feexpay.mode', config('services.feexpay.sandbox') ? 'SANDBOX' : 'LIVE'));
    const LOG_PREFIX = '[ADF FeexPay]';

    function log(message, data) {
        if (data === undefined) {
            console.log(LOG_PREFIX, message);
            return;
        }
        console.log(LOG_PREFIX, message, data);
    }

    function callbackUrl(paymentData) {
        const url = new URL('/payment/feexpay/callback', window.location.origin);
        url.searchParams.set('local_reference', paymentData.reference || '');
        url.searchParams.set('type', paymentData.callback_info?.type || '');
        if (paymentData.registration_id) url.searchParams.set('registration_id', paymentData.registration_id);
        if (paymentData.order_id) url.searchParams.set('order_id', paymentData.order_id);
        return url.toString();
    }

    function notifySuccess(paymentData, result) {
        log('callback SDK recu', result);
        const status = String(result?.status || result?.responsecode || result?.responsemsg || 'SUCCESSFUL').toUpperCase();
        Livewire.dispatch('paymentSuccess', [{
            transactionId: result?.reference || result?.id || result?.transaction_id || null,
            reference: paymentData.reference,
            status: status,
            provider: 'feexpay',
            registration_id: paymentData.registration_id || null,
            order_id: paymentData.order_id || null,
            callback_info: paymentData.callback_info || null,
            raw: result || null,
        }]);
    }

    function resetRenderContainer() {
        let container = document.getElementById('render');
        if (!container) {
            container = document.createElement('div');
            container.id = 'render';
            document.body.appendChild(container);
            log('conteneur #render cree');
        }

        container.innerHTML = '';

        let button = document.getElementById('btn-payer-feexpay');
        if (!button) {
            button = document.createElement('button');
            button.type = 'button';
            button.id = 'btn-payer-feexpay';
            button.textContent = 'Payer';
            document.body.appendChild(button);
            log('bouton custom #btn-payer-feexpay cree hors #render');
        }
        button.style.position = 'fixed';
        button.style.left = '-10000px';
        button.style.top = '0';

        return container;
    }

    function openFeexpay(paymentData) {
        log('demande ouverture widget', paymentData);

        const amount = parseInt(paymentData.amount, 10);
        if (Number.isNaN(amount) || amount <= 0) {
            throw new Error('invalid_amount');
        }

        log('etat SDK/config', {
            hasFeexPayButton: typeof window.FeexPayButton !== 'undefined',
            hasShopId: Boolean(FEEXPAY_SHOP_ID),
            hasToken: Boolean(FEEXPAY_KEY),
            mode: FEEXPAY_MODE,
        });

        if (typeof window.FeexPayButton === 'undefined') {
            throw new Error('feexpay_sdk_not_loaded');
        }
        if (!FEEXPAY_KEY || !FEEXPAY_SHOP_ID) {
            throw new Error('feexpay_config_missing');
        }

        const container = resetRenderContainer();
        const options = {
            id: FEEXPAY_SHOP_ID,
            amount: amount,
            token: FEEXPAY_KEY,
            callback: function (response) {
                notifySuccess(paymentData, response || {});
            },
            callback_url: callbackUrl(paymentData),
            error_callback_url: new URL('/payment/cancel', window.location.origin).toString(),
            mode: FEEXPAY_MODE,
            custom_button: true,
            id_custom_button: 'btn-payer-feexpay',
            custom_id: paymentData.reference,
            description: (paymentData.formation || 'Paiement ADF') + ' ' + paymentData.reference,
            case: '',
            fields_to_hide: ['email', 'name'],
            callback_info: paymentData.callback_info || { reference: paymentData.reference },
        };

        log('init FeexPayButton.init("render", options)', {
            id: options.id,
            amount: options.amount,
            mode: options.mode,
            custom_id: options.custom_id,
            callback_url: options.callback_url,
            error_callback_url: options.error_callback_url,
            renderChildren: container.children.length,
        });

        window.FeexPayButton.init('render', options);
        log('init terminee, clic bouton custom dans 250ms');

        window.setTimeout(function () {
            try {
                const button = document.getElementById('btn-payer-feexpay');
            log('bouton custom trouve avant clic', {
                exists: Boolean(button),
                html: button ? button.outerHTML : null,
                renderHtml: document.getElementById('render')?.innerHTML || null,
            });
                if (!button) {
                    throw new Error('feexpay_custom_button_missing');
                }
                button.click();
                log('clic bouton custom envoye');
            } catch (error) {
                console.error(LOG_PREFIX, 'erreur clic bouton custom', error);
                try {
                    Livewire.dispatch('paymentWidgetFailed', [{ reason: error.message || 'click_failed' }]);
                } catch (e) {}
            }
        }, 250);
    }

    document.addEventListener('livewire:init', function () {
        log('listener Livewire pret');
        Livewire.on('openPaymentWidget', function (data) {
            const paymentData = Array.isArray(data) ? data[0] : data;
            try {
                openFeexpay(paymentData || {});
            } catch (error) {
                console.error(LOG_PREFIX, 'erreur ouverture widget', error);
                alert('Le paiement FeexPay est temporairement indisponible. Veuillez réessayer dans quelques instants.');
                try {
                    Livewire.dispatch('paymentWidgetFailed', [{ reason: error.message || 'unavailable' }]);
                } catch (e) {}
            }
        });
    });
})();
</script>
