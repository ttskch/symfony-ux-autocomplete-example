<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class UserAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => User::class,
            'placeholder' => 'Please select',
            'filter_query' => function (QueryBuilder $qb, string $query) {
                $queries = explode(' ', strval(preg_replace('/\s+/', ' ', trim($query))));
                $qb
                    ->leftJoin('entity.team', 't')
                    ->addSelect('t')
                ;
                $conditions = [];
                foreach ($queries as $i => $query) {
                    $conditions[] = sprintf('t.name LIKE :query%d OR entity.name LIKE :query%d', $i, $i);
                    $qb->setParameter(sprintf('query%d', $i), '%'.str_replace('%', '\%', $query).'%');
                }
                $qb->andWhere($qb->expr()->andX(...$conditions));
            },
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
