<?php

namespace Tests\Unit;

use App\Rules\DividesBy;
use PHPUnit\Framework\TestCase;

class DividesByTest extends TestCase
{
    public function test_passing()
    {
        $rule = new DividesBy(20);
        $this->assertTrue($rule->passes(20));
        $this->assertTrue($rule->passes(0));
        $this->assertTrue($rule->passes(80));
        $this->assertTrue($rule->passes(-20));
    }

    public function test_failing()
    {
        $rule = new DividesBy(20);
        $this->assertFalse($rule->passes(1));
        $this->assertFalse($rule->passes(-1));
        $this->assertFalse($rule->passes(7.7));
    }
}
