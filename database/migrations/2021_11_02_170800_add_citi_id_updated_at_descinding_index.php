<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCitiIdUpdatedAtDescindingIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `v1_artworks` ADD INDEX `v1_artworks_citi_id_updated_at_desc_index` (`updated_at` DESC, `citi_id` ASC)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `v1_artworks` DROP INDEX `v1_artworks_citi_id_updated_at_desc_index`');
    }
}
