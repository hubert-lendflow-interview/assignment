<?php

namespace Tests\Unit;

use App\Rules\IsbnCollection;
use PHPUnit\Framework\TestCase;

class IsbnCollectionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_correct_isbn_array()
    {
        $rule = new IsbnCollection();
        $this->assertTrue($rule->passes(['9780399178573'], 'test'));
        $this->assertTrue($rule->passes(['9780399178573', '9780671003548'], 'test'));
        $this->assertTrue($rule->passes(['9780399178573', '9780399178573'], 'test'));
    }

    public function test_array_with_incorrect_values()
    {
        $rule = new IsbnCollection();
        $this->assertFalse($rule->passes(['test'], 'test'));
        $this->assertFalse($rule->passes(['9780399178573', 'test'], 'test'));
        $this->assertFalse($rule->passes(['test', '9780399178573'], 'test'));
        $this->assertFalse($rule->passes(['978039917857'], 'test'));
        $this->assertFalse($rule->passes(['978039917857a'], 'test'));
    }
}
