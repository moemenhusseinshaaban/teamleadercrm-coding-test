<?php declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class RuleSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $this->execute('TRUNCATE TABLE rules');

        $data = include 'rule_seeds.php';

        $rules = $this->table('rules');
        $rules->insert($data)
            ->saveData();
    }
}
