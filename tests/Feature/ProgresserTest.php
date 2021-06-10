<?php

declare(strict_types=1);

namespace ConsoleTVs\Progresser\Tests\Feature;

use ConsoleTVs\Progresser\Models\Progress;
use ConsoleTVs\Progresser\Tests\TestCase;
use ConsoleTVs\Progresser\Traits\Progressable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class ProgresserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_stepped_progresses()
    {
        $progress = Progress::create();

        $this->assertEquals($progress->isRunning(), false);

        $progress->start('Preparing information...', 5);
        $this->assertEquals($progress->isRunning(), true);
        $this->assertEquals($progress->percentage(), 0.0);
        $this->assertEquals($progress->current_step, 0);
        $this->assertEquals($progress->status, 'Preparing information...');

        $progress->step('Done task 1');
        $this->assertEquals($progress->current_step, 1);
        $this->assertEquals($progress->percentage(), 20.0);
        $this->assertEquals($progress->status, 'Done task 1');

        $progress->step('Done task 2');
        $this->assertEquals($progress->current_step, 2);
        $this->assertEquals($progress->percentage(), 40.0);
        $this->assertEquals($progress->status, 'Done task 2');

        $progress->step('Done task 3');
        $this->assertEquals($progress->current_step, 3);
        $this->assertEquals($progress->percentage(), 60.0);
        $this->assertEquals($progress->status, 'Done task 3');

        $progress->step('Done task 4');
        $this->assertEquals($progress->current_step, 4);
        $this->assertEquals($progress->percentage(), 80.0);
        $this->assertEquals($progress->status, 'Done task 4');

        $progress->step('Done task 5');
        $this->assertEquals($progress->current_step, 5);
        $this->assertEquals($progress->percentage(), 100.0);
        $this->assertEquals($progress->status, 'Done task 5');
        $this->assertEquals($progress->isRunning(), false);
        $this->assertEquals($progress->hasCompleted(), true);
    }

    /** @test */
    public function it_can_create_stepped_progresses_with_complete()
    {
        $progress = Progress::create();

        $this->assertEquals($progress->isRunning(), false);

        $progress->start('Preparing information...', 5);
        $this->assertEquals($progress->isRunning(), true);
        $this->assertEquals($progress->percentage(), 0.0);
        $this->assertEquals($progress->current_step, 0);
        $this->assertEquals($progress->status, 'Preparing information...');

        $progress->step('Done task 1');
        $this->assertEquals($progress->current_step, 1);
        $this->assertEquals($progress->percentage(), 20.0);
        $this->assertEquals($progress->status, 'Done task 1');

        $progress->step('Done task 2');
        $this->assertEquals($progress->current_step, 2);
        $this->assertEquals($progress->percentage(), 40.0);
        $this->assertEquals($progress->status, 'Done task 2');

        $progress->step('Done task 3');
        $this->assertEquals($progress->current_step, 3);
        $this->assertEquals($progress->percentage(), 60.0);
        $this->assertEquals($progress->status, 'Done task 3');

        $progress->step('Done task 4');
        $this->assertEquals($progress->current_step, 4);
        $this->assertEquals($progress->percentage(), 80.0);
        $this->assertEquals($progress->status, 'Done task 4');

        $progress->complete('Done task 5');
        $this->assertEquals($progress->current_step, 5);
        $this->assertEquals($progress->percentage(), 100.0);
        $this->assertEquals($progress->status, 'Done task 5');
        $this->assertEquals($progress->isRunning(), false);
        $this->assertEquals($progress->hasCompleted(), true);
    }

    /** @test */
    public function it_can_create_stepped_progresses_with_complete_2()
    {
        $progress = Progress::create();

        $this->assertEquals($progress->isRunning(), false);

        $progress->start('Preparing information...', 5);
        $this->assertEquals($progress->isRunning(), true);
        $this->assertEquals($progress->percentage(), 0.0);
        $this->assertEquals($progress->current_step, 0);
        $this->assertEquals($progress->status, 'Preparing information...');

        $progress->step('Done task 1');
        $this->assertEquals($progress->current_step, 1);
        $this->assertEquals($progress->percentage(), 20.0);
        $this->assertEquals($progress->status, 'Done task 1');

        $progress->complete('All done');
        $this->assertEquals($progress->current_step, 5);
        $this->assertEquals($progress->percentage(), 100.0);
        $this->assertEquals($progress->status, 'All done');
        $this->assertEquals($progress->isRunning(), false);
        $this->assertEquals($progress->hasCompleted(), true);
    }

    /** @test */
    public function it_can_create_progresses()
    {
        $progress = Progress::create();

        $this->assertEquals($progress->isRunning(), false);

        $progress->start('Preparing information...');
        $this->assertEquals($progress->isRunning(), true);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->current_step, 0);
        $this->assertEquals($progress->status, 'Preparing information...');

        $progress->step('Done task 1');
        $this->assertEquals($progress->current_step, 1);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->status, 'Done task 1');

        $progress->step('Done task 2');
        $this->assertEquals($progress->current_step, 2);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->status, 'Done task 2');

        $progress->step('Done task 3');
        $this->assertEquals($progress->current_step, 3);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->status, 'Done task 3');

        $progress->step('Done task 4');
        $this->assertEquals($progress->current_step, 4);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->status, 'Done task 4');

        $progress->complete('Done task 5');
        $this->assertEquals($progress->current_step, 5);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->status, 'Done task 5');
        $this->assertEquals($progress->isRunning(), false);
        $this->assertEquals($progress->hasCompleted(), true);
    }

    /** @test */
    public function it_can_fail_progresses()
    {
        $progress = Progress::create();

        $this->assertEquals($progress->isRunning(), false);

        $progress->start('Preparing information...');
        $this->assertEquals($progress->isRunning(), true);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->current_step, 0);
        $this->assertEquals($progress->status, 'Preparing information...');

        $progress->step('Done task 1');
        $this->assertEquals($progress->current_step, 1);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->status, 'Done task 1');

        $progress->step('Done task 2');
        $this->assertEquals($progress->current_step, 2);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->status, 'Done task 2');

        $progress->fail('Failed at doing task 3');
        $this->assertEquals($progress->current_step, 2);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->status, 'Failed at doing task 3');
        $this->assertEquals($progress->isRunning(), false);
        $this->assertEquals($progress->hasCompleted(), false);
        $this->assertEquals($progress->hasFailed(), true);
    }

    /** @test */
    public function it_can_fail_progresses_with_payloads()
    {
        $progress = Progress::create();

        $progress->start('Preparing information...');

        $progress->step('Done task 1');
        $progress->step('Done task 2');

        try {
            throw new Exception('Example error');
        } catch (Exception $e) {
            $progress->fail('Failed at doing task 3', $e->getMessage());
        }

        $this->assertEquals($progress->failed_payload, 'Example error');
        $this->assertEquals($progress->status, 'Failed at doing task 3');
        $this->assertEquals($progress->isRunning(), false);
        $this->assertEquals($progress->hasCompleted(), false);
        $this->assertEquals($progress->hasFailed(), true);
    }

    /** @test */
    public function it_can_make_multiple_progresses()
    {
        $progress = Progress::create();

        $progress->start('Preparing information...');
        $this->assertEquals($progress->isRunning(), true);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->current_step, 0);
        $this->assertEquals($progress->status, 'Preparing information...');

        $progress->step('Done task 1');
        $this->assertEquals($progress->current_step, 1);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->hasCompleted(), false);
        $this->assertEquals($progress->status, 'Done task 1');

        $progress->complete('Done task 2');
        $this->assertEquals($progress->current_step, 2);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->hasCompleted(), true);
        $this->assertEquals($progress->status, 'Done task 2');

        $progress->start('Preparing information...');
        $this->assertEquals($progress->isRunning(), true);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->current_step, 0);
        $this->assertEquals($progress->status, 'Preparing information...');

        $progress->step('Done task 1');
        $this->assertEquals($progress->current_step, 1);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->hasCompleted(), false);
        $this->assertEquals($progress->status, 'Done task 1');

        $progress->complete('Done task 2');
        $this->assertEquals($progress->current_step, 2);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->hasCompleted(), true);
        $this->assertEquals($progress->status, 'Done task 2');
    }

    /** @test */
    public function models_can_have_progresses()
    {
        Schema::create('stubs', function ($table) {
            $table->temporary();
            $table->increments('id');
            $table->timestamps();
        });

        $stubClass = new class extends Model
        {
            use Progressable;

            protected $table = 'stubs';
        };

        $stub = $stubClass::create();
        $progress = $stub->progress('example-progress');
        $progress2 = $stub->progress('example-progress-2');

        $progress->start('Preparing information...');
        $this->assertEquals($progress->isRunning(), true);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->current_step, 0);
        $this->assertEquals($progress->status, 'Preparing information...');

        $progress->refresh();
        $progress2->refresh();

        $progress->step('Done task 1');
        $this->assertEquals($progress->current_step, 1);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->hasCompleted(), false);
        $this->assertEquals($progress->status, 'Done task 1');

        $progress->refresh();
        $progress2->refresh();

        $progress2->start('Preparing information 2...');
        $this->assertEquals($progress2->isRunning(), true);
        $this->assertEquals($progress2->percentage(), null);
        $this->assertEquals($progress2->current_step, 0);
        $this->assertEquals($progress2->status, 'Preparing information 2...');

        $progress->refresh();
        $progress2->refresh();

        $progress->step('Done task 2');
        $this->assertEquals($progress->current_step, 2);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->hasCompleted(), false);
        $this->assertEquals($progress->status, 'Done task 2');

        $progress->refresh();
        $progress2->refresh();

        $progress2->complete('Done!');
        $this->assertEquals($progress2->current_step, 1);
        $this->assertEquals($progress2->percentage(), null);
        $this->assertEquals($progress2->hasCompleted(), true);
        $this->assertEquals($progress2->status, 'Done!');

        $progress->refresh();
        $progress2->refresh();

        $progress->step('Done task 3');
        $this->assertEquals($progress->current_step, 3);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->hasCompleted(), false);
        $this->assertEquals($progress->status, 'Done task 3');

        $progress->refresh();
        $progress2->refresh();

        $progress->complete('Done task 4');
        $this->assertEquals($progress->current_step, 4);
        $this->assertEquals($progress->percentage(), null);
        $this->assertEquals($progress->hasCompleted(), true);
        $this->assertEquals($progress->status, 'Done task 4');
    }
}
