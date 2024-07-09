<?php

namespace Mostafaznv\Larupload\Test\Support;

use Illuminate\Foundation\Application;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Mostafaznv\Larupload\Enums\LaruploadMode;
use Mostafaznv\Larupload\Larupload;

class LaruploadTestTablesMigration extends Migration
{
    public function __construct(private readonly Application $app) {}

    public function migrate(): void
    {
        config()->set('larupload.store-original-file-name', true);

        $this->heavy();
        $this->light();
        $this->withoutStoreOriginalNameColumn();
        $this->softDelete();
        $this->queue();

        config()->set('larupload.store-original-file-name', false);
    }


    private function heavy(): void
    {
        $this->app['db']->connection()
            ->getSchemaBuilder()
            ->create('upload_heavy', function(Blueprint $table) {
                $table->id();
                $table->upload('main_file', LaruploadMode::HEAVY);
                $table->timestamps();
            });
    }

    private function light(): void
    {
        $this->app['db']->connection()
            ->getSchemaBuilder()
            ->create('upload_light', function(Blueprint $table) {
                $table->id();
                $table->upload('main_file', LaruploadMode::LIGHT);
                $table->timestamps();
            });
    }

    private function withoutStoreOriginalNameColumn(): void
    {
        config()->set('larupload.store-original-file-name', false);

        $this->app['db']->connection()
            ->getSchemaBuilder()
            ->create('without_store_original_name_column', function(Blueprint $table) {
                $table->id();
                $table->upload('main_file', LaruploadMode::HEAVY);
                $table->timestamps();
            });

        config()->set('larupload.store-original-file-name', true);
    }

    private function softDelete(): void
    {
        $this->app['db']->connection()
            ->getSchemaBuilder()
            ->create('upload_soft_delete', function(Blueprint $table) {
                $table->id();
                $table->upload('main_file', LaruploadMode::HEAVY);
                $table->timestamps();
                $table->softDeletes();
            });
    }

    private function queue(): void
    {
        $this->app['db']->connection()
            ->getSchemaBuilder()
            ->create(Larupload::FFMPEG_QUEUE_TABLE, function(Blueprint $table) {
                $table->id();
                $table->unsignedInteger('record_id');
                $table->string('record_class', 50);
                $table->boolean('status')->default(0);
                $table->text('message')->nullable();

                $table->timestamp('created_at')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('finished_at')->nullable();
            });
    }
}
