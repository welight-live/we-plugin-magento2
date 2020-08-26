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

namespace UOL\welight\Helper;

/**
 * Class Library
 * @package UOL\welight\Helper
 */
class Library
{
    /**
     *
     */
    const STANDARD_JS = "https://stc.welight.uol.com.br/welight/api/v2/checkout/welight.lightbox.js";
    /**
     *
     */
    const SANDBOX_JS = "https://stc.sandbox.welight.uol.com.br/welight/api/v2/checkout/welight.lightbox.js";
    /**
     *
     */
    const DIRECT_PAYMENT_URL = "https://stc.welight.uol.com.br/welight/api/v2/checkout/welight.directpayment.js";
    /**
     *
     */
    const DIRECT_PAYMENT_URL_SANDBOX= "https://stc.sandbox.welight.uol.com.br/welight/api/v2/checkout/welight.directpayment.js";

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
	/**
     * @var \Magento\Framework\Module\ModuleList
     */
	protected $_moduleList;
    /**
     * Library constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
		\Magento\Framework\Module\ModuleList $moduleList
    ) {
		$this->_moduleList = $moduleList;
        $this->loader();
        $this->_scopeConfig = $scopeConfigInterface;
    }
    /**
     * Get the access credential
     * @return welightAccountCredentials
     */
    public function getwelightCredentials()
    {
        $email = $this->_scopeConfig->getValue('payment/welight/email');
        $token = $this->_scopeConfig->getValue('payment/welight/token');
        //Set the credentials
        \welight\Configuration\Configure::setAccountCredentials($email, $token);
        return \welight\Configuration\Configure::getAccountCredentials();
    }
    /**
     * @return bool
     */
    public function isLightboxCheckoutType()
    {
        if ($this->_scopeConfig->getValue('payment/welight_default_lightbox/checkout')
            == \UOL\welight\Model\System\Config\Checkout::LIGHTBOX) {
            return true;
        }
        return false;
    }
    /**
     * Load library vendor
     */
    private function loader()
    {
        /** @var \Magento\Framework\App\ObjectManager $objectManager */
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productMetadata = $objectManager->get('Magento\Framework\App\ProductMetadataInterface');
        $timezone = $objectManager->create('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
        /** @var \Magento\Framework\App\ProductMetadataInterface $productMetadata */

        //set the store timezone to the script
        date_default_timezone_set($timezone->getConfigTimezone());
        \welight\Library::initialize();
		\welight\Library::cmsVersion()->setName("Magento")->setRelease($productMetadata->getVersion());
        \welight\Library::moduleVersion()->setName($this->_moduleList->getOne('WE_welight')['name'])
			->setRelease($this->_moduleList->getOne('WE_welight')['setup_version']);
    }

    /**
     * Set the environment configured in the welight module
     */
    public function setEnvironment()
    {
        \welight\Configuration\Configure::setEnvironment(
            $this->_scopeConfig->getValue('payment/welight/environment')
        );
    }
    /**
     * Set the environment configured in the welight module
     */
    public function getEnvironment()
    {
       return $this->_scopeConfig->getValue('payment/welight/environment');
    }

    /**
     * Set the charset configured in the welight module
     */
    public function setCharset()
    {
        \welight\Configuration\Configure::setCharset(
            $this->_scopeConfig->getValue('payment/welight/charset')
        );
    }

    /**
     * Set the log and log location configured in the welight module
     */
    public function setLog()
    {
        \welight\Configuration\Configure::setLog(
            $this->_scopeConfig->getValue('payment/welight/log'),
            $this->_scopeConfig->getValue('payment/welight/log_file')
        );
    }


    /**
     * Get session
     *
     * @return mixed
     * @throws \Exception
     */
    public function getSession()
    {
        try {
            $session = \welight\Services\Session::create(
                $this->getwelightCredentials()
            );
            return $session->getResult();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Get direct payment url
     *
     * @return string
     */
    public function getDirectPaymentUrl()
    {
        if ($this->getEnvironment() == 'sandbox') {
            return Library::DIRECT_PAYMENT_URL_SANDBOX;
        } else {
            return Library::DIRECT_PAYMENT_URL;
        }
    }

    /**
     * Get image full frontend url
     * @return type
     */
    public function getImageUrl($imageModulePath)
    {
        /** @var \Magento\Framework\App\ObjectManager $om */
	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	/** @var \Magento\Framework\View\Asset\Repository */
	$viewRepository = $objectManager->get('\Magento\Framework\View\Asset\Repository');
	return $viewRepository->getUrl($imageModulePath);
    }
}
