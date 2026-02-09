<?php

namespace Tests\Feature;

use App\Models\Fakultet;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StudentCrudTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function moze_kreirati_studenta(): void
    {
        $f = Fakultet::create([
            'naziv' => 'FER',
            'mjesto' => 'Zagreb',
        ]);

        $student = Student::create([
            'ime' => 'Marko',
            'prezime' => 'Marić',
            'datum_rod' => '2000-01-01',
            'mbr' => 10,
            'stipendija' => 100,
            'mjesto' => 'Split', // ⬅️ bitno: NE null
            'fakultetid' => $f->id,
        ]);

        $this->assertDatabaseHas('studenti', [
            'id' => $student->id,
            'ime' => 'Marko',
        ]);
    }

    #[Test]
    public function moze_azurirati_studenta(): void
    {
        $f = Fakultet::create(['naziv' => 'FER', 'mjesto' => 'Zagreb']);

        $student = Student::create([
            'ime' => 'Ivo',
            'prezime' => 'Ivić',
            'datum_rod' => '2000-01-01',
            'mbr' => 20,
            'stipendija' => 0,
            'mjesto' => 'Rijeka',
            'fakultetid' => $f->id,
        ]);

        $student->update(['stipendija' => 250]);

        $this->assertDatabaseHas('studenti', [
            'id' => $student->id,
            'stipendija' => 250,
        ]);
    }

    #[Test]
    public function moze_obrisati_studenta(): void
    {
        $f = Fakultet::create(['naziv' => 'PMF', 'mjesto' => 'Zagreb']);

        $student = Student::create([
            'ime' => 'Ana',
            'prezime' => 'Anić',
            'datum_rod' => '2001-01-01',
            'mbr' => 30,
            'stipendija' => 0,
            'mjesto' => 'Zagreb',
            'fakultetid' => $f->id,
        ]);

        $student->delete();

        $this->assertDatabaseMissing('studenti', [
            'id' => $student->id,
        ]);
    }
}