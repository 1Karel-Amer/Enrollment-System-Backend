<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public function up() {
    Schema::create('subjects', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('term');
        $table->string('preReq')->nullable();
        $table->timestamps();
    });
}
}
