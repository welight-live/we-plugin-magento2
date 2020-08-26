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

namespace UOL\welight\Controller\Adminhtml\Abandoned;

use UOL\welight\Controller\Pageable;

/**
 * Class Index
 * @package UOL\welight\Controller\Adminhtml
 */
class Index extends Pageable
{

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context, $resultPageFactory);
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {

        /** @var \UOL\welight\Helper\Abandoned $abandonedHelper */
        $abandonedHelper = $this->_objectManager->create('UOL\welight\Helper\Abandoned');
        /** @var \UOL\welight\Helper\Auth $authHelper */
        $authHelper = $this->_objectManager->create('UOL\welight\Helper\Auth');

        /** Check for credentials **/
        if (!$authHelper->hasCredentials())
            return $this->_redirect('welight/credentials/error');

        /** Check if abandoned is already active**/
        if (!$abandonedHelper->isActive())
            return $this->_redirect('welight/abandoned/error');

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Abandonadas'));
        $resultPage->getLayout()->getBlock('adminhtml.block.welight.abandoned.content')->setData('adminurl', $this->getAdminUrl());
        return $resultPage;
    }

    /**
     * Abandoned access rights checking
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('WE_welight::Abandoned');
    }

}
