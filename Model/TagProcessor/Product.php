<?php
/**
 * MageSpecialist
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magespecialist.it so we can send you a copy immediately.
 *
 * @category   MSP
 * @package    MSP_APIBoost
 * @copyright  Copyright (c) 2017 Skeeller srl (http://www.magespecialist.it)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace MSP\APIBoost\Model\TagProcessor;

use Magento\Catalog\Api\ProductRepositoryInterface;
use MSP\APIBoost\Api\TagProcessorInterface;

class Product implements TagProcessorInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    /**
     * Return a list of tags for a request
     * @param \Magento\Framework\App\RequestInterface $request
     * @return string[]
     */
    public function getTags(\Magento\Framework\App\RequestInterface $request)
    {
        if (preg_match('|/rest/V1/products/(.+?)/?$|', $request->getRequestUri(), $matches)) {
            $sku = $matches[1];
            try {
                $product = $this->productRepository->get($sku);
            } catch (\Exception $e) {
                return [];
            }

            return ['catalog_product_' . $product->getId()];
        }

        return [];
    }
}
