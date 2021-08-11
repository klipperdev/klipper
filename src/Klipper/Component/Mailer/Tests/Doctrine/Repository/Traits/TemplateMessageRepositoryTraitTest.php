<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Mailer\Tests\Doctrine\Repository\Traits;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Translatable\Query\TreeWalker\TranslationWalker;
use Gedmo\Translatable\TranslatableListener;
use Klipper\Component\Mailer\Doctrine\Repository\Traits\TemplateMessageRepositoryTrait;
use Klipper\Component\Mailer\Tests\Fixtures\Mock\TemplateMessage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class TemplateMessageRepositoryTraitTest extends TestCase
{
    /**
     * @throws
     */
    public function testFindTemplate(): void
    {
        $expectedTemplateMessage = new TemplateMessage();

        $query = $this->getMockForAbstractClass(AbstractQuery::class, [], '', false, false, true, [
            'setHint',
            'getOneOrNullResult',
        ]);

        $checkSetHintCustomOutputWalker = false;
        $checkSetHintTranslatableLocale = false;
        $checkSetHintFallback = false;

        $query->expects(static::atLeast(3))
            ->method('setHint')
            ->willReturnCallback(static function ($name, $value) use (&$checkSetHintCustomOutputWalker, &$checkSetHintTranslatableLocale, &$checkSetHintFallback): void {
                if (Query::HINT_CUSTOM_OUTPUT_WALKER === $name && TranslationWalker::class === $value) {
                    $checkSetHintCustomOutputWalker = true;
                } elseif (TranslatableListener::HINT_TRANSLATABLE_LOCALE === $name && 'fr_FR' === $value) {
                    $checkSetHintTranslatableLocale = true;
                } elseif (TranslatableListener::HINT_FALLBACK === $name && 1 === $value) {
                    $checkSetHintFallback = true;
                }
            })
        ;

        $query->expects(static::once())
            ->method('getOneOrNullResult')
            ->willReturn($expectedTemplateMessage)
        ;

        $qb = $this->getMockBuilder(QueryBuilder::class)->disableOriginalConstructor()->getMock();

        $qb->expects(static::once())
            ->method('where')
            ->with('t.name = :name')
            ->willReturn($qb)
        ;
        $qb->expects(static::atLeast(2))
            ->method('andWhere')
            ->willReturn($qb)
        ;
        $qb->expects(static::atLeast(2))
            ->method('setParameter')
            ->willReturn($qb)
        ;
        $qb->expects(static::once())
            ->method('getQuery')
            ->willReturn($query)
        ;

        /** @var MockObject|TemplateMessageRepositoryTrait $trait */
        $trait = $this->getMockForTrait(TemplateMessageRepositoryTrait::class, [], '', false, false, true, [
            'createQueryBuilder',
            'getClassName',
        ]);

        $trait->expects(static::once())
            ->method('createQueryBuilder')
            ->with('t')
            ->willReturn($qb)
        ;

        $trait->expects(static::once())
            ->method('getClassName')
            ->willReturn(TemplateMessage::class)
        ;

        $res = $trait->findTemplate('template_name', 'template_type', 'fr_FR');

        static::assertTrue($checkSetHintCustomOutputWalker);
        static::assertTrue($checkSetHintTranslatableLocale);
        static::assertTrue($checkSetHintFallback);

        static::assertSame($expectedTemplateMessage, $res);
    }
}
