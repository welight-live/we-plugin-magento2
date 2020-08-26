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

namespace UOL\welight\Controller;

abstract class Ajaxable extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\Controller\Result\Json  */
    protected $_result;

    /**
     * Ajaxable constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        /** @var \Magento\Framework\Controller\Result\Json $_result */
        $this->_result = $resultJsonFactory->create();
    }

    /**
     * Return when success
     *
     * @param $response
     * @return \Magento\Framework\Controller\Result\Json
     */
    protected function whenSuccess($response)
    {
        return $this->_result->setData([
            'success' => true,
            'payload' => [
                'data' => $response
            ]
        ]);
    }

    /**
     * Return when fails
     *
     * @param $message
     * @return \Magento\Framework\Controller\Result\Json
     */
    protected function whenError($message)
    {
        return $this->_result->setData([
            'success' => false,
            'payload' => [
                'error'    => $message,
            ]
        ]);
    }
}