<?php

declare(strict_types=1);

namespace Ugarit\Pao\Ugarit;

use Heritage\Console\Events\CommandStarting;
use Heritage\Console\OutputStyle;
use Heritage\Contracts\Events\Dispatcher;
use Heritage\Support\ServiceProvider as UgaritServiceProvider;
use Ugarit\AgentDetector\AgentDetector;

/**
 * @internal
 *
 * @codeCoverageIgnore
 */
final class ServiceProvider extends UgaritServiceProvider
{
    public function boot(): void
    {
        if (filter_var($_SERVER['PAO_DISABLE'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        if (! $this->app->runningInConsole()) {
            return;
        }

        if ($this->app->runningUnitTests()) {
            return;
        }

        if (! AgentDetector::detect()->isAgent && ! filter_var($_SERVER['PAO_FORCE'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        $this->app->bind(OutputStyle::class, PaoOutputStyle::class);

        /** @var Dispatcher $events */
        $events = $this->app->make(Dispatcher::class);
        $events->listen(CommandStarting::class, function (CommandStarting $event): void {
            $event->output->setDecorated(false);
        });
    }
}
