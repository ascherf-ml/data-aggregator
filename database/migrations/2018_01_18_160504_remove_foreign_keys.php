<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Symfony\Component\Console\Output\ConsoleOutput;

class RemoveForeignKeys extends Migration
{

    public function up()
    {

        $output = new ConsoleOutput();
        $conn = Schema::getConnection()->getDoctrineSchemaManager();

        $tables = DB::select('SHOW TABLES');

        foreach( $tables as $table )
        {
            $table_array = get_object_vars( $table );
            $table_name = $table_array[ key( $table_array ) ];

            $foreign_keys = $conn->listTableForeignKeys( $table_name );

            Schema::table($table_name, function (Blueprint $table) use ($output, $table_name, $foreign_keys) {

                foreach( $foreign_keys as $key )
                {

                    $key_name = $key->getName();
                    $table->dropForeign( $key_name );

                    $output->writeln( 'Dropped `' . $key_name . '` from table `' . $table_name . '` ');

                }

            });

        }

    }

    public function down()
    {

        // It seems that we cannot add foreign keys to existing, filled tables
        // There might be a way, but working out all the bugs currently doesn't seem worth it

    }

}
