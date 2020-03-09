<?php
declare(strict_types=1);

namespace App\ReadModel\Ad;

use App\Entity\Ad;
use App\ReadModel\Ad\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;

class AdFetcher
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Filter $filter
     * @return array[]
     */
    public function all(Filter $filter): array
    {
       $qb = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'date',
                'title',
                'country',
            )
            ->from('ad_ads');
        
       if ($filter->title) {
            $qb->andWhere($qb->expr()->like('title', ':title'));
            $qb->setParameter(':title', '%' . mb_strtolower($filter->title) . '%');
        }
    
        if ($filter->date) {
            $qb->andWhere('date = :date');
            $qb->setParameter(':date', $filter->date);
        }
    
        if ($filter->country) {
            $qb->andWhere($qb->expr()->like('country', ':country'));
            $qb->setParameter(':country', '%' . mb_strtolower($filter->country) . '%');
        }
        
        return $qb->execute()->fetchAll(FetchMode::ASSOCIATIVE);
    }
}