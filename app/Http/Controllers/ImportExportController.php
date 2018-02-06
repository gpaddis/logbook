<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LogbookEntry;
use League\Csv\Writer;
use Carbon\Carbon;
use Illuminate\Http\Response;

class ImportExportController extends Controller
{
    /**
     * ImportExportController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Export the visits for the time span desired.
     *
     * @param Request $request
     * @return Response
     */
    public function exportCsv(Request $request)
    {
        $request->validate(
            [
                'from' => 'required|date',
                'to' => 'required|date|before_or_equal:' . date('Y-m-d')
            ]
        );

        $start = $request->input('from');
        $end = Carbon::parse($request->input('to'))->addDay()->toDateString();
        $visits = LogbookEntry::within($start, $end)->export();

        if (empty($visits)) {
            return redirect()->back();
        }

        return $this->createCsv($visits);
    }

    /**
     * Create a CSV containing the visits and return it with the response.
     *
     * @param array $visits
     * @return Response
     */
    protected function createCsv(array $visits)
    {
        if (empty($visits)) {
            return null;
        }

        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(array_keys($visits[0]));
        foreach ($visits as $visit) {
            $csv->insertOne($visit);
        }

        // Return a Response object, avoid direct file output
        // See: https://csv.thephpleague.com/9.0/connections/output/
        return new Response(
            $csv, 200, [
                'Content-Encoding' => 'none',
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="Export.csv"',
                'Content-Description' => 'File Transfer',
            ]
        );
    }
}
