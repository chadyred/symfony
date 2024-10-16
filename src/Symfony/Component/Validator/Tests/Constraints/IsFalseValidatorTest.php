<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Validator\Tests\Constraints;

use Symfony\Component\Validator\Constraints\IsFalse;
use Symfony\Component\Validator\Constraints\IsFalseValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class IsFalseValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator()
    {
        return new IsFalseValidator();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new IsFalse());

        $this->assertNoViolation();
    }

    public function testFalseIsValid()
    {
        $this->validator->validate(false, new IsFalse());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider provideInvalidConstraints
     */
    public function testTrueIsInvalid(IsFalse $constraint)
    {
        $this->validator->validate(true, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', 'true')
            ->setCode(IsFalse::NOT_FALSE_ERROR)
            ->assertRaised();
    }

    public static function provideInvalidConstraints(): iterable
    {
        yield 'Doctrine style' => [new IsFalse([
            'message' => 'myMessage',
        ])];

        if (\PHP_VERSION_ID >= 80000) {
            yield 'named parameters' => [eval('return new \Symfony\Component\Validator\Constraints\IsFalse(message: "myMessage");')];
        }
    }
}
