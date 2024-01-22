<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Historysa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('historysa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('BetTime');
            $table->datetime('PayoutTime');
            $table->string('Username');
            $table->string('HostID');
            $table->string('GameID');
            $table->string('Round');
            $table->string('Set');
            $table->string('BetID');
            $table->string('BetAmount');
            $table->string('Rolling');
            $table->string('ResultAmount');
            $table->string('Balance');
            $table->string('GameType');
            $table->string('BetType');
            $table->string('BetSource');
            $table->string('Detail');
            $table->string('TransactionID');
            $table->string('GameResult');
            $table->string('State');
            $table->string('statistical');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
