<?php

namespace App\Models\Comments\Traits;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;

/**
 * Trait GeoIP
 *
 * Provides IP address handling and geolocation functionality for comment models.
 * Uses GeoIP2 to detect the country associated with an IP address.
 */
trait GeoIP
{
    /**
     * Mutator for ip_address attribute.
     * Validates and sets the IP address, storing null for invalid IPs.
     *
     * @param string|null $value The IP address to set
     * @return void
     */
    public function setIpAddressAttribute($value): void
    {
        $this->attributes['ip_address'] = filter_var($value, FILTER_VALIDATE_IP) ?: null;
    }

    /**
     * Accessor for ip_address attribute.
     * Returns the IP address or 'Unknown' if not set.
     *
     * @param string|null $value The stored IP address
     * @return string
     */
    public function getIpAddressAttribute($value): string
    {
        return $value ?? 'Unknown';
    }

    /**
     * Boot the trait, adding creating and deleting event listeners.
     *
     * @return void
     */
    protected static function booted(): void
    {
        // Automatically set IP address and country on comment creation
        static::creating(function ($comment) {
            if (empty($comment->ip_address)) {
                $comment->ip_address = request()->ip() ?? null;
            }

            if (empty($comment->ip_country) && $comment->ip_address) {
                $comment->ip_country = self::detectCountry($comment->ip_address);
            }
        });

        // Handle cascading deletion of replies
        static::deleting(function ($comment) {
            $replies = $comment->replies();
            if ($comment->isForceDeleting()) {
                $replies->forceDelete();
            } else {
                $replies->delete();
            }
        });
    }

    /**
     * Detects the country associated with an IP address using GeoIP2.
     *
     * @param string|null $ip The IP address to geolocate
     * @return string|null The country name or null if detection fails
     */
    protected static function detectCountry(?string $ip): ?string
    {
        if (!$ip || !file_exists(storage_path('app/geoip/GeoLite2-Country.mmdb'))) {
            return null;
        }

        try {
            $reader = new Reader(storage_path('app/geoip/GeoLite2-Country.mmdb'));
            $record = $reader->country($ip);
            return $record->country->name ?? null;
        } catch (AddressNotFoundException|InvalidDatabaseException $e) {
            // Log error if needed: \Log::warning("GeoIP detection failed: {$e->getMessage()}");
            return null;
        }
    }
}
