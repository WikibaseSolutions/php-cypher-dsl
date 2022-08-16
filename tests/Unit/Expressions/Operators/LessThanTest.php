<?php

/*
 * Cypher DSL
 * Copyright (C) 2021  Wikibase Solutions
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace WikibaseSolutions\CypherDSL\Tests\Unit\Expressions\Operators;

use PHPUnit\Framework\TestCase;
use TypeError;
use WikibaseSolutions\CypherDSL\Expressions\Operators\LessThan;
use WikibaseSolutions\CypherDSL\Types\AnyType;
use WikibaseSolutions\CypherDSL\Expressions\Literals\Integer;

/**
 * @covers \WikibaseSolutions\CypherDSL\Expressions\Operators\LessThan
 */
class LessThanTest extends TestCase
{

    public function testToQuery(): void
    {
        $lessThan = new LessThan(new Integer(10), new Integer(15));

        $this->assertSame("(10 < 15)", $lessThan->toQuery());
    }

    public function testToQueryNoParentheses(): void
    {
        $lessThan = new LessThan(new Integer(10), new Integer(15), false);

        $this->assertSame("10 < 15", $lessThan->toQuery());
    }

    public function testCannotBeNested(): void
    {
        $lessThan = new LessThan(new Integer(10), new Integer(15));

        $this->expectException(TypeError::class);

        $lessThan = new LessThan($lessThan, $lessThan);

        $this->assertSame("((10 < 15) < (10 < 15))", $lessThan->toQuery());
    }

    public function testDoesNotAcceptAnyTypeAsOperands(): void
    {
        $this->expectException(TypeError::class);

        $lessThan = new LessThan($this->createMock(AnyType::class), $this->createMock(AnyType::class));

        $lessThan->toQuery();
    }
}
