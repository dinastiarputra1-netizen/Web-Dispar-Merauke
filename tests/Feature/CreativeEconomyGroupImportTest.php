<?php

namespace Tests\Feature;

use App\Imports\CreativeEconomyGroupImporter;
use App\Models\CreativeEconomyGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;
use Tests\TestCase;

class CreativeEconomyGroupImportTest extends TestCase
{
    use RefreshDatabase;

    private array $temporaryFiles = [];

    protected function tearDown(): void
    {
        foreach ($this->temporaryFiles as $temporaryFile) {
            if (file_exists($temporaryFile)) {
                unlink($temporaryFile);
            }
        }

        parent::tearDown();
    }

    public function test_imports_excel_rows_using_headers_and_normalizes_categories(): void
    {
        $file = $this->makeSpreadsheet([
            [
                [null, null, null, null, null],
                ['SUBSEKTOR', 'DISTRIK', 'LABEL OAP/NON-OAP', 'KELOMPOK', 'KETUA (SUMBER)'],
                ['Kriya', 'Merauke', 'OAP (LABEL KONSISTEN)', 'Maju Bersama', 'Yanuaria Arumua'],
            ],
            [
                [null, null, null, null, null],
                ['KELOMPOK', 'LABEL OAP/NON-OAP', 'KETUA (SUMBER)', 'SUBSEKTOR', 'DISTRIK'],
                ['Anyaman Indah', 'NON OAP (LABEL KONSISTEN)', 'Maria Gebze', 'Kuliner', 'Semangga'],
            ],
        ]);

        $importedCount = app(CreativeEconomyGroupImporter::class)->import($file, 2026);

        $this->assertSame(2, $importedCount);
        $this->assertDatabaseHas(CreativeEconomyGroup::class, [
            'year' => 2026,
            'group_name' => 'Maju Bersama',
            'classification' => 'OAP',
            'district' => 'Merauke',
        ]);
        $this->assertDatabaseHas(CreativeEconomyGroup::class, [
            'group_name' => 'Anyaman Indah',
            'classification' => 'Non-OAP',
            'subsector' => 'Kuliner',
        ]);
    }

    public function test_reimport_updates_existing_group_instead_of_creating_a_duplicate(): void
    {
        CreativeEconomyGroup::create([
            'year' => 2026,
            'group_name' => 'Maju Bersama',
            'classification' => 'OAP',
            'subsector' => 'Kriya',
        ]);

        $file = $this->makeSpreadsheet([
            ['KELOMPOK', 'LABEL OAP/NON-OAP', 'KETUA (SUMBER)', 'SUBSEKTOR', 'DISTRIK'],
            ['Maju Bersama', 'Non-OAP', 'Ketua Baru', 'Kuliner', 'Merauke'],
        ]);

        $importedCount = app(CreativeEconomyGroupImporter::class)->import($file, 2026);

        $this->assertSame(1, $importedCount);
        $this->assertDatabaseCount('creative_economy_groups', 1);
        $this->assertDatabaseHas(CreativeEconomyGroup::class, [
            'group_name' => 'Maju Bersama',
            'classification' => 'Non-OAP',
            'leader_source' => 'Ketua Baru',
            'subsector' => 'Kuliner',
        ]);
    }

    public function test_invalid_category_rejects_the_whole_file(): void
    {
        $file = $this->makeSpreadsheet([
            ['KELOMPOK', 'LABEL OAP/NON-OAP', 'KETUA (SUMBER)', 'SUBSEKTOR', 'DISTRIK'],
            ['Valid Group', 'OAP', 'Ketua', 'Kriya', 'Merauke'],
            ['Invalid Group', 'Other', 'Ketua', 'Kuliner', 'Semangga'],
        ]);

        try {
            app(CreativeEconomyGroupImporter::class)->import($file, 2026);
            $this->fail('The import should reject an unsupported category.');
        } catch (ValidationException) {
            $this->assertDatabaseCount('creative_economy_groups', 0);
        }
    }

    public function test_public_page_shows_group_data_and_doughnut_chart(): void
    {
        CreativeEconomyGroup::create([
            'year' => 2026,
            'group_name' => 'Maju Bersama',
            'classification' => 'OAP',
            'leader_source' => 'Yanuaria Arumua',
            'subsector' => 'Kriya',
            'district' => 'Semangga',
        ]);

        $response = $this->get(route('statistik.ekraf', ['tahun' => 2026]));

        $response->assertOk()
            ->assertSee('Data Kelompok Ekonomi Kreatif (2026)')
            ->assertSee('Maju Bersama')
            ->assertSee('OAP')
            ->assertSee('ekrafGroupDoughnut');
    }

    private function makeSpreadsheet(array $rows): string
    {
        $filePath = tempnam(sys_get_temp_dir(), 'ekraf-import-');
        $this->temporaryFiles[] = $filePath;

        $writer = new Writer;
        $writer->openToFile($filePath);
        $sheets = isset($rows[0][0]) && is_array($rows[0][0]) ? $rows : [$rows];

        foreach ($sheets as $sheetIndex => $sheetRows) {
            if ($sheetIndex > 0) {
                $writer->addNewSheetAndMakeItCurrent();
            }

            foreach ($sheetRows as $row) {
                $writer->addRow(Row::fromValues($row));
            }
        }

        $writer->close();

        return $filePath;
    }
}
