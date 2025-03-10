<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
class CreateTalksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('length')->nullable();
            $table->string('type');
            $table->text('abstract')->nullable();
            $table->text('organizer_notes')->nullable();
            $table->foreignIdFor(User::class);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('talks');
    }
}
