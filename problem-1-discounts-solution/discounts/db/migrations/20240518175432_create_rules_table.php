<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateRulesTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('rules');
        $table->addColumn('type', 'string', ['limit' => 255])
            ->addColumn('subtype', 'string', ['limit' => 255])
            ->addColumn('condition_key', 'string', ['limit' => 255])
            ->addColumn('operator', 'string', ['limit' => 255])
            ->addColumn('target_value', 'string', ['limit' => 255])
            ->addColumn('action_type', 'string', ['limit' => 255])
            ->addColumn('action_value', 'string', ['limit' => 255])
            ->addColumn('reason', 'string', ['limit' => 255])
            ->addColumn('action_key', 'string', ['limit' => 255, 'null' => true])
            ->create();
    }
}
