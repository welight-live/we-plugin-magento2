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

namespace UOL\welight\Controller\Notification;

use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;

/**
 * Class Checkout
 * @package UOL\welight\Controller\Payment
 */
class Response extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\CsrfAwareActionInterface
{

    /**
     * Response constructor.
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Update a order
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        try {
            $nofitication = new \UOL\welight\Model\NotificationMethod(
                $this->_objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface'),
                $this->_objectManager->create('\Magento\Sales\Api\OrderRepositoryInterface'),
                $this->_objectManager->create('\Magento\Sales\Api\Data\OrderStatusHistoryInterface'),
                $this->_objectManager->create('Magento\Framework\Module\ModuleList'),
                $this->_objectManager->create('\Magento\Framework\Model\ResourceModel\Db\Context')
            );
            $nofitication->init();
        } catch (\Exception $ex) {
            //log already written in your welight log file if welight log is enabled in admin
            exit;
        }
    }

    /**
     * Create exception in case CSRF validation failed.
     * Return null if default exception will suffice.
     *
     * @param RequestInterface $request
     *
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        return null;
    }

    /**
     * Perform custom request validation.
     * Return null if default validation is needed.
     *
     * @param RequestInterface $request
     *createCsrfValidationException
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ?bool {
        return true;
    }
}
