<?php

namespace App\Tests;

use App\Entity\Tutos;
use PHPUnit\Framework\TestCase;

class TutosTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = (new Tutos())
            ->setTitle('mon tutoriel true')
            ->setCreator('moi testeur')
            ->setUrl('https://www.monurl.com/youtubeid')
        ;

        $this->assertTrue($user->getTitle() === 'mon tutoriel true');
        $this->assertTrue($user->getCreator() === 'moi testeur');
        $this->assertTrue($user->getUrl() === 'https://www.monurl.com/youtubeid');
    }

    public function testIsFalse(): void
    {
        $user = (new Tutos())
            ->setTitle('mon tutoriel true')
            ->setCreator('moi testeur')
            ->setUrl('https://www.monurl.com/youtubeid')
        ;

        $this->assertFalse($user->getTitle() === 'mon tutoriel false');
        $this->assertFalse($user->getCreator() === 'un autre testeur');
        $this->assertFalse($user->getUrl() === 'https://www.pasmonurl.com/youtubeid');
    }

    public function testIsEmpty(): void
    {
        $user = new Tutos();

        $this->assertEmpty($user->getTitle());
        $this->assertEmpty($user->getCreator());
        $this->assertEmpty($user->getUrl());
    }
}
