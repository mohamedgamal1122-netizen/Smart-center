<?php
namespace Database\Factories;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
class StudentFactory extends Factory
{
    protected $model = Student::class;
    public function definition(): array
    {
        return [
            'code' => 'STU'.fake()->unique()->numerify('####'),
            'name' => fake('ar_EG')->name(),
            'grade' => 'الصف الأول',
            'status' => 'active',
            'enrollment_date' => now()->format('Y-m-d'),
        ];
    }
}
