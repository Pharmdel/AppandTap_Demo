<?php

namespace App\Support;

/**
 * Which staff context the pharmacy portal is rendering as: the Group Owner,
 * or one of its pharmacies. Picked from the header's portal dropdown and kept
 * in the session. As in the Pharmdel preview, choosing a pharmacy relabels
 * the portal as that pharmacy; the rest of the demo content is shared.
 */
class Portal
{
    public const GROUP_OWNER = 'group-owner';

    public const PHARMACY = 'pharmacy';

    public static function all(): array
    {
        return [self::GROUP_OWNER, self::PHARMACY];
    }

    public static function current(): string
    {
        $portal = session('portal');

        return in_array($portal, self::all(), true) ? $portal : self::GROUP_OWNER;
    }

    public static function isGroupOwner(): bool
    {
        return self::current() === self::GROUP_OWNER;
    }

    public static function findPharmacy(int $id): ?array
    {
        return collect(DemoData::staffPharmacies())->firstWhere('id', $id);
    }

    /**
     * The pharmacy chosen in the dropdown, falling back to the first one.
     */
    public static function pharmacy(): array
    {
        return self::findPharmacy((int) session('pharmacy_id')) ?? DemoData::staffPharmacies()[0];
    }
}
