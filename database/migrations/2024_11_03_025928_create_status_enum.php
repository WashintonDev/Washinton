<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStatusEnum extends Migration
{
    public function up()
    {
        DB::statement("CREATE TYPE status_enum AS ENUM ('active', 'inactive');");
    }

    public function down()
    {
        DB::statement("DROP TYPE status_enum;");
    }
}
