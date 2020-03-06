<?php

namespace Thomasdominic\ModelsTestor\Tests;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
        $this->withFactories(realpath(dirname(__DIR__).'/database/factories'));
        $this->setUpDatabase();
    }

    protected function setUpDatabase()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('first_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('second_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('first_model_id')->nullable();
            $table->foreign('first_model_id')->references('id')->on('first_models');
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('third_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('second_model_third_model', function (Blueprint $table) {
            $table->integer('second_model_id');
            $table->integer('third_model_id');
            $table->foreign('second_model_id')->references('id')->on('second_models');
            $table->foreign('third_model_id')->references('id')->on('third_models');
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('morph_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->nullableMorphs('morph_modelable');
        });
    }
}
