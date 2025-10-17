<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Herds;
use App\Models\Property;
use App\Models\RuralProducer;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

use function Spatie\LaravelPdf\Support\pdf;

class HerdsReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(String $id)
    {
        $herdsPerProducer = RuralProducer::with('properties.herds')->find($id);

        if (!$herdsPerProducer) {
            return response()->json(['message' => 'Nenhum rebanho cadastrado para esse produtor']);
        }

        return pdf()->withBrowsershot(function (Browsershot $browsershot) {
            return $browsershot->noSandbox()
                ->setNodeBinary(env('BROWSERSHOT_NODE_BINARY'))
                ->setNpmBinary(env('BROWSERSHOT_NPM_BINARY'))
                ->setChromePath(env('BROWSERSHOT_CHROME_PATH'));
        })
            ->view('pdfs.herds', ['producer' => $herdsPerProducer])
            ->name('herds_per_producer' . now() . '.pdf')
            ->download();
    }
}
