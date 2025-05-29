<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeRouteCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('make:route')
            ->setDescription('Adiciona uma nova rota ao sistema')
            ->addArgument('method', InputArgument::REQUIRED, 'Método HTTP (get, post, put, delete...)')
            ->addArgument('uri', InputArgument::REQUIRED, 'URI da rota (ex: /usuarios)')
            ->addArgument('controller', InputArgument::REQUIRED, 'Controller@metodo (ex: UsuarioController@index)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $method = strtolower($input->getArgument('method'));
        $uri = $input->getArgument('uri');
        $controllerAction = $input->getArgument('controller');
        $routeLine = "Route::{$method}('{$uri}', '{$controllerAction}');";

        $routesPath = __DIR__ . '/../../routes';
        $filePath = $routesPath . '/web.php';

        if (!is_dir($routesPath)) {
            mkdir($routesPath, 0777, true);
        }

        // Evita duplicatas simples
        if (file_exists($filePath) && str_contains(file_get_contents($filePath), $routeLine)) {
            $output->writeln("<comment>Rota já existe em web.php.</comment>");
            return Command::SUCCESS;
        }

        file_put_contents($filePath, $routeLine . PHP_EOL, FILE_APPEND);
        $output->writeln("<info>Rota adicionada: {$routeLine}</info>");

        return Command::SUCCESS;
    }
}
