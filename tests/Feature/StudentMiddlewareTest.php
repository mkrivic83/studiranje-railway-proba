<?php

namespace Tests\Feature;

use App\Models\Fakultet;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StudentMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    private function seedFakultet(): Fakultet
    {
        return Fakultet::create([
            'naziv' => 'FER',
            'mjesto' => 'Zagreb',
        ]);
    }

    #[Test]
    public function blokira_show_ako_student_nema_mjesto(): void
    {
        $f = $this->seedFakultet();

        $student = Student::create([
            'ime' => 'Null',
            'prezime' => 'Mjesto',
            'datum_rod' => '2000-01-01',
            'mbr' => 1,
            'stipendija' => 0,
            'mjesto' => 'null',
            'fakultetid' => $f->id,
        ]);

        $this->get(route('studenti.show', $student))
            ->assertStatus(403)
            ->assertSee('Prikaz studenta nije dozvoljen');
    }

    #[Test]
    public function dozvoljava_show_ako_student_ima_mjesto(): void
    {
        $f = $this->seedFakultet();

        $student = Student::create([
            'ime' => 'Vidljiv',
            'prezime' => 'Student',
            'datum_rod' => '2000-01-01',
            'mbr' => 2,
            'stipendija' => 0,
            'mjesto' => 'Split',
            'fakultetid' => $f->id,
        ]);

        $this->get(route('studenti.show', $student))
            ->assertStatus(200)
            ->assertSee('Vidljiv');
    }
}