<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('country_code');
            $table->integer('mobile');
            $table->longText('balance')->default('{"BTC":0,"ETH":0,"USDT":0,"BNB":0,"SOL":0,"XRP":0,"USDC":0,"ADA":0,"AVAX":0,"DOGE":0,"TRX":0,"DOT":0,"MATIC":0,"LINK":0,"WBTC":0,"ICP":0,"SHIB":0,"DAI":0,"LTC":0,"BCH":0,"ETC":0,"UNI":0,"ATOM":0,"OP":0,"NEAR":0,"XLM":0,"INJ":0,"APT":0,"LDO":0,"FIL":0,"TIA":0,"XMR":0,"IMX":0,"ARB":0,"HBAR":0,"STX":0,"VET":0,"WBET":0,"TUSD":0,"FDUSD":0,"MKR":0,"SEI":0,"ORDI":0,"GRT":0,"AAVE":0,"ALGO":0,"RUNE":0,"QNT":0,"SUI":0,"EGLD":0}');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
