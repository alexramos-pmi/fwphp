<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeControllerCommand extends Command
{
    protected static $defaultName = 'make:controller';

    protected function configure()
    {
        $this
            ->setName('make:controller') // ESSENCIAL!
            ->setDescription('Cria um novo controller')
            ->addArgument('name', InputArgument::REQUIRED, 'Nome do controller');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        $className = ucfirst($name);
        $filePath = __DIR__ . "/../Http/Controllers/{$className}.php";

        if (file_exists($filePath)) {
            $output->writeln("<error>O controller '{$className}' já existe.</error>");
            return Command::FAILURE;
        }

        $content = <<<PHP
<?php

namespace App\Http\Controllers;

class {$className}
{
    public function index()
    {
        echo "Olá do controller {$className}!";
    }
}
PHP;

        file_put_contents($filePath, $content);
        $output->writeln("<info>Controller {$className} criado com sucesso.</info>");

        return Command::SUCCESS;
    }
}
