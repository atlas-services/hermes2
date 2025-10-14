<?php

namespace App\Repository;

use App\Entity\Menu;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry as RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }
    
    public function save(Post $entity )
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @return int Returns max position value
     */

    public function getMaxPosition(Menu $menu = null)
    {
        $qb = $this->getQbMaxPosition();
        if(isset($menu)) {
            $qb
            ->where('m.menu = :menu')
            ->setParameter(':menu', $menu->getId());
        }
        $list = $qb
            ->getQuery()
            ->getResult()
        ;
        if(isset($list[0])){
            return ++$list[0]['position'];
        }
        if(isset($list['position'])){
            return ++$list['position'];
        }
        return 1;
    }

        /**
     * @return position  Returns an integer 
     */
    public function getLastPostPosition(): int
    {
        $qb =  $this->createQueryBuilder('m')
            ->select('Max(m.position)')
            ->getQuery();

        $position = array_values($qb->getOneOrNullResult())[0] ;
        return (int)$position;
    }

    /**
     * @return int Returns max position value
     */

    public function switchActive(int $id)
    {

        $post = $this->findOneById($id);

        if($post->isActive()){
            $post->setActive(false);
        }else{
            $post->setActive(true);
        }
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();

        return $post;

    }


    /**
     * @return int Returns max position value
     */
     public function getEditablePosts()
     {
        $allposts = $this->createQueryBuilder('s')
        ->orderBy('s.menu', 'ASC')
        ->addOrderBy('s.position', 'ASC')
        ->addOrderBy('s.active', 'DESC')
        ->getQuery()
        ->getResult();
        // $allposts = $this->findAll();
        foreach($allposts as $key => $post){
            if(!is_null($post->getMenu())){
                // $posts[] = $post;
                $posts[$key]['id'] = $post->getId();
                $posts[$key]['active'] = $post->isActive();
                $posts[$key]['position'] = $post->getPosition();
                $posts[$key]['name'] = $post->getName();
                $posts[$key]['startPublishedAt'] = $post->getStartPublishedAt();
                $posts[$key]['endPublishedAt'] = $post->getEndPublishedAt();
                $posts[$key]['updatedAt'] = $post->getUpdatedAt();

                $posts[$key]['menu'] = $post->getMenu()->getName();
                $posts[$key]['menu_id'] = $post->getMenu()->getId();
                $posts[$key]['menu'] = $post->getMenu()->getMenu()->getName();
                $posts[$key]['template'] = $post->getMenu()->getTemplate()->getName();
                $posts[$key]['template_code'] = $post->getMenu()->getTemplate()->getCode();
                $posts[$key]['menu_id'] = $post->getMenu()->getMenu()->getId();
                $posts[$key]['menu_slug'] = $post->getMenu()->getMenu()->getSlug();
                $posts[$key]['locale'] = $post->getMenu()->getMenu()->getLocale();
                $posts[$key]['sheet'] = $post->getMenu()->getMenu()->getSheet()->getName();

            }
        }

         return array_values($posts);

     }

         /**
     * @return Post[] Returns an array of Menu objects
     */
    public function getPosts(): array
    {
        $qb =  $this->createQueryBuilder('m')
            ->orderBy('m.position')
            ->getQuery();

            return $qb->getResult()
        ;
    }


}
