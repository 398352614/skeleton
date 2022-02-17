<?php

declare(strict_types=1);

namespace App\Process;

use Hyperf\Process\AbstractProcess;
use Hyperf\Process\Annotation\Process;

/**
 * @Process(name="FooProcess")
 */
#[Process(name: 'FooProcess')]
class FooProcess extends AbstractProcess
{
    public function handle(): void
    {
    }
}
