<?php

declare(strict_types=1);

use Heritage\Console\OutputStyle;
use Ugarit\Pao\Ugarit\PaoOutputStyle;
use Ugarit\Pao\Ugarit\ServiceProvider;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\Ugarit\TestCase;

uses(TestCase::class);

afterEach(function (): void {
    unset($_SERVER['AI_AGENT'], $_SERVER['PAO_DISABLE'], $_SERVER['PAO_FORCE']);
    putenv('AI_AGENT');
});

it('does not bind PaoOutputStyle when not in agent mode', function (): void {
    $this->app->register(ServiceProvider::class, true);

    expect($this->app->make(OutputStyle::class, [
        'input' => new ArrayInput([]),
        'output' => new NullOutput,
    ]))->not->toBeInstanceOf(PaoOutputStyle::class);
});

it('does not bind PaoOutputStyle when PAO_DISABLE is set', function (): void {
    $_SERVER['AI_AGENT'] = '1';
    $_SERVER['PAO_DISABLE'] = '1';

    $this->app->register(ServiceProvider::class, true);

    expect($this->app->make(OutputStyle::class, [
        'input' => new ArrayInput([]),
        'output' => new NullOutput,
    ]))->not->toBeInstanceOf(PaoOutputStyle::class);
});
