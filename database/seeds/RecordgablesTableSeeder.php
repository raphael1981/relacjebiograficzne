<?php

use Illuminate\Database\Seeder;

class RecordgablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //aszkenazy: kornblum, nowicka
        \App\Recordgables\RecordgablesRepository::getInstance()->linkAllToAll([21,26,2]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(29,[14,22,4]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(14,[29,22,25,28,13]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(22,[28,4,17]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(4,[28,22,17]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(17,[4,28,22]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(16,[3,8]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(5,[13,19]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(18,[9,7,24]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkAllToAll([20,23,27]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(3,[6,25]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(6,[3,25]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(25,[6,3,14,29]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(8,[16,3,1]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(24,[12,7,9,1]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(29,[14,30,31]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(31,[29,30]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(30,[29,31]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkAllToAll([7,24,9,1,15]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(13,[5,19,14]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(11,[25,1,23]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(10,[18,24]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(19,[5,13]);

        \App\Recordgables\RecordgablesRepository::getInstance()->linkManyToOne(12,[24,14]);

    }
}
