<?php

/**
 * Copyright 2024-2026 Firstruner and Contributors
 * Firstruner is an Registered Trademark & Property of Christophe BOULAS
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Freemium License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@firstruner.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit, reproduce ou modify this file.
 * Please refer to https://firstruner.fr/ or contact Firstruner for more information.
 *
 * @author    Firstruner and Contributors <contact@firstruner.fr>
 * @copyright 2024-2026 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace System\Threading;

class Parallel
{
    public static function For($start, $end, callable $callback)
    {
        $processes = [];

        for ($i = $start; $i < $end; $i++) {
            $processes[] = self::runInBackground(function () use ($callback, $i) {
                ($callback)($i);
            });
        }

        // Attente de la fin des processus
        foreach ($processes as $process) {
            $process['status']();
        }
    }

    public static function Invoke(...$actions)
    {
        $processes = [];

        foreach ($actions as $action) {
            $processes[] = self::runInBackground($action);
        }

        // Attente de la fin des processus
        foreach ($processes as $process) {
            $process['status']();
        }
    }

    public static function RunInBackground(callable $callback)
    {
        // Création d'un fichier temporaire pour stocker les résultats de l'exécution
        $tempFile = tempnam(sys_get_temp_dir(), 'parallel_');
        $code = self::Code($callback, $tempFile);

        // Lancement du processus PHP en arrière-plan
        $cmd = "php -r \"$code\" > $tempFile &";
        exec($cmd);

        // Attendre la fin du processus en vérifiant l'existence du fichier
        while (!file_exists($tempFile)) {
            usleep(100000); // Attendre un peu avant de vérifier
        }

        // Lire le résultat
        $result = file_get_contents($tempFile);
        unlink($tempFile); // Supprimer le fichier temporaire

        return $result;
    }

    private static function Code(callable $callback, $tempFile)
    {
        // Convertir la fonction en code exécutable
        return "
                file_put_contents('$tempFile', 'Task started...\\n');
                try {
                    " . var_export($callback, true) . "();
                    file_put_contents('$tempFile', 'Task completed.\\n', FILE_APPEND);
                } catch (Exception \$e) {
                    file_put_contents('$tempFile', 'Error: ' . \$e->getMessage(), FILE_APPEND);
                }
            ";
    }
}
