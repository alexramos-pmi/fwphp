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
        $name = ucfirst($input->getArgument('name'));
        $directory = __DIR__ . '/../Http/Middleware';
        $filePath = "{$directory}/{$name}.php";

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        if (file_exists($filePath)) {
            $output->writeln("<error>O middleware '{$name}' já existe.</error>");
            return Command::FAILURE;
        }

        $content = <<<PHP
<?php

namespace App\Http\Middleware;

class {$name}
{
    public function handle(\$request, \Closure \$next)
    {
        // Lógica do middleware aqui

        return \$next(\$request);
    }
}
PHP;

        file_put_contents($filePath, $content);
        $output->writeln("<info>Middleware {$name} criado com sucesso.</info>");

        return Command::SUCCESS;
    }
}