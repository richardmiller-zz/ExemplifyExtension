<?php

namespace RMiller\ExemplifyExtension\Generator;

use PhpSpec\CodeGenerator\Generator\GeneratorInterface;
use PhpSpec\Console\IO;
use PhpSpec\CodeGenerator\TemplateRenderer;
use PhpSpec\Util\Filesystem;
use PhpSpec\Locator\ResourceInterface;
use RMiller\Caser\Cased;

/**
 * Generates class methods from a resource
 */
class SpecificationMethodGenerator implements GeneratorInterface
{
    /**
     * @var \PhpSpec\Console\IO
     */
    private $io;

    /**
     * @var \PhpSpec\CodeGenerator\TemplateRenderer
     */
    private $templates;

    /**
     * @var \PhpSpec\Util\Filesystem
     */
    private $filesystem;

    /**
     * @param IO               $io
     * @param TemplateRenderer $templates
     * @param Filesystem       $filesystem
     */
    public function __construct(IO $io, TemplateRenderer $templates, Filesystem $filesystem = null)
    {
        $this->io         = $io;
        $this->templates  = $templates;
        $this->filesystem = $filesystem ?: new Filesystem();
    }

    /**
     * @param ResourceInterface $resource
     * @param string            $generation
     * @param array             $data
     *
     * @return bool
     */
    public function supports(ResourceInterface $resource, $generation, array $data)
    {
        return 'specification_method' === $generation;
    }

    /**
     * @param ResourceInterface $resource
     * @param array             $data
     */
    public function generate(ResourceInterface $resource, array $data = array())
    {
        $filepath  = $resource->getSpecFilename();
        $method    = Cased::fromCamelCase($data['method']);

        $values = [
            '%method%' => $method->asCamelCase(),
            '%example_name%' => 'it_should_'.$method->asSnakeCase(),
        ];
        if (!$content = $this->templates->render('method', $values)) {
            $content = $this->templates->renderString(
                $this->getTemplate(), $values
            );
        }

        $code = $this->filesystem->getFileContents($filepath);
        $code = preg_replace('/}[ \n]*$/', rtrim($content)."\n}\n", trim($code));
        $this->filesystem->putFileContents($filepath, $code);

        $this->io->writeln(sprintf(
            "\nExample for <info>Method <value>%s::%s()</value> has been created.</info>",
            $resource->getSrcClassname(), $method->asCamelCase()
        ), 2);
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 0;
    }

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return file_get_contents(__DIR__.'/templates/specification_method.template');
    }
}
