<?php

namespace App\Transformers\Inbound\Web;

use App\Transformers\Datum;
use App\Transformers\Inbound\WebTransformer;

class DigitalPublicationSection extends WebTransformer
{

    use HasBlocks { getExtraFields as getBlockFields;
    }

    protected function getExtraFields(Datum $datum)
    {
        return array_merge($this->getBlockFields($datum), [
            'date' => $datum->date('date'),

            // TODO: Move these to trait?
            'publish_start_date' => $datum->date('publish_start_date'),
        ]);
    }
}
