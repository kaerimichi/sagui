<?php


use Phinx\Migration\AbstractMigration;

class CreateTableConfiguration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $users = $this->table('configurations');
        $users->addColumn('plugin', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('data', 'text', ['null' => false, 'default' => '{}'])
            ->addColumn('active', 'boolean', ['null' => false, 'default' => false])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['email'])
            ->save();
    }
}
