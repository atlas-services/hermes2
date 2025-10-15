<?php


namespace App\Service;

use App\Entity\Config;
use App\Entity\Contact;
use App\Entity\Menu;
use App\Entity\Interfaces\ContactInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PageService
{

    protected $doctrine;
    protected $parameterBag;
    protected $config;
    public function __construct(ManagerRegistry $doctrine, ParameterBagInterface $parameterBag)
    {
        $this->doctrine = $doctrine;
        $this->parameterBag = $parameterBag;
        $em = $doctrine->getManager();
        $this->config = $em->getRepository(Config::class)->findAll();
    }


    public function getSitemapByLocale($locale, $host="")
    {
        $em = $this->doctrine->getManager('default');
        $urls = $urls_xml = $urls_html = [];
        $menusLocale = $em->getRepository(Menu::class)
            ->findBy(['locale' => $locale])
            // ->getMenusByLocaleOrderByPosition($locale, true)
        ;
        foreach ($menusLocale as $key => $menu){
            $updated = $menu->getUpdatedAt();
            if(is_null($updated)){
                $updated = (new \DateTime("now"))->format('Y-m-d');
            }else{
                $updated = $menu->getUpdatedAt()->format('Y-m-d');
            }
            if($locale == $menu->getSheet()->getLocale()){
                $name = $menu->getName();
                $sheet_name = $menu->getSheet()->getName();
                if($menu->getSheet()->getSlug() == $menu->getSlug()){
                    $name = $menu->getSheet()->getName();
                    $loc = $host. '/'. $locale. '/'.$menu->getSheet()->getSlug();
                }else{
                    $loc = $host. '/'. $locale. '/'.$menu->getSheet()->getSlug(). '/' . $menu->getSlug();                
                }
                // homepage
                if(0 == $key){
                    $loc = $host. '/'. $locale;                
                }
                $urls_xml[] = [
                    'name' => $name,
                    'sheetname' => $sheet_name,
                    'loc' => $loc,
                    'lastmod' => $updated,
                    'changefreq' => 'weekly',
                    'priority' => '0.5',
                ];
                $urls_html[$menu->getSheet()->getSlug()][] = [
                    'name' => $name,
                    'sheetname' => $sheet_name,
                    'loc' => $loc,
                    'lastmod' => $updated,
                    'changefreq' => 'weekly',
                    'priority' => '0.5',
                ];
            }
        }
        $urls['xml'] = $urls_xml;
        $urls['html'] = $urls_html;
        return $urls;
    }

    public function getCacheMenu($sheet, $slug)
    {
        $em = $this->doctrine->getManager('default');
        $menu = $em->getRepository(Menu::class)->getMyMenuBySheetAndMenuSlugs($sheet, $slug);
//        // cache remote pictures
        $cache = [];
        if(!is_null($menu)){
//            // cache menu
//            if(!is_null($menu->getSheet()->getUpdatedAt())) {
//                $cache['front_cache'] = 'front_cache_sheet' . $sheet . $slug . $menu->getSheet()->getUpdatedAt()->format('Y-m-d-H-i-s');
//            }
//            // cache sous-menu
//            if(!is_null($menu->getUpdatedAt())) {
//                $cache['front_cache'] = 'front_cache_menu' . $sheet . $slug . $menu->getUpdatedAt()->format('Y-m-d-H-i-s');
//            }
//            //cache section
            foreach ($menu->getSections() as $section){
//                // cache Posts
//                foreach ($section->getPosts() as $post){
//                    if(!is_null($post->getUpdatedAt())) {
//                        $cache['front_cache'] = 'front_cache_post' . $sheet . $slug . $post->getUpdatedAt()->format('Y-m-d-H-i-s');
//                    }
//                }
            }
        }

        return $cache;
    }

    public function getLocale($locale)
    {
        $em = $this->doctrine->getManager('default');

        $locales = $em->getRepository(Menu::class)->findBy(['locale' =>$locale]);

        if(empty($locales)){
            $locale = $this->parameterBag->get('app.default_locale');
            return $locale;
        }
        $intl = \IntlCalendar::getAvailableLocales();

        if( !in_array($locale, $intl)){
            $locale = $this->parameterBag->get('app.default_locale');
        }
        return $locale;
    }

}
