<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class CreateProgresserTable extends Migration
{
    /**
     * Stores the table name to use.
     *
     * @var string
     */
    protected string $table;

    /**
     * Creates a new instance of the class.
     */
    public function __construct()
    {
        $this->table = Config::get('progresser.table');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable();
            $table->string('default_completed_status')->nullable();
            $table->string('default_failed_status')->nullable();
            $table->integer('current_step')->nullable();
            $table->integer('steps')->nullable();
            $table->boolean('running')->default(false);
            $table->boolean('failed')->default(false);
            $table->json('failed_payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
}
