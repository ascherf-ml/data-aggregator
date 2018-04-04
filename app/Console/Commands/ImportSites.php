<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;

use App\Models\StaticArchive\Site;

class ImportSites extends AbstractImportCommandNew
{

    protected $signature = 'import:sites
                            {--y|yes : Answer "yes" to all prompts}';

    protected $description = "Import all historic microsites";


    public function handle()
    {

        $hasReset = $this->reset(
            [
                Site::class,
            ],
            [
                'artwork_site',
                'agent_site',
                'exhibition_site',
                'sites',
            ]
        );

        if( !$hasReset )
        {
            return false;
        }

        $contents = $this->fetch( env('STATIC_ARCHIVE_JSON') );

        Storage::disk('local')->put('archive.json', $contents);

        $contents = Storage::get('archive.json');

        $results = json_decode( $contents );

        $this->importSites( $results->data );

    }


    private function importSites( $results )
    {

        $this->info("Importing static sites");

        foreach( $results as $datum )
        {

            $this->save( $datum, Site::class );

        }

    }

}
