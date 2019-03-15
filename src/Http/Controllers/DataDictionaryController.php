<?php

namespace Encore\Admin\DataDictionary\Http\Controllers;

use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class DataDictionaryController extends Controller
{
    public function index(Content $content, Request $request)
    {
        $connection = $request->get('connection', config('database.default'));

        $tables = collect($this->select('show table status'))->map(function ($table) {
            return (array)$table;
        });

        $connections = collect(config('database.connections'))->filter(function ($conn) {
            return $conn['driver'] == 'mysql';
        })->keys();

        return $content
            ->header('Data dictionary')
            ->description($connection)
            ->body(view('data-dictionary::index', compact('tables', 'connection', 'connections')));
    }

    public function desc(Request $request)
    {
        $table = $request->get('table');

        $desc = $this->select("SHOW FULL COLUMNS FROM {$table}");

        return collect($desc)->map(function ($item) {
            return (array)$item;
        });
    }

    public function export(Request $request)
    {
        $tables = explode(',', $request->get('table'));

        if (empty($tables)) {
            return;
        }

        $dump = '';

        foreach ($tables as $table) {

            $dump .= "DROP TABLE IF EXISTS `$table`;\n\n";

            $createExpr = $this->select("show create table `$table`");

            $dump .= data_get($createExpr, '0.Create Table') . ";\n\n\n\n";
        }

        $fileName = 'dump.sql';

        if (count($tables) == 1) {
            $fileName = $tables[0].'.sql';
        }

        return response($dump, '200', [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    protected function select($sql)
    {
        $connection = \request()->get('connection', config('database.default'));

        return DB::connection($connection)->select($sql);
    }
}