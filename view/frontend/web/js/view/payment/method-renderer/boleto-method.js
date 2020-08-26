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
        'jquery',
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/action/set-payment-information',
        'Magento_Checkout/js/action/place-order',
        'WE_welight/js/model/direct-payment-validator',
        window.checkoutConfig.library.directPaymentJs
    ],
    function ($, Component, quote, fullScreenLoader, setPaymentInformationAction, placeOrder, directPaymentValidator) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'WE_welight/payment/boleto-form',
                brazilFlagPath: window.checkoutConfig.brazilFlagPath
            },

            initObservable: function () {

                this._super()
                    .observe([
                        'boletoDocument'
                    ]);
                return this;
            },
            
            context: function() {
                return this;
            },

            getCode: function() {
                return "welight_boleto"
            },
            
             getData: function() {
                return {
                    'method': this.item.method,
                    'additional_data': {
                        'boleto_document': this.boletoDocument()
                    }
                };
            },

            doDocumentMask: function(data, event) {
              //directPaymentValidator.documentValidator(document.getElementById('welight_boleto_boleto_document'));
              //value.length
              documentMask(document.getElementById('welight_boleto_boleto_document'));
              //console.log(event.keyCode);
              //console.log(directPaymentValidator.documentValidator());
              return true;
            },

            /**
             * @override
             */
            placeOrder: function () {
                var self = this;
                var paymentData = quote.paymentMethod();
                var messageContainer = this.messageContainer;
                welightDirectPayment.setSessionId(window.checkoutConfig.library.session);
                fullScreenLoader.startLoader();
                this.isPlaceOrderActionAllowed(false);
                if (! self.validatePlaceOrder()) {
                  fullScreenLoader.stopLoader();
                  this.isPlaceOrderActionAllowed(true);
                  return;
                }

                $.when(setPaymentInformationAction(this.messageContainer, {
                    'method': self.getCode(),
                    'additional_data': {
                        'boleto_document': (self.boletoDocument()) ? self.boletoDocument() : document.getElementById('welight_boleto_boleto_document').value,
                        'boleto_hash': welightDirectPayment.getSenderHash()
                    }
                })).done(function () {
                        delete paymentData['title'];                        
                        $.when(placeOrder(paymentData, messageContainer)).done(function () {
                          $.mage.redirect(window.checkoutConfig.welight_boleto);
                        });
                }).fail(function () {
                    self.isPlaceOrderActionAllowed(true);
                }).always(function(){
                    fullScreenLoader.stopLoader();
                });
            },
            
            validatePlaceOrder: function() {
              return validateDocumentFinal(document.getElementById('welight_boleto_boleto_document'));
            }
        });
    }
);
