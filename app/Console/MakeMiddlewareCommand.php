<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeMiddlewareCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('make:middleware')
            ->setDescription('Cria um novo middleware')
            ->addArgument('name', InputArgument::REQUIRED, 'Nome do middleware');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        // Divide o nome em partes: Admin/AuthMiddleware -> ['Admin', 'AuthMiddleware']
        $pathParts = explode('/', str_replace('\\', '/', $name));
        $className = ucfirst(array_pop($pathParts));
        $namespacePath = implode('\\', array_map('ucfirst', $pathParts));
        $directoryPath = implode(DIRECTORY_SEPARATOR, array_map('ucfirst', $pathParts));

        $baseDir = __DIR__ . '/../Http/Middleware';
        $fullDir = $baseDir . ($directoryPath ? DIRECTORY_SEPARATOR . $directoryPath : '');
        $filePath = $fullDir . DIRECTORY_SEPARATOR . $className . '.php';

        // Cria os diretórios necessários
        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0777, true);
        }

        // Verifica se já existe
        if (file_exists($filePath)) {
            $output->writeln("<error>O middleware '{$className}' já existe em '{$fullDir}'.</error>");
            return Command::FAILURE;
        }

        // Define o namespace correto
        $namespace = 'App\\Http\\Middleware' . ($namespacePath ? '\\' . $namespacePath : '');

        // Conteúdo do middleware
        $content = <<<PHP
        <?php

        namespace {$namespace};

        class {$className}
        {
            public function handle(\$request, \Closure \$next)
            {
                // Lógica do middleware aqui

                return \$next(\$request);
            }
        }
        PHP;

        $content = ltrim($content); // Garante que <?php esteja no topo

        // Salva o arquivo
        file_put_contents($filePath, $content);

        $output->writeln("<info>Middleware {$className} criado com sucesso em {$filePath}.</info>");

        return Command::SUCCESS;
    }
}