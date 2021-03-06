<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Framework\Interceptor;

use Spiral\App\Controller\Demo2Controller;
use Spiral\App\Controller\DemoController;
use Spiral\Core\CoreInterface;
use Spiral\Core\Exception\ControllerException;
use Spiral\Core\Exception\InterceptorException;
use Spiral\Framework\ConsoleTest;
use Spiral\Security\Actor\Actor;
use Spiral\Security\ActorInterface;

class GuardedTest extends ConsoleTest
{
    public function testInvalidAnnotationConfiguration(): void
    {
        /** @var CoreInterface $core */
        $core = $this->app->get(CoreInterface::class);

        $this->expectException(InterceptorException::class);
        $core->callAction(DemoController::class, 'guardedButNoName', []);
    }

    public function testNotAllowed(): void
    {
        /** @var CoreInterface $core */
        $core = $this->app->get(CoreInterface::class);

        $this->expectException(ControllerException::class);
        $core->callAction(DemoController::class, 'do', []);
    }

    public function testNotAllowed2(): void
    {
        /** @var CoreInterface $core */
        $core = $this->app->get(CoreInterface::class);

        $this->expectException(ControllerException::class);
        $core->callAction(Demo2Controller::class, 'do1', []);
    }

    public function testNotAllowedError1(): void
    {
        /** @var CoreInterface $core */
        $core = $this->app->get(CoreInterface::class);

        $this->expectExceptionCode(ControllerException::FORBIDDEN);
        $core->callAction(Demo2Controller::class, 'do1', []);
    }

    public function testNotAllowedError2(): void
    {
        /** @var CoreInterface $core */
        $core = $this->app->get(CoreInterface::class);

        $this->expectExceptionCode(ControllerException::NOT_FOUND);
        $core->callAction(Demo2Controller::class, 'do2', []);
    }


    public function testNotAllowedError3(): void
    {
        /** @var CoreInterface $core */
        $core = $this->app->get(CoreInterface::class);

        $this->expectExceptionCode(ControllerException::ERROR);
        $core->callAction(Demo2Controller::class, 'do3', []);
    }


    public function testNotAllowedError4(): void
    {
        /** @var CoreInterface $core */
        $core = $this->app->get(CoreInterface::class);

        $this->expectExceptionCode(ControllerException::BAD_ACTION);
        $core->callAction(Demo2Controller::class, 'do4', []);
    }

    public function testAllowed(): void
    {
        /** @var CoreInterface $core */
        $core = $this->app->get(CoreInterface::class);

        $this->app->getContainer()->bind(ActorInterface::class, new Actor(['user']));

        $this->assertSame('ok', $core->callAction(DemoController::class, 'do', []));
    }

    public function testNotAllowed3(): void
    {
        /** @var CoreInterface $core */
        $core = $this->app->get(CoreInterface::class);

        $this->app->getContainer()->bind(ActorInterface::class, new Actor(['user']));

        $this->expectExceptionCode(ControllerException::FORBIDDEN);
        $this->assertSame('ok', $core->callAction(Demo2Controller::class, 'do1', []));
    }

    public function testAllowed2(): void
    {
        /** @var CoreInterface $core */
        $core = $this->app->get(CoreInterface::class);

        $this->app->getContainer()->bind(ActorInterface::class, new Actor(['demo']));
        $this->assertSame('ok', $core->callAction(Demo2Controller::class, 'do1', []));
    }
}
