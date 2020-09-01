<?php

namespace App\Factory;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Review|Proxy findOrCreate(array $attributes)
 * @method static Review|Proxy random()
 * @method static Review[]|Proxy[] randomSet(int $number)
 * @method static Review[]|Proxy[] randomRange(int $min, int $max)
 * @method static ReviewRepository|RepositoryProxy repository()
 * @method Review|Proxy create($attributes = [])
 * @method Review[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class ReviewFactory extends ModelFactory
{
    /**
     * factory random review
     * see: https://github.com/zenstruck/foundry#model-factories
     * @return array
     */
    protected function getDefaults(): array
    {
        return [
            'score' => self::faker()->numberBetween(1, 5),
            'comment' => self::faker()->sentence,
            'created_date' => self::faker()->dateTimeBetween($startDate = '-2 years', $endDate = 'now'),
        ];
    }

    /**
     * see https://github.com/zenstruck/foundry#initialization
     * @return $this
     */
    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->beforeInstantiate(function(Review $review) {})
        ;
    }

    protected static function getClass(): string
    {
        return Review::class;
    }
}
