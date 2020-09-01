<?php

namespace App\Factory;

use App\Entity\Hotel;
use App\Repository\HotelRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Hotel|Proxy findOrCreate(array $attributes)
 * @method static Hotel|Proxy random()
 * @method static Hotel[]|Proxy[] randomSet(int $number)
 * @method static Hotel[]|Proxy[] randomRange(int $min, int $max)
 * @method static HotelRepository|RepositoryProxy repository()
 * @method Hotel|Proxy create($attributes = [])
 * @method Hotel[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class HotelFactory extends ModelFactory
{
    /**
     * factory random hotel
     * see: https://github.com/zenstruck/foundry#model-factories
     * @return array
     */
    protected function getDefaults(): array
    {
        return [
            'name' =>  self::faker()->unique()->company,
        ];
    }

    /**
     * see https://github.com/zenstruck/foundry#initialization
     * @return $this
     */
    protected function initialize(): self
    {
        //
        return $this
            // ->beforeInstantiate(function(Hotel $hotel) {})
        ;
    }

    protected static function getClass(): string
    {
        return Hotel::class;
    }
}
