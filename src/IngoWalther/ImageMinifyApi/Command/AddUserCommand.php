<?php

namespace IngoWalther\ImageMinifyApi\Command;

use IngoWalther\ImageMinifyApi\Security\ApiKeyGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class AddUserCommand extends Command
{
    /**
     * @var ApiKeyGenerator
     */
    private $apiKeyGenerator;

    /**
     * @param ApiKeyGenerator $apiKeyGenerator
     */
    public function setApiKeyGenerator($apiKeyGenerator)
    {
        $this->apiKeyGenerator = $apiKeyGenerator;
    }


    protected function configure()
    {
        $this
            ->setName('user:add')
            ->setDescription('Creates a new User/API-Key')
            ->addArgument('name', InputArgument::OPTIONAL, 'Username?')
            ->addArgument('quotaPerMonth', InputArgument::OPTIONAL, 'Quota Per Month')
            ->addArgument('quotaPerDay', InputArgument::OPTIONAL, 'Quota Per Day')
            ->addArgument('quotaPerHour', InputArgument::OPTIONAL, 'Quota Per Hour');

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if(!$this->apiKeyGenerator) {
            throw new \LogicException('ApiKeyGenerator is not set!');
        }

        $quotaParams = [];

        $name = $input->getArgument('name');
        $quotaParams['month'] = $input->getArgument('quotaPerMonth');
        $quotaParams['day'] = $input->getArgument('quotaPerDay');
        $quotaParams['hour'] = $input->getArgument('quotaPerHour');

        if(!$name) {
            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');
            $question = new Question('<question>Username?</question> ');

            $name = $helper->ask($input, $output, $question);
        }

        // Ask Quota Per Month
        if(!$quotaParams['month']) {
            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');
            $question = new Question('<question>Quota Per Month?</question> ');

            $quotaParams['month'] = $helper->ask($input, $output, $question);
        }

        // Ask Quota Per Day
        if(!$quotaParams['day']) {
            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');
            $question = new Question('<question>Quota Per Day?</question> ');

            $quotaParams['day'] = $helper->ask($input, $output, $question);
        }

        // Ask Quota Per Hour
        if(!$quotaParams['hour']) {
            /** @var QuestionHelper $helper */
            $helper = $this->getHelper('question');
            $question = new Question('<question>Quota Per Hour?</question> ');

            $quotaParams['hour'] = $helper->ask($input, $output, $question);
        }

        $key = $this->apiKeyGenerator->generate($name, $quotaParams);

        $output->writeln(sprintf('<info>User "%s" succesfully created</info>', $name));
        $output->writeln(sprintf('<info>API-Key: %s</info>', $key));
        $output->writeln(sprintf(
            '<info>Quota per Month, Day, Hour: %s, %s, %s</info>',
            $quotaParams['month'], $quotaParams['day'], $quotaParams['hour'])
        );
    }
}