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

        // Converte caminhos do tipo Relatorios/RelBoletimController
        $pathParts = explode('/', str_replace('\\', '/', $name));
        $className = array_pop($pathParts); // Pega o nome da classe
        $namespacePath = implode('\\', $pathParts); // Para o namespace
        $directoryPath = implode(DIRECTORY_SEPARATOR, $pathParts); // Para o sistema de arquivos

        $baseDir = __DIR__ . '/../Http/Controllers';
        $fullDir = $baseDir . ($directoryPath ? DIRECTORY_SEPARATOR . $directoryPath : '');
        $filePath = $fullDir . DIRECTORY_SEPARATOR . $className . '.php';

        // Cria diretórios se necessário
        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0777, true);
        }

        // Verifica existência
        if (file_exists($filePath)) {
            $output->writeln("<error>O controller '{$className}' já existe em '{$fullDir}'.</error>");
            return Command::FAILURE;
        }

        // Namespace final
        $namespace = 'App\\Http\\Controllers' . ($namespacePath ? '\\' . $namespacePath : '');

        // Criação do conteúdo, com ltrim para garantir que <?php esteja no topo
        $content = <<<PHP
        <?php

        namespace {$namespace};

        class {$className}
        {
            public function index()
            {
                echo "Olá do controller {$className}!";
            }
        }
        PHP;

        // Remove qualquer espaço ou linha antes do <?php
        $content = ltrim($content);

        // Escreve no arquivo com codificação limpa
        file_put_contents($filePath, $content);

        $output->writeln("<info>Controller {$className} criado com sucesso em {$filePath}.</info>");

        return Command::SUCCESS;
    }
}
