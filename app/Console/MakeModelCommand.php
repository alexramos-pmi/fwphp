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
        $name = $input->getArgument('name');

        // Quebra o caminho por "/" ou "\"
        $pathParts = explode('/', str_replace('\\', '/', $name));
        $className = ucfirst(array_pop($pathParts));
        $namespacePath = implode('\\', array_map('ucfirst', $pathParts));
        $directoryPath = implode(DIRECTORY_SEPARATOR, array_map('ucfirst', $pathParts));

        $baseDir = __DIR__ . '/../Models';
        $fullDir = $baseDir . ($directoryPath ? DIRECTORY_SEPARATOR . $directoryPath : '');
        $filePath = $fullDir . DIRECTORY_SEPARATOR . $className . '.php';

        // Cria os diretórios, se necessário
        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0777, true);
        }

        // Verifica se o model já existe
        if (file_exists($filePath)) {
            $output->writeln("<error>O model '{$className}' já existe em '{$fullDir}'.</error>");
            return Command::FAILURE;
        }

        // Define o namespace correto
        $namespace = 'App\\Models' . ($namespacePath ? '\\' . $namespacePath : '');

        // Conteúdo do arquivo
        $content = <<<PHP
        <?php

        namespace {$namespace};

        class {$className}
        {
            // Adicione atributos e métodos aqui
        }
        PHP;

        // Remove espaços antes do <?php
        $content = ltrim($content);

        // Salva o arquivo
        file_put_contents($filePath, $content);

        $output->writeln("<info>Model {$className} criado com sucesso em {$filePath}.</info>");

        return Command::SUCCESS;
    }
}
