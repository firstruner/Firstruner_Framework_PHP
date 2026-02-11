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
 * Please refer to https:*firstruner.fr/ or contact Firstruner for more information.
 *
 * @author    Firstruner and Contributors <contact@firstruner.fr>
 * @copyright 2024-2026 Firstruner and Contributors
 * @license   Proprietary
 * @version 2.0.0
 */

namespace Firstruner\Classes\Algorithms;

abstract class EulerTracks
{
      /**
       * Ne retourne rien, car l'on utilise une variable par référence (output)
       * plutôt que par valeur
       */
      private static function adjacencyBuilder(array $sommets, array $jonctions, array &$output): void
      {
            foreach ($sommets as $v) $output[$v] = [];

            foreach ($jonctions as $eid => $e) {
                  list($u, $v) = $e;

                  $output[$u][] = [$v, $eid];
                  $output[$v][] = [$u, $eid];
            }
      }

      /**
       * Toujours pas de valeur de retour
       * Mais utilisation de 2 variables par références
       */
      private static function degreesAndTouched(array $jonctions, array &$degrees, array &$touched): void
      {
            foreach ($jonctions as $e) {
                  list($u, $v) = $e;

                  if (!isset($degrees[$u])) $degrees[$u] = 0;
                  if (!isset($degrees[$v])) $degrees[$v] = 0;

                  $degrees[$u]++;
                  $degrees[$v]++;
                  $touched[$u] = true;
                  $touched[$v] = true;
            }
      }

      private static function countEulerTrails(array $sommets, array $jonctions, bool $OnlySymetrie = false): int
      {
            $nE = count($jonctions);
            if ($nE === 0) return 0;

            $adj = [];
            EulerTracks::adjacencyBuilder($sommets, $jonctions, $adj);

            // degrees and touched
            $deg = [];
            $touched = [];
            EulerTracks::degreesAndTouched($jonctions, $deg, $touched);

            // connectivity check
            $start0 = array_keys($touched)[0];
            $stack = [$start0];
            $seen = [$start0 => true];

            while (!empty($stack)) {
                  $x = array_pop($stack);

                  foreach ($adj[$x] as $p) {
                        $y = $p[0];
                        if (!isset($seen[$y])) {
                              $seen[$y] = true;
                              $stack[] = $y;
                        }
                  }
            }

            foreach ($touched as $v => $_)
                  if (!isset($seen[$v]))
                        return 0;

            // odd degree
            $odd = [];
            foreach ($touched as $v => $_)
                  if (($deg[$v] % 2) == 1)
                        $odd[] = $v;

            if (!(count($odd) == 0 || count($odd) == 2))
                  return 0;

            $starts = [];
            if (count($odd) == 2) $starts = $odd;
            else {
                  foreach ($sommets as $v) if (isset($deg[$v]) && $deg[$v] > 0) $starts[] = $v;
            }

            // DFS with memoization (use associative cache)
            $cache = [];

            $allMask = (1 << $nE) - 1; // careful: PHP int is platform dependent (64-bit typical)
            $dfs = function ($current, $usedMask) use (&$dfs, &$adj, $nE, $allMask, &$cache) {
                  $key = $current . '|' . $usedMask;
                  if (isset($cache[$key])) return $cache[$key];
                  if ($usedMask === $allMask) return $cache[$key] = 1;
                  $total = 0;

                  foreach ($adj[$current] as $p) {
                        list($nxt, $eid) = $p;
                        if ((($usedMask >> $eid) & 1) === 0)
                              $total += $dfs($nxt, $usedMask | (1 << $eid));
                  }

                  return $cache[$key] = $total;
            };

            $totalOriented = 0;
            foreach ($starts as $s) $totalOriented += $dfs($s, 0);

            if ($OnlySymetrie)
                  return intdiv($totalOriented, 2);

            return $totalOriented;
      }

      public static function CountTracks(array $Jonctions, bool $OnlySymetries = false): int
      {
            // Aplatir le tableau multidimensionnel
            $flat = array_merge(...$Jonctions);

            // Supprimer les doublons
            $sommets = array_values(array_unique($flat));

            return EulerTracks::countEulerTrails(
                  $sommets,
                  $Jonctions,
                  $OnlySymetries
            );
      }
}
