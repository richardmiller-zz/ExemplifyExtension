<?php

namespace RMiller\ExemplifyExtension\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExemplifyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('exemplify')
            ->setDefinition(array(
                    new InputArgument('class', InputArgument::REQUIRED, 'Class method belongs to'),
                    new InputArgument('method', InputArgument::REQUIRED, 'Method to describe'),
                ))
            ->setDescription('Adds an example for a method')
            ->addOption('confirm', null, InputOption::VALUE_NONE, 'Ask for confirmation before creating example')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command creates an example for a method:

  <info>php %command.full_name% ClassName MethodName</info>

Will generate an example in the ClassNameSpec.

EOF
            )
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getApplication()->getContainer();
        $container->configure();

        $classname = $input->getArgument('class');
        $method = $input->getArgument('method');

        if (!$this->confirm($input, $container, $classname, $method)) {
            return;
        }

        $dialog = $this->getHelper('dialog');

        $resource = $container->get('locator.resource_manager')->createResource($classname);

        $container->get('code_generator')->generate($resource, 'specification_method', [
            'method' => $method,
            'type' => $this->confirmMethodType($output, $dialog),
        ]);
    }

    /**
     * @param InputInterface $input
     * @param $container
     * @param $classname
     * @param $method
     * @return bool
     */
    private function confirm(InputInterface $input, $container, $classname, $method)
    {
        if (!$input->getOption('confirm')) {
            return true;
        }

        $question = sprintf('Do you want to generate an example for %s::%s? (Y/n)', $classname, $method);

        if ($container->get('console.io')->askConfirmation($question, true)) {
            return true;
        }

        return false;
    }

    /**
     * @param OutputInterface $output
     * @param $dialog
     */
    protected function confirmMethodType(OutputInterface $output, $dialog)
    {
        $formattedMethodTypes = ['instance method','named constructor', 'static method'];
        $methodTypes = ['instance-method', 'named-constructor', 'static-method'];

        return $methodTypes[$dialog->select(
            $output,
            'Please select the method type (defaults to instance method)',
            $formattedMethodTypes,
            0
        )];
    }
}
