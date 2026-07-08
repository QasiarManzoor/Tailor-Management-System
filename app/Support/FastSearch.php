<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FastSearch
{
    public static function customers(Builder $query, string $term): Builder
    {
        $term = self::clean($term);
        $digits = self::digits($term);
        $fullText = self::booleanPrefixTerm($term);

        return $query->where(function (Builder $query) use ($term, $digits, $fullText) {
            $query->where('customer_no', $term)
                ->orWhere('customer_no', 'like', self::prefix($term))
                ->orWhere('name', 'like', self::prefix($term));

            if ($digits !== '') {
                $query->orWhere('phone', $digits)
                    ->orWhere('phone', 'like', self::prefix($digits))
                    ->orWhere('alternate_phone', $digits)
                    ->orWhere('alternate_phone', 'like', self::prefix($digits));
            } else {
                $query->orWhere('phone', 'like', self::prefix($term))
                    ->orWhere('alternate_phone', 'like', self::prefix($term));
            }

            self::orFullText($query, 'name', $fullText);
        });
    }

    public static function orders(Builder $query, string $term): Builder
    {
        $term = self::clean($term);
        $fullText = self::booleanPrefixTerm($term);

        return $query->where(function (Builder $query) use ($term, $fullText) {
            $query->where('order_no', $term)
                ->orWhere('order_no', 'like', self::prefix($term))
                ->orWhere('order_type', 'like', self::prefix($term))
                ->orWhereHas('customer', fn (Builder $customerQuery) => self::customers($customerQuery, $term));

            self::orFullText($query, 'order_type', $fullText);
        });
    }

    public static function measurements(Builder $query, string $term): Builder
    {
        $term = self::clean($term);
        $fullText = self::booleanPrefixTerm($term);

        return $query->where(function (Builder $query) use ($term, $fullText) {
            $query->where('title', 'like', self::prefix($term))
                ->orWhereHas('customer', fn (Builder $customerQuery) => self::customers($customerQuery, $term));

            self::orFullText($query, 'title', $fullText);
        });
    }

    protected static function clean(string $term): string
    {
        return trim(preg_replace('/\s+/', ' ', $term) ?: '');
    }

    protected static function digits(string $term): string
    {
        return preg_replace('/\D+/', '', $term) ?: '';
    }

    protected static function prefix(string $term): string
    {
        return addcslashes($term, '\%_').'%';
    }

    protected static function booleanPrefixTerm(string $term): string
    {
        $tokens = preg_split('/[^\pL\pN]+/u', $term, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return collect($tokens)
            ->filter(fn (string $token) => mb_strlen($token) >= 2)
            ->map(fn (string $token) => '+'.$token.'*')
            ->implode(' ');
    }

    protected static function orFullText(Builder $query, string|array $columns, string $term): void
    {
        if ($term === '' || DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        $query->orWhereFullText($columns, $term, ['mode' => 'boolean']);
    }
}
