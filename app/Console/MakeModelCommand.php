<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeModelCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('make:model')
            ->setDescription('Cria um novo model')
            ->addArgument('name', InputArgument::REQUIRED, 'Nome do model');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = ucfirst($input->getArgument('name'));
        $filePath = __DIR__ . "/../Models/{$name}.php";

        if (file_exists($filePath)) {
            $output->writeln("<error>O model '{$name}' já existe.</error>");
            return Command::FAILURE;
        }

        $content = <<<PHP
<?php

namespace App\Models;

class {$name}
{
    // Adicione atributos e métodos aqui
}
PHP;

        file_put_contents($filePath, $content);
        $output->writeln("<info>Model {$name} criado com sucesso.</info>");

        return Command::SUCCESS;
    }
}
