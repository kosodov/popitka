<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('logs_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_url');
            $table->string('http_method');
            $table->string('controller');
            $table->string('method');
            $table->text('request_body')->nullable();
            $table->text('request_headers')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->integer('status_code');
            $table->text('response_body')->nullable();
            $table->text('response_headers')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('logs_requests');
    }
}
