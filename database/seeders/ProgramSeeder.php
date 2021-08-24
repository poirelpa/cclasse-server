<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Program;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('programs')->delete();

        Storage::disk('public')->put(
            'references/cycle3.html',
            Storage::disk('database_data')->get('references/cycle3.html'),
            'public'
        );

        $contents = Storage::disk('database_data')->get('programs/cycle3.json');
        $program = json_decode($contents);
        $p = Program::create([
            'name' => $program->name,
            'reference_file' => $program->reference
        ]);

        $p->levels()->sync([7,8]);


        foreach ($program->items as $item) {
            $this->storeSubject($item, $p->id);
        }
    }

    private function storeSubject($item, $programId = null, $parentId = null)
    {
        $subject = Subject::create([
            'name' => $item->name,
            'color' => $item->color,
            'reference_xpath' => $item->referenceXPath ?? '',
            'parent_id' => $parentId,
            'program_id' => $programId
        ]);

        foreach ($item->items as $item) {
            $this->storeSubject($item, $programId, $subject->id);
        }
    }
}
