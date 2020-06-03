<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Mailer\Doctrine\Repository\Traits;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Translatable\Query\TreeWalker\TranslationWalker;
use Gedmo\Translatable\Translatable;
use Gedmo\Translatable\TranslatableListener;
use Klipper\Component\Mailer\Model\TemplateMessageInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @method QueryBuilder createQueryBuilder($alias, $indexBy = null)
 * @method string       getClassName()
 */
trait TemplateMessageRepositoryTrait
{
    /**
     * @see TemplateMessageRepositoryInterface::findTemplate()
     *
     * @throws
     */
    public function findTemplate(string $name, ?string $type = null, ?string $locale = null): ?TemplateMessageInterface
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.name = :name')
            ->andWhere('t.enabled = true')
            ->setParameter('name', $name)
        ;

        if (null !== $type) {
            $qb->andWhere('t.type = :type')->setParameter('type', $type);
        }

        return $this->addTranslationFilter($qb->getQuery(), $locale)->getOneOrNullResult();
    }

    protected function addTranslationFilter(AbstractQuery $query, ?string $locale = null): AbstractQuery
    {
        if (class_exists(TranslationWalker::class) && is_a($this->getClassName(), Translatable::class, true)) {
            $locale = $locale ?? \Locale::getDefault();
            $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, TranslationWalker::class);
            $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale);
            $query->setHint(TranslatableListener::HINT_FALLBACK, 1);
        }

        return $query;
    }
}
