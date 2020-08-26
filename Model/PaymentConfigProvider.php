<?php
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

namespace UOL\welight\Model;

use Magento\Payment\Helper\Data as PaymentHelper;

/**
 * Class PaymentConfigProvider
 * @package UOL\welight\Model
 */
class PaymentConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{

    /**
     * Get payment method code for welight from Payment model.
     */
    const PAYMENT_METHOD_welight_CODE = 'welight_default_lightbox';//\UOL\welight\Model\Payment::PAYMENT_METHOD_welight_CODE;
    
    const PAYMENT_METHOD_welight_BOLETO_CODE = 'welight_boleto';
    
    const PAYMENT_METHOD_welight_ONLINE_DEBIT_CODE = 'welight_oline_debit';
    

    /**
     * @var
     */
    private $method;
    
    private $boletoMethod;

    /**
     * PaymentConfigProvider constructor.
     * @param PaymentHelper $helper
     */
    public function __construct(PaymentHelper $helper)
    {
        $this->method = $helper->getMethodInstance(self::PAYMENT_METHOD_welight_CODE);
        $this->boletoMethod = $helper->getMethodInstance(self::PAYMENT_METHOD_welight_BOLETO_CODE);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_library = $objectManager->create('\UOL\welight\Helper\Library');
    }

    /**
     * Get payment config
     *
     * @return array
     */
    public function getConfig()
    {
        $this->_library->setEnvironment();
        
        $config = [
            'library' => [
                'session' => $this->_library->getSession(),
                'directPaymentJs' => $this->_library->getDirectPaymentUrl()
            ],
            'brazilFlagPath' => $this->_library->getImageUrl('WE_welight::images/flag-origin-country.png'),
            'payment' => [
                'welight' => [
                    'isDirect'   => $this->method->isDirectCheckout(),
                    'isLightbox' => $this->method->isLightboxCheckoutType(),
                    'checkout'   => [
                        'lightbox' => $this->method->getLightboxCheckoutPaymentUrl(),
                        'standard' => $this->method->getStandardCheckoutPaymentUrl(),
                        'direct'   => $this->method->getDirectCheckoutPaymentUrl()
                    ]
                ]
            ],
            'welight_boleto' => $this->boletoMethod->getStandardCheckoutPaymentUrl(),
        ];
        return $config;
    }
}
