<?php declare(strict_types=1);

namespace App\Models;

use App\Enums\Operator;
use App\Enums\RuleActionType;
use App\Enums\RuleSubtype;
use App\Enums\RuleType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repositories\RuleRepository")]
#[ORM\Table(name: "rules")]
class Rule
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    public function __construct(
        #[ORM\Column(name: "type", type: "string", length: 255, enumType: RuleType::class)]
        private readonly RuleType $type,

        #[ORM\Column(name: "subtype", type: "string", length: 255, enumType: RuleSubtype::class)]
        private readonly RuleSubtype $subtype,

        #[ORM\Column(name: "condition_key", type: "string", length: 255)]
        private readonly string $conditionKey,

        #[ORM\Column(name: "operator", type: "string", length: 255, enumType: Operator::class)]
        private readonly Operator $operator,

        #[ORM\Column(name: "target_value", type: "string", length: 255)]
        private readonly string $targetValue,

        #[ORM\Column(name: "action_type", type: "string", length: 255, enumType: RuleActionType::class)]
        private readonly RuleActionType $actionType,

        #[ORM\Column(name: "action_value", type: "string", length: 255)]
        private readonly string $actionValue,

        #[ORM\Column(name: "reason", type: "string", length: 255)]
        private readonly string $reason,

        #[ORM\Column(name: "action_key", type: "string", length: 255)]
        private readonly ?string $actionKey
    ) {

    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): RuleType
    {
        return $this->type;
    }

    public function getSubtype(): RuleSubtype
    {
        return $this->subtype;
    }

    public function getConditionKey(): string
    {
        return $this->conditionKey;
    }

    public function getOperator(): Operator
    {
        return $this->operator;
    }

    public function getTargetValue(): string
    {
        return $this->targetValue;
    }

    public function getActionType(): RuleActionType
    {
        return $this->actionType;
    }

    public function getActionValue(): string
    {
        return $this->actionValue;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getActionKey(): ?string
    {
        return $this->actionKey;
    }
}
