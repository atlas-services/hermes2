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
            ->where('m.id = m.parent')
            ->orderBy('m.position')
            ->getQuery();

            return $qb->getResult()
        ;
    }

    /**
     * @return position  Returns an integer 
     */
    public function getLastMenuPosition(): int
    {
        $qb =  $this->createQueryBuilder('m')
            ->select('Max(m.position)')
            ->where('m.id = m.parent')
            ->getQuery();

        $position = array_values($qb->getOneOrNullResult())[0] ;
        return (int)$position;
    }


    /**
     * @return Menu[] Returns an array of Menu objects
     */
    public function getSubMenus($menu): array
    {
        $qb =  $this->createQueryBuilder('m')
            ->where('m.parent != m.id')
            ->andWhere('m.parent = :menu')
            ->setParameter('menu', $menu)
            ->orderBy('m.position')
            ->getQuery();

            return $qb->getResult()
        ;
    }

    /**
     * @return position  Returns an integer 
     */
    public function getLastSubMenuPosition($menu): int
    {
        $qb =  $this->createQueryBuilder('m')
            ->select('Max(m.position)')
            ->where('m.parent = :menu')
            ->setParameter('menu', $menu)
            ->getQuery();

        $position = array_values($qb->getOneOrNullResult())[0] ;
        return (int)$position;
    }




}
