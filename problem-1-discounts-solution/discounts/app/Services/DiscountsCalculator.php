<?php declare(strict_types = 1);

namespace App\Services;

use App\Enums\RuleType;
use App\Models\Rule;
use App\Repositories\RuleRepository;
use App\Services\DiscountActions\ActionFactory;
use App\Services\DiscountRules\RuleFactory;
use App\Services\DTO\ArrayOfProducts;
use App\Services\DTO\Customer;
use App\Services\DTO\Order;
use App\Services\ExternalAPI\CustomerClient;
use App\Services\ExternalAPI\ProductClient;

class DiscountsCalculator
{
    private ?Customer $customer;

    private Order $order;

    private ArrayOfProducts $products;

    private array $appliedRules;

    public function __construct(
        private readonly RuleRepository $ruleRepository,
        private readonly CustomerClient $customerClient,
        private readonly ProductClient  $productClient,
    ) {
        $this->products = new ArrayOfProducts();
        $this->appliedRules = [];
    }

    public function calculate(Order $order): array
    {
        $this->order = $order;
        $rules = $this->ruleRepository->findAllPerRuleType();

        $customerBasedRules = $rules[RuleRepository::CUSTOMER_BASED_RULE];
        $orderBasedRules = $rules[RuleRepository::ORDER_BASED_RULE];
        $productBasedRules = $rules[RuleRepository::PRODUCT_BASED_RULE];

        $this->processCusromerRules($customerBasedRules);
        $this->processProductRules($productBasedRules);
        $this->processRules($orderBasedRules);

        return $this->calculateDiscounts($this->appliedRules);
    }

    private function processRules($rules): void {
        /** @var Rule $rule */
        foreach ($rules as $rule) {
            $ruleObject = RuleFactory::createRule($rule->getSubtype(), $this->customer, $this->order, $this->products);
            if ($ruleObject?->evaluate($rule->getConditionKey(), $rule->getOperator(), $rule->getTargetValue())) {
                $this->appliedRules[] = $rule;
            }
        }
    }

    private function processCusromerRules($customerBasedRules): void
    {
        if (!empty($customerBasedRules)) {
            $this->customer = $this->customerClient->fetchCustomer($this->order->getCustomerId());
        }

        if ($this->customer) {
            $this->processRules($customerBasedRules);
        }
    }

    private function processProductRules($productBasedRules): void
    {
        if (!empty($productBasedRules)) {
            $this->products = $this->productClient->fetchProducts($this->order->getItems());
        }

        if (!empty($this->products)) {
            $this->processRules($productBasedRules);
        }
    }

    private function calculateDiscounts($appliedRules): array
    {
        $discounts = [];
        /** @var Rule $rule */
        foreach ($appliedRules as $rule) {
            $action = ActionFactory::createAction(
                $rule,
                $this->order,
                $this->products,
            );

            $discounts[] = $action->apply()->serialize();
        }

        return $discounts;
    }

}
