# Progresser

Adds progress to your laravel application.

```php
$progress = Progress::create();

// Control methods
$progress->start('Preparing information...');
$progress->step('Done task 1');
$progress->status('Now doing this...');
$progress->complete('Done task 2');
$progress->fail('Failed at task 2');

// Methods
$progress->isRunning();
$progress->hasFailed();
$progress->hasCompleted();
$progress->hasSteps();
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
$progress = Progress::create();

$progress->start('Preparing information...');
$progress->step('Done task 1');
$progress->complete('Done task 2');
```

```php
$progress = Progress::create();

$progress->start('Preparing information...', 2);
$progress->step('Done task 1');
$progress->step('Done task 2');
```

```php
$progress = Progress::create();

$progress->start('Preparing information...');
$progress->step('Done task 1');
$progress->fail('Done task 2');
```

```php
use Illuminate\Database\Eloquent\Model;
use ConsoleTVs\Progresser\Traits\Progressable;

class Book extends Model
{
    use Progressable;
}

$book = Book::create();
$progress = $book->progress('review');
$progress->start('Starting book review...');
$progress->complete('Finished review...');
```
