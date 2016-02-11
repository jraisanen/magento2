<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Checkout\Model\Cart;

class CollectQuote
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Customer\Api\AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * @var \Magento\Quote\Api\Data\EstimateAddressInterfaceFactory
     */
    protected $estimatedAddressFactory;

    /**
     * @var \Magento\Quote\Api\ShippingMethodManagementInterface
     */
    protected $shippingMethodManager;

    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Api\AddressRepositoryInterface $addressRepository
     * @param \Magento\Quote\Api\Data\EstimateAddressInterfaceFactory $estimatedAddressFactory
     * @param \Magento\Quote\Api\ShippingMethodManagementInterface $shippingMethodManager
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Quote\Api\Data\EstimateAddressInterfaceFactory $estimatedAddressFactory,
        \Magento\Quote\Api\ShippingMethodManagementInterface $shippingMethodManager,
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ) {
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
        $this->estimatedAddressFactory = $estimatedAddressFactory;
        $this->shippingMethodManager = $shippingMethodManager;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @return void
     */
    public function collect(\Magento\Quote\Model\Quote $quote)
    {
        if ($this->customerSession->isLoggedIn()) {
            $customer = $this->customerRepository->getById($this->customerSession->getCustomerId());
            if (!$customer->getDefaultShipping()) {
                $quote->setTotalsCollectedFlag(false);
                $quote->collectTotals();
                $this->quoteRepository->save($quote);
            }
        }
    }
}
