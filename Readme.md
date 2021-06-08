# Progresser

Adds progress to your laravel application.

```php
$progress = Progresser::create();

// Control methods
$progress->start('Preparing information...');
$progress->step('Done task 1');
$progress->statis('Now doing this...');
$progress->complete('Done task 2');
$progress->fail('Failed at task 2');

// Methods
$progress->isRunning();
$progress->hasFailed();
$progress->hasCompleted();
$progress->isStepped();
$progress->percentage();

// Attributes
$progress->status;
$progress->current_step;
$progress->steps;
$progress->running;
$progress->failed;
$progress->failed_payload;
$progress->default_completed_status;
$progress->default_failed_status;
```

```php
$progress = Progresser::create();

$progress->start('Preparing information...');
$progress->step('Done task 1');
$progress->complete('Done task 2');
```

```php
$progress = Progresser::create();

$progress->start('Preparing information...', 2);
$progress->step('Done task 1');
$progress->step('Done task 2');
```

```php
$progress = Progresser::create();

$progress->start('Preparing information...');
$progress->step('Done task 1');
$progress->fail('Done task 2');
```
