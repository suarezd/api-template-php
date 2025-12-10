<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MetricsController
{
    #[Route('/metrics', methods: ['GET'])]
    public function metrics(): Response
    {
        $metrics = [];

        $opcacheStatus = opcache_get_status(false);
        if ($opcacheStatus) {
            $metrics[] = sprintf('# HELP opcache_memory_usage OPcache memory usage in bytes');
            $metrics[] = sprintf('# TYPE opcache_memory_usage gauge');
            $metrics[] = sprintf('opcache_memory_usage{type="used"} %d', $opcacheStatus['memory_usage']['used_memory']);
            $metrics[] = sprintf('opcache_memory_usage{type="free"} %d', $opcacheStatus['memory_usage']['free_memory']);

            $metrics[] = sprintf('# HELP opcache_hits_total Total number of OPcache hits');
            $metrics[] = sprintf('# TYPE opcache_hits_total counter');
            $metrics[] = sprintf('opcache_hits_total %d', $opcacheStatus['opcache_statistics']['hits']);

            $metrics[] = sprintf('# HELP opcache_misses_total Total number of OPcache misses');
            $metrics[] = sprintf('# TYPE opcache_misses_total counter');
            $metrics[] = sprintf('opcache_misses_total %d', $opcacheStatus['opcache_statistics']['misses']);

            $metrics[] = sprintf('# HELP opcache_scripts_cached Number of scripts cached');
            $metrics[] = sprintf('# TYPE opcache_scripts_cached gauge');
            $metrics[] = sprintf('opcache_scripts_cached %d', $opcacheStatus['opcache_statistics']['num_cached_scripts']);
        }

        if (extension_loaded('apcu')) {
            $apcuInfo = apcu_cache_info(true);
            $apcuSma = apcu_sma_info(true);

            $metrics[] = sprintf('# HELP apcu_memory_usage APCu memory usage in bytes');
            $metrics[] = sprintf('# TYPE apcu_memory_usage gauge');
            $metrics[] = sprintf('apcu_memory_usage{type="used"} %d', $apcuSma['seg_size'] - $apcuSma['avail_mem']);
            $metrics[] = sprintf('apcu_memory_usage{type="free"} %d', $apcuSma['avail_mem']);

            $metrics[] = sprintf('# HELP apcu_hits_total Total number of APCu hits');
            $metrics[] = sprintf('# TYPE apcu_hits_total counter');
            $metrics[] = sprintf('apcu_hits_total %d', $apcuInfo['num_hits']);

            $metrics[] = sprintf('# HELP apcu_misses_total Total number of APCu misses');
            $metrics[] = sprintf('# TYPE apcu_misses_total counter');
            $metrics[] = sprintf('apcu_misses_total %d', $apcuInfo['num_misses']);

            $metrics[] = sprintf('# HELP apcu_entries Number of entries in APCu cache');
            $metrics[] = sprintf('# TYPE apcu_entries gauge');
            $metrics[] = sprintf('apcu_entries %d', $apcuInfo['num_entries']);
        }

        $metrics[] = sprintf('# HELP php_memory_usage PHP memory usage in bytes');
        $metrics[] = sprintf('# TYPE php_memory_usage gauge');
        $metrics[] = sprintf('php_memory_usage %d', memory_get_usage(true));

        $metrics[] = sprintf('# HELP php_memory_peak_usage PHP peak memory usage in bytes');
        $metrics[] = sprintf('# TYPE php_memory_peak_usage gauge');
        $metrics[] = sprintf('php_memory_peak_usage %d', memory_get_peak_usage(true));

        return new Response(
            implode("\n", $metrics) . "\n",
            200,
            ['Content-Type' => 'text/plain; version=0.0.4']
        );
    }
}
