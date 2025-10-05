<?php

namespace App\Command;

use App\Entity\Template;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class InitHermesTemplatesCommand extends Command
{
    protected static $defaultName = 'app:init-hermes-templates';

    public function __construct(private EntityManagerInterface $entityManager, private ParameterBagInterface $params,)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(static::$defaultName)
            ->setDescription('Initiate Hermes Templates.');
      }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $nb = $this->initTemplates();

        $output->writeln(sprintf(" %s Templates created successfully ", $nb));

        return Command::SUCCESS;
    }

    public function initTemplates() : int
    {
        $nb = 0;
        $templates = $this->params->get('templates');

        if (!$templates) {
            throw new InvalidArgumentException('No templates configured.');
        }

        foreach($templates as $template){
            $db_template = $this->entityManager->getRepository(Template::class)->findOneBy(['code' => $template['code']]);
            if(is_null($db_template)){
                // Créer  template
                $newTemplate = new Template();
                $newTemplate->setType($template['type']);
                $newTemplate->setCode($template['code']);
                $newTemplate->setName($template['name']);
                $newTemplate->setSummary($template['summary']);

                $this->entityManager->persist($newTemplate);
                $nb++;
            }
        }

        $this->entityManager->flush();
        return $nb;
    }
}
