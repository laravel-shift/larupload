<?php

use Hashids\Hashids;
use Mostafaznv\Larupload\Enums\LaruploadSecureIdsMethod;
use Illuminate\Support\Str;
use Mostafaznv\Larupload\Test\Support\Enums\LaruploadTestModels;
use Mostafaznv\Larupload\Test\Support\TestAttachmentBuilder;


it('will hide ids using ulid method', function () {
    config()->set('larupload.secure-ids', LaruploadSecureIdsMethod::ULID);

    $model = LaruploadTestModels::HEAVY->instance();
    $model->setAttachments(
        TestAttachmentBuilder::make($model->mode)->withLandscapeImage()->toArray()
    );

    $model = save($model, jpg());
    $attachment = $model->attachment('main_file');
    $id = $attachment->meta('id');

    expect(Str::isUlid($id))->toBeTrue()
        ->and($attachment->url())
        ->toContain($id)
        ->toBeExists()
        //
        ->and($attachment->url('cover'))
        ->toContain($id)
        ->toBeExists()
        //
        ->and($attachment->url('landscape'))
        ->toContain($id)
        ->toBeExists();
});

it('will hide ids using uuid method', function () {
    config()->set('larupload.secure-ids', LaruploadSecureIdsMethod::UUID);

    $model = LaruploadTestModels::HEAVY->instance();
    $model->setAttachments(
        TestAttachmentBuilder::make($model->mode)->withLandscapeImage()->toArray()
    );

    $model = save($model, jpg());
    $attachment = $model->attachment('main_file');
    $id = $attachment->meta('id');

    expect(Str::isUuid($id))->toBeTrue()
        ->and($attachment->url())
        ->toContain($id)
        ->toBeExists()
        //
        ->and($attachment->url('cover'))
        ->toContain($id)
        ->toBeExists()
        //
        ->and($attachment->url('landscape'))
        ->toContain($id)
        ->toBeExists();
});

it('will hide ids using hashid method', function () {
    config()->set('larupload.secure-ids', LaruploadSecureIdsMethod::HASHID);

    $model = LaruploadTestModels::HEAVY->instance();
    $model->setAttachments(
        TestAttachmentBuilder::make($model->mode)->withLandscapeImage()->toArray()
    );

    $model = save($model, jpg());
    $attachment = $model->attachment('main_file');
    $id = $attachment->meta('id');
    $hashIds = new Hashids(config('app.key'), 20);

    expect($hashIds->decode($id))->toBe([1])
        ->and($attachment->url())
        ->toContain($id)
        ->toBeExists()
        //
        ->and($attachment->url('cover'))
        ->toContain($id)
        ->toBeExists()
        //
        ->and($attachment->url('landscape'))
        ->toContain($id)
        ->toBeExists();
});

it('wont hide hide id in upload path when secure-ids is disabled', function () {
    $model = LaruploadTestModels::HEAVY->instance();
    $model->setAttachments(
        TestAttachmentBuilder::make($model->mode)->withLandscapeImage()->toArray()
    );

    $model = save($model, jpg());
    $attachment = $model->attachment('main_file');
    $id = $attachment->meta('id');

    expect($id)->toBe('1')
        ->and($attachment->url())
        ->toContain($id)
        ->toBeExists()
        //
        ->and($attachment->url('cover'))
        ->toContain($id)
        ->toBeExists()
        //
        ->and($attachment->url('landscape'))
        ->toContain($id)
        ->toBeExists();
});

it('will work with light mode', function () {
    config()->set('larupload.secure-ids', LaruploadSecureIdsMethod::ULID);

    $model = LaruploadTestModels::LIGHT->instance();
    $model->setAttachments(
        TestAttachmentBuilder::make($model->mode)->withLandscapeImage()->toArray()
    );

    $model = save($model, jpg());
    $attachment = $model->attachment('main_file');
    $id = $attachment->meta('id');

    expect(Str::isUlid($id))->toBeTrue()
        ->and($attachment->url())
        ->toContain($id)
        ->toBeExists()
        //
        ->and($attachment->url('cover'))
        ->toContain($id)
        ->toBeExists()
        //
        ->and($attachment->url('landscape'))
        ->toContain($id)
        ->toBeExists();
});

it('will work with ffmpeg class [video]', function () {
    config()->set('larupload.secure-ids', LaruploadSecureIdsMethod::ULID);

    $model = LaruploadTestModels::HEAVY->instance();
    $model->setAttachments(
        TestAttachmentBuilder::make($model->mode)->withLandscapeVideo()->with480pStream()->toArray()
    );

    $model = save($model, mp4());

    $attachment = $model->attachment('main_file');
    $id = $attachment->meta('id');

    expect(Str::isUlid($id))->toBeTrue()
        ->and($attachment->url())
        ->toContain($id)
        ->toBeExists()
        //
        ->and($attachment->url('cover'))
        ->toContain($id)
        ->toBeExists()
        //
        ->and($attachment->url('stream'))
        ->toContain($id)
        ->toBeExists()
        //
        ->and($attachment->url('landscape'))
        ->toContain($id)
        ->toBeExists();
});

it('will work with ffmpeg class [audio]', function () {
    config()->set('larupload.secure-ids', LaruploadSecureIdsMethod::ULID);

    $model = LaruploadTestModels::HEAVY->instance();
    $model->setAttachments(
        TestAttachmentBuilder::make($model->mode)->withWavAudio()->toArray()
    );

    $model = save($model, mp3());

    $attachment = $model->attachment('main_file');
    $id = $attachment->meta('id');

    expect(Str::isUlid($id))->toBeTrue()
        ->and($attachment->url())
        ->toContain($id)
        ->toBeExists()
        //
        ->and($attachment->url('cover'))
        ->toBeNull()
        //
        ->and($attachment->url('audio_wav'))
        ->toContain($id)
        ->toBeExists();
});
