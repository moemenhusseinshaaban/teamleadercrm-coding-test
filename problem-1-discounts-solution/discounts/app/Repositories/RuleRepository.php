<?php declare(strict_types=1);

namespace App\Repositories;

use App\Enums\RuleType;
use App\Models\Rule;
use Doctrine\ORM\EntityRepository;

class RuleRepository extends EntityRepository
{
    const CUSTOMER_BASED_RULE = 'customer_based_rules';
    const ORDER_BASED_RULE = 'order_based_rules';
    const PRODUCT_BASED_RULE = 'product_based_rules';

    public function findAllPerRuleType(): array
    {
        $rules = $this->findAll();
        $customerBasedRules = [];
        $orderBasedRules = [];
        $productBasedRules = [];

        /** @var Rule $rule */
        foreach ($rules as $rule) {
            match ($rule->getType()) {
                RuleType::CUSTOMER => $customerBasedRules[] = $rule,
                RuleType::ORDER => $orderBasedRules[] = $rule,
                RuleType::PRODUCT => $productBasedRules[] = $rule,
            };
        }

        return [
            self::CUSTOMER_BASED_RULE => $customerBasedRules,
            self::ORDER_BASED_RULE => $orderBasedRules,
            self::PRODUCT_BASED_RULE => $productBasedRules,
        ];
    }
}
