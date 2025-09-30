<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Menu>
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function save(Menu $entity )
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @return Menu[] Returns an array of Menu objects
     */
    public function getMenus(): array
    {
        $qb =  $this->createQueryBuilder('m')
            ->select('m.id, m.name, m.position, m.slug, m.active')
            ->where('m.id = m.parent')
            ->getQuery();

            return $qb->getResult()
        ;
    }

}
