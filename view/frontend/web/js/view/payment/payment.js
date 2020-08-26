/**
 * 2007-2016 [welight Internet Ltda.]
 *
 * NOTICE OF LICENSE
 *
 *Licensed under the Apache License, Version 2.0 (the "License");
 *you may not use this file except in compliance with the License.
 *You may obtain a copy of the License at
 *
 *http://www.apache.org/licenses/LICENSE-2.0
 *
 *Unless required by applicable law or agreed to in writing, software
 *distributed under the License is distributed on an "AS IS" BASIS,
 *WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *See the License for the specific language governing permissions and
 *limitations under the License.
 *
 *  @author    welight Internet Ltda.
 *  @copyright 2016 welight Internet Ltda.
 *  @license   http://www.apache.org/licenses/LICENSE-2.0
 */
/*
 * browser:true
 * global define
 */
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'welight_default_lightbox',
                component: 'WE_welight/js/view/payment/method-renderer/default_lightbox-method'
            },
            {
                type: 'welight_credit_card',
                component: 'WE_welight/js/view/payment/method-renderer/credit_card-method'
            },
            {
                type: 'welight_boleto',
                component: 'WE_welight/js/view/payment/method-renderer/boleto-method'
            },
            {
                type: 'welight_online_debit',
                component: 'WE_welight/js/view/payment/method-renderer/online_debit-method'
            }
        );

        return Component.extend({});
    }
);
