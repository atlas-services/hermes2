<?php
/**
 * Created by PhpStorm.
 * User: atlas
 * Date: 09/03/20
 * Time: 15:46
 */

namespace App\Upload\Namer;

use App\Entity\Config ;
use App\Entity\Post;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Symfony\Component\Filesystem\Filesystem;

class ConventionedDirectoryNamer implements DirectoryNamerInterface
{
    protected $directoryNamerLogger;
    protected $filesystem;
    protected $params;

    public function __construct(LoggerInterface $directoryNamerLogger, Filesystem $filesystem, ParameterBagInterface $params)
    {
        $this->directoryNamerLogger = $directoryNamerLogger;
        $this->filesystem = $filesystem;
        $this->params = $params;
    }

    public function directoryName($object, PropertyMapping $mapping): string
    {
        $notification = 'Upload image';
        try {
            $className = (new \ReflectionClass($object))->getShortName();
            if ('Menu' == $className) {
                if (in_array('getCode', get_class_methods($object))) {
                    $path = $object->getCode() . '/';
                    return $path;
                }
            }
            if (in_array('getMenu', get_class_methods($object))) {
                if ('Menu' == $className) {
                    $path = $object->getMenu()->getCode() . '/' . $object->getCode() . '/' ;
                }else{
                    $path = $object->getMenu()->getCode() . '/' . $className . '/';
                }
                return $path;
            }
            if($object instanceof Post){
                $path = 'menu';
                if(!is_null($object->getMenu())){
                    $menu_code = $object->getMenu()->getCode();
                    $menu_id = $object->getMenu()->getId();
                    if('' == $menu_id){
    //                    dd($object);
                    }
    //                $path = $className.'/'.$menu_code.'/' ;
                    $path = 'menu'. $menu_id.'/'.$menu_code.'/';
                }
                return $path;
            }

            if($object instanceof Config){
                $path = $className.'/' ;
                return $path;
            }

            $path = $className.'/'.$object->getId().'/' ;

            return $path;
        } catch (\Exception $e) {
            $logContext = [
                'statut' => 'ko',
                'exception' => $e->getMessage(),
            ];
            $this->directoryNamerLogger->alert($notification, $logContext);
        }
    }

}
