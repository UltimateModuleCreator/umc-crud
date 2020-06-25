<?php

/**
 * Umc_Crud extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category  Umc
 * @package   Umc_Crud
 * @copyright 2020 Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru
 */

declare(strict_types=1);

namespace Umc\Crud\Test\Unit\Controller\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Controller\Adminhtml\Upload;
use Umc\Crud\Model\Uploader;

class UploadTest extends TestCase
{
    /**
     * @var Context | MockObject
     */
    private $context;
    /**
     * @var Uploader | MockObject
     */
    private $uploader;
    /**
     * @var ResultFactory | MockObject
     */
    private $resultFactory;
    /**
     * @var Json | MockObject
     */
    private $resultJson;
    /**
     * @var RequestInterface | MockObject
     */
    private $request;
    /**
     * @var Upload
     */
    private $upload;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->uploader = $this->createMock(Uploader::class);
        $this->request = $this->createMock(RequestInterface::class);
        $this->resultFactory = $this->createMock(ResultFactory::class);
        $this->resultJson = $this->createMock(Json::class);
        $this->resultFactory->method('create')->willReturn($this->resultJson);
        $this->context->method('getRequest')->willReturn($this->request);
        $this->context->method('getResultFactory')->willReturn($this->resultFactory);
        $this->upload = new Upload(
            $this->context,
            $this->uploader
        );
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\Upload::execute
     * @covers \Umc\Crud\Controller\Adminhtml\Upload::getFieldName
     * @covers \Umc\Crud\Controller\Adminhtml\Upload::__construct
     */
    public function testExecute()
    {
        $this->uploader->method('saveFileToTmpDir')->willReturn(['result']);
        $this->resultJson->expects($this->once())->method('setData')->with(['result']);
        $this->assertEquals($this->resultJson, $this->upload->execute());
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\Upload::execute
     * @covers \Umc\Crud\Controller\Adminhtml\Upload::getFieldName
     * @covers \Umc\Crud\Controller\Adminhtml\Upload::__construct
     */
    public function testExecuteWithException()
    {
        $this->uploader->method('saveFileToTmpDir')->willThrowException(new \Exception());
        $this->assertEquals($this->resultJson, $this->upload->execute());
    }
}
